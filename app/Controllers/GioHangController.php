<?php

namespace App\Controllers;

use App\Models\GioHang;
use App\Models\PhienKhach;
use App\Models\BienTheSanPham;
use App\Models\NguoiDung;
use App\Models\DonHang;
use App\Models\DiaChiDonHang;
use App\Models\ChiTietDonHang;
use App\Models\MaGiamGia;
use Doctrine\ORM\EntityManager;

class GioHangController
{
    private EntityManager $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
    }

    public function themVaoGio()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }

        $bienTheId = (int)($_POST['bien_the_id'] ?? 0);
        $soLuong = (int)($_POST['so_luong'] ?? 1);

        if ($bienTheId <= 0 || $soLuong <= 0) {
            echo json_encode(['success' => false, 'message' => 'Thông tin sản phẩm không hợp lệ.']);
            return;
        }

        $bienThe = $this->em->getRepository(BienTheSanPham::class)->find($bienTheId);

        if (!$bienThe || !$bienThe->isKichHoat() || $bienThe->isHetHang()) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã hết hàng.']);
            return;
        }

        if (isset($_SESSION['user_id'])) {
            $this->themVaoGioNguoiDung($_SESSION['user_id'], $bienThe, $soLuong);
        } else {
            $this->themVaoGioKhach($bienTheId, $soLuong);
        }
    }

    private function themVaoGioNguoiDung(int $nguoiDungId, BienTheSanPham $bienThe, int $soLuong)
    {
        try {
            $nguoiDung = $this->em->getReference(NguoiDung::class, $nguoiDungId);

            $gioHangItem = $this->em->getRepository(GioHang::class)->findOneBy([
                'nguoiDung' => $nguoiDung,
                'bienTheSanPham' => $bienThe
            ]);

            if ($gioHangItem) {
                $gioHangItem->tangSoLuong($soLuong);
            } else {
                $gioHangItem = new GioHang();
                $gioHangItem->setNguoiDung($nguoiDung);
                $gioHangItem->setBienTheSanPham($bienThe);
                $gioHangItem->setSoLuong($soLuong);
                $this->em->persist($gioHangItem);
            }

            $this->em->flush();

            $totalItems = $this->getCartItemCountForUser($nguoiDungId);

            echo json_encode([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
                'cartItemCount' => $totalItems // **NEW**: Add count to response
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Không thể thêm sản phẩm vào giỏ hàng.']);
        }
    }

    private function themVaoGioKhach(int $bienTheId, int $soLuong)
    {
        try {
            $maPhien = $_SESSION['ma_phien'] ?? null;
            $phienKhach = null;

            if ($maPhien) {
                $phienKhach = $this->em->getRepository(PhienKhach::class)->findOneBy(['maPhien' => $maPhien]);
            }

            if (!$phienKhach || $phienKhach->isExpired()) {
                $phienKhach = new PhienKhach();
                $this->em->persist($phienKhach);
            }

            $phienKhach->addToCart($bienTheId, $soLuong); //
            $_SESSION['ma_phien'] = $phienKhach->getMaPhien();

            $this->em->flush();

            // **NEW**: Get total items from the PhienKhach entity
            $totalItems = $phienKhach->getCartItemCount(); //

            echo json_encode([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng.',
                'cartItemCount' => $totalItems // **NEW**: Add count to response
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Không thể thêm sản phẩm vào giỏ hàng khách.']);
        }
    }

    /**
     * **NEW**: Helper function to get total item count for a logged-in user.
     */
    private function getCartItemCountForUser(int $nguoiDungId): int
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('sum(gh.soLuong)')
            ->from(GioHang::class, 'gh')
            ->where('gh.nguoiDung = :userId')
            ->setParameter('userId', $nguoiDungId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    // public function index()
    // {
    //     $cartItems = []; // <--- Dùng mảng mới này

    //     if (isset($_SESSION['user_id'])) {
    //         $nguoiDung = $this->em->getReference(NguoiDung::class, $_SESSION['user_id']);
    //         $cartRows = $this->em->getRepository(GioHang::class)->findBy([
    //             'nguoiDung' => $nguoiDung
    //         ]);
    //         foreach ($cartRows as $item) {
    //             $cartItems[] = [
    //                 'bienThe' => $item->getBienTheSanPham(),
    //                 'qty' => $item->getSoLuong(),
    //             ];
    //         }
    //     } else {
    //         $maPhien = $_SESSION['ma_phien'] ?? null;
    //         if ($maPhien) {
    //             $phienKhach = $this->em->getRepository(PhienKhach::class)->findOneBy(['maPhien' => $maPhien]);
    //             if ($phienKhach && !$phienKhach->isExpired()) {
    //                 $duLieuGioHang = $phienKhach->getDuLieuGioHang() ?? [];
    //                 foreach ($duLieuGioHang as $item) {
    //                     $bienThe = $this->em->getRepository(BienTheSanPham::class)->find($item['bien_the_id']);
    //                     if ($bienThe) {
    //                         $cartItems[] = [
    //                             'bienThe' => $bienThe,
    //                             'qty' => $item['so_luong'],
    //                         ];
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     view('giohang/index', ['cartItems' => $cartItems]);
    // }

   
    public function capNhat()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Phương thức không hợp lệ');
        }

        $quantities = $_POST['quantities'] ?? [];
        $ids = $_POST['ids'] ?? [];

        if (!$quantities || !$ids) {
            header('Location: /gio-hang');
            exit;
        }

        if (isset($_SESSION['user_id'])) {
            // User đăng nhập: cập nhật từ DB
            foreach ($ids as $i => $id) {
                $soLuong = (int)($quantities[$i] ?? 1);
                if ($soLuong <= 0) $soLuong = 1;

                $cartItem = $this->em->getRepository(\App\Models\GioHang::class)->findOneBy([
                    'bienTheSanPham' => $id,
                    'nguoiDung' => $this->em->getReference(\App\Models\NguoiDung::class, $_SESSION['user_id'])
                ]);
                if ($cartItem) {
                    $cartItem->setSoLuong($soLuong);
                    $this->em->flush();
                }
            }
        } else {
            // Khách vãng lai: cập nhật trong session
            $maPhien = $_SESSION['ma_phien'] ?? null;
            if ($maPhien) {
                $phienKhach = $this->em->getRepository(\App\Models\PhienKhach::class)->findOneBy(['maPhien' => $maPhien]);
                if ($phienKhach && !$phienKhach->isExpired()) {
                    $duLieu = $phienKhach->getDuLieuGioHang() ?? [];
                    foreach ($ids as $i => $id) {
                        $soLuong = (int)($quantities[$i] ?? 1);
                        if (isset($duLieu[$id])) {
                            if ($soLuong <= 0) {
                                unset($duLieu[$id]);
                            } else {
                                $duLieu[$id]['so_luong'] = $soLuong;
                            }
                        }
                    }
                    $phienKhach->setDuLieuGioHang($duLieu);
                    $this->em->flush();
                }
            }
        }

        header('Location: /gio-hang');
        exit;
    }

    public function xoa()
    {
        $id = $_GET['id'] ?? ($_POST['id'] ?? null);
        if (!$id) {
            header('Location: /gio-hang');
            exit;
        }

        if (isset($_SESSION['user_id'])) {
            // Xóa trong DB cho user đã đăng nhập
            $cartItem = $this->em->getRepository(\App\Models\GioHang::class)->findOneBy([
                'bienTheSanPham' => $id,
                'nguoiDung' => $this->em->getReference(\App\Models\NguoiDung::class, $_SESSION['user_id'])
            ]);
            if ($cartItem) {
                $this->em->remove($cartItem);
                $this->em->flush();
            }
        } else {
            // Xóa trong session cho khách vãng lai
            $maPhien = $_SESSION['ma_phien'] ?? null;
            if ($maPhien) {
                $phienKhach = $this->em->getRepository(\App\Models\PhienKhach::class)->findOneBy(['maPhien' => $maPhien]);
                if ($phienKhach && !$phienKhach->isExpired()) {
                    $duLieu = $phienKhach->getDuLieuGioHang() ?? [];
                    if (isset($duLieu[$id])) {
                        unset($duLieu[$id]);
                    }
                    $phienKhach->setDuLieuGioHang($duLieu);
                    $this->em->flush();
                }
            }
        }

        header('Location: /gio-hang');
        exit;
    }

    public function checkout()
    {
        // Nếu chưa đăng nhập, chuyển sang trang đăng nhập và truyền kèm redirect
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = '/gio-hang/checkout';
            header('Location: /dang-nhap');
            exit;
        }

        // Phần lấy giỏ hàng như bạn đã có ở trên
        $cartItems = [];
        $nguoiDung = $this->em->getReference(NguoiDung::class, $_SESSION['user_id']);
        $cartRows = $this->em->getRepository(GioHang::class)->findBy([
            'nguoiDung' => $nguoiDung
        ]);
        foreach ($cartRows as $item) {
            $cartItems[] = [
                'bienThe' => $item->getBienTheSanPham(),
                'qty' => $item->getSoLuong(),
            ];
        }
        $user = $this->em->getRepository(NguoiDung::class)->find($_SESSION['user_id']);

        view('giohang/checkout', [
            'cartItems' => $cartItems,
            'user' => $user
        ]);
    }

    public function index()
    {
        $cartItems = [];

        // Lấy giỏ hàng người dùng đăng nhập hoặc khách vãng lai
        if (isset($_SESSION['user_id'])) {
            $nguoiDung = $this->em->getReference(NguoiDung::class, $_SESSION['user_id']);
            $cartRows = $this->em->getRepository(GioHang::class)->findBy([
                'nguoiDung' => $nguoiDung
            ]);
            foreach ($cartRows as $item) {
                $cartItems[] = [
                    'bienThe' => $item->getBienTheSanPham(),
                    'qty' => $item->getSoLuong(),
                ];
            }
        } else {
            $maPhien = $_SESSION['ma_phien'] ?? null;
            if ($maPhien) {
                $phienKhach = $this->em->getRepository(PhienKhach::class)->findOneBy(['maPhien' => $maPhien]);
                if ($phienKhach && !$phienKhach->isExpired()) {
                    $duLieuGioHang = $phienKhach->getDuLieuGioHang() ?? [];
                    foreach ($duLieuGioHang as $item) {
                        $bienThe = $this->em->getRepository(BienTheSanPham::class)->find($item['bien_the_id']);
                        if ($bienThe) {
                            $cartItems[] = [
                                'bienThe' => $bienThe,
                                'qty' => $item['so_luong'],
                            ];
                        }
                    }
                }
            }
            $nguoiDung = null; // Khách chưa đăng nhập
        }

        // Tính tổng tiền tạm tính
        $tamTinh = 0;
        foreach ($cartItems as $item) {
            $tamTinh += $item['bienThe']->getGia() * $item['qty'];
        }


        // Lấy thông tin người dùng nếu đăng nhập
        if (isset($_SESSION['user_id'])) {
            $nguoiDungEntity = $this->em->getRepository(NguoiDung::class)->find($_SESSION['user_id']);
        } else {
            $nguoiDungEntity = null;
        }

        // Mã giảm giá (nếu có truyền qua query string, ví dụ khi lỗi mã)
        $maGiamGiaApDung = $_GET['ma_giam_gia'] ?? '';

        view('giohang/index', [
            'cartItems' => $cartItems,
            'tamTinh' => $tamTinh,
            'tongCong' => $tamTinh, // chưa trừ giảm giá
            'giamGiaTien' => 0,
            'nguoiDung' => $nguoiDungEntity,
            'maGiamGiaApDung' => $maGiamGiaApDung,
        ]);
    }

    private function layGioHang(): array
    {
        $cartItems = [];
        if (isset($_SESSION['user_id'])) {
            $nguoiDung = $this->em->getReference(NguoiDung::class, $_SESSION['user_id']);
            $cartRows = $this->em->getRepository(GioHang::class)->findBy(['nguoiDung' => $nguoiDung]);
            foreach ($cartRows as $item) {
                $cartItems[] = [
                    'bienThe' => $item->getBienTheSanPham(),
                    'qty' => $item->getSoLuong(),
                ];
            }
        } else {
            $maPhien = $_SESSION['ma_phien'] ?? null;
            if ($maPhien) {
                $phienKhach = $this->em->getRepository(PhienKhach::class)->findOneBy(['maPhien' => $maPhien]);
                if ($phienKhach && !$phienKhach->isExpired()) {
                    $duLieuGioHang = $phienKhach->getDuLieuGioHang() ?? [];
                    foreach ($duLieuGioHang as $item) {
                        $bienThe = $this->em->getRepository(BienTheSanPham::class)->find($item['bien_the_id']);
                        if ($bienThe) {
                            $cartItems[] = [
                                'bienThe' => $bienThe,
                                'qty' => $item['so_luong'],
                            ];
                        }
                    }
                }
            }
        }
        return $cartItems;
    }

    private function xoaGioHang(): void
    {
        if (isset($_SESSION['user_id'])) {
            $nguoiDung = $this->em->getReference(NguoiDung::class, $_SESSION['user_id']);
            $cartItems = $this->em->getRepository(GioHang::class)->findBy(['nguoiDung' => $nguoiDung]);
            foreach ($cartItems as $item) {
                $this->em->remove($item);
            }
            $this->em->flush();
        } else {
            $maPhien = $_SESSION['ma_phien'] ?? null;
            if ($maPhien) {
                $phienKhach = $this->em->getRepository(PhienKhach::class)->findOneBy(['maPhien' => $maPhien]);
                if ($phienKhach) {
                    $phienKhach->setDuLieuGioHang([]);
                    $this->em->flush();
                }
            }
        }
    }


    public function datHang()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartItems = $this->layGioHang();
        if (empty($cartItems)) {
            header('Location: /gio-hang');
            exit;
        }

        // Lấy dữ liệu từ form (bạn cần bổ sung các trường địa chỉ chi tiết)
        $hoTen = $_POST['ho_ten'] ?? null;
        $soDienThoai = $_POST['so_dien_thoai'] ?? null;
        $diaChi1 = $_POST['dia_chi_1'] ?? null;
        $diaChi2 = $_POST['dia_chi_2'] ?? null;
        $thanhPho = $_POST['thanh_pho'] ?? null;
        $tinhThanh = $_POST['tinh_thanh'] ?? null;
        $maBuuDien = $_POST['ma_buu_dien'] ?? null;
        $hinhThucThanhToan = $_POST['hinh_thuc_thanh_toan'] ?? 'cod';
        $maGiamGiaCode = trim($_POST['ma_giam_gia'] ?? '');
        $emailKhach = $_POST['email_khach'] ?? null;
        $ghiChu = $_POST['ghi_chu'] ?? null;

        if (!$hoTen || !$soDienThoai || !$diaChi1 || !$thanhPho || !$tinhThanh) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location: /gio-hang/checkout');
            exit;
        }

        $em = $this->em;
        $donHang = new DonHang();

        if (isset($_SESSION['user_id'])) {
            $nguoiDung = $em->getReference(NguoiDung::class, $_SESSION['user_id']);
            $donHang->setNguoiDung($nguoiDung);
        } else {
            $donHang->setNguoiDung(null);
            $donHang->setEmailKhach($emailKhach);
        }

        $tongPhu = 0;
        foreach ($cartItems as $item) {
            $tongPhu += $item['bienThe']->getGia() * $item['qty'];
        }
        $donHang->setTongPhu($tongPhu);

        $donHang->setPhiVanChuyen(0);
        $donHang->setTienThue(0);

        if ($maGiamGiaCode) {
            $maGiamGiaEntity = $em->getRepository(MaGiamGia::class)->findOneBy(['maGiamGia' => $maGiamGiaCode]);
            if ($maGiamGiaEntity && $maGiamGiaEntity->coTheSuDung()) {
                $donHang->apDungMaGiamGia($maGiamGiaEntity);
            } else {
                $donHang->boMaGiamGia();
                $_SESSION['error_ma_giam_gia'] = 'Mã giảm giá không hợp lệ hoặc đã hết hạn.';
                header('Location: /gio-hang/checkout');
                exit;
            }
        }

        // $donHang->setTongTien($donHang->getTongTien());
        $donHang->setGhiChu($ghiChu);
        $donHang->setTrangThai('cho_xu_ly');
        $donHang->setTrangThaiThanhToan($hinhThucThanhToan === 'cod' ? 'cho_thanh_toan' : 'chua_thanh_toan');

        // Tách họ tên nếu muốn
        $ho = '';
        $ten = '';
        if ($hoTen) {
            $parts = explode(' ', trim($hoTen));
            $ten = array_pop($parts);
            $ho = implode(' ', $parts);
        }

        $diaChiEntity = new DiaChiDonHang();
        $diaChiEntity->setLoai('giao_hang');
        $diaChiEntity->setHo($ho);
        $diaChiEntity->setTen($ten);
        $diaChiEntity->setDiaChi1($diaChi1);
        $diaChiEntity->setDiaChi2($diaChi2);
        $diaChiEntity->setThanhPho($thanhPho);
        $diaChiEntity->setTinhThanh($tinhThanh);
        $diaChiEntity->setMaBuuDien($maBuuDien);
        $diaChiEntity->setSoDienThoai($soDienThoai);

        $donHang->getDiaChis()->add($diaChiEntity);
        $diaChiEntity->setDonHang($donHang);

        foreach ($cartItems as $item) {
            $chiTiet = new ChiTietDonHang();
            $chiTiet->setDonHang($donHang);
            $chiTiet->setBienTheSanPham($item['bienThe']);
            $chiTiet->setSoLuong($item['qty']);
            $chiTiet->setDonGia($item['bienThe']->getGia());
            $donHang->getChiTiets()->add($chiTiet);
        }

        $em->persist($donHang);
        $em->flush();

        $this->xoaGioHang();

        header('Location: /don-hang/cam-on?ma=' . urlencode($donHang->getMaDonHang()));
        exit;
    }
}
