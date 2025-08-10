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
use App\Models\ThanhToan;
use App\Services\EmailService;
use App\Services\DichVuMaGiamGia;
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
        // Phân nhánh người dùng và  khách
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

    public function index()
    {
        $cartItems = []; // <--- Dùng mảng mới này

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
        }
        view('giohang/index', ['cartItems' => $cartItems]);
    }



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
        $cartItems = $this->getCartItems();
        
        if (empty($cartItems)) {
            header('Location: /gio-hang');
            exit;
        }

        $user = null;
        if (isset($_SESSION['user_id'])) {
            $user = $this->em->getRepository(NguoiDung::class)->find($_SESSION['user_id']);
        }

        view('giohang/checkout', [
            'cartItems' => $cartItems,
            'user' => $user
        ]);
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /gio-hang/checkout');
            exit;
        }

        $cartItems = $this->getCartItems();
        
        if (empty($cartItems)) {
            header('Location: /gio-hang');
            exit;
        }

        // Validate input data
        $validationErrors = $this->validateCheckoutData($_POST);
        if (!empty($validationErrors)) {
            $user = null;
            if (isset($_SESSION['user_id'])) {
                $user = $this->em->getRepository(NguoiDung::class)->find($_SESSION['user_id']);
            }
            
            view('giohang/checkout', [
                'cartItems' => $cartItems,
                'user' => $user,
                'errors' => $validationErrors,
                'formData' => $_POST
            ]);
            return;
        }

        try {
            // Create order
            $donHang = $this->createOrder($_POST, $cartItems);
            
            // Clear cart after successful order
            $this->clearCart();
            
            // Redirect to success page
            header('Location: /don-hang/thanh-cong/' . $donHang->getMaDonHang());
            exit;
            
        } catch (\Exception $e) {
            error_log('Checkout error: ' . $e->getMessage());
            
            $user = null;
            if (isset($_SESSION['user_id'])) {
                $user = $this->em->getRepository(NguoiDung::class)->find($_SESSION['user_id']);
            }
            
            view('giohang/checkout', [
                'cartItems' => $cartItems,
                'user' => $user,
                'error' => 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.',
                'formData' => $_POST
            ]);
        }
    }

    private function getCartItems(): array
    {
        $cartItems = [];

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
        }

        return $cartItems;
    }

    private function validateCheckoutData(array $data): array
    {
        $errors = [];

        // For guest checkout
        if (!isset($_SESSION['user_id'])) {
            if (empty($data['guest_email']) || !filter_var($data['guest_email'], FILTER_VALIDATE_EMAIL)) {
                $errors['guest_email'] = 'Email không hợp lệ';
            }
//            if (empty($data['guest_phone'])) {
//                $errors['guest_phone'] = 'Số điện thoại không được để trống';
//            }
        }

        // Address validation
        if (empty($data['ho'])) {
            $errors['ho'] = 'Họ không được để trống';
        }
        if (empty($data['ten'])) {
            $errors['ten'] = 'Tên không được để trống';
        }
        if (empty($data['dia_chi_1'])) {
            $errors['dia_chi_1'] = 'Địa chỉ không được để trống';
        }
        if (empty($data['tinh_thanh'])) {
            $errors['tinh_thanh'] = 'Tỉnh/thành không được để trống';
        }
        if (empty($data['huyen_quan'])) {
            $errors['huyen_quan'] = 'Quận/huyện không được để trống';
        }
        if (empty($data['xa_phuong'])) {
            $errors['xa_phuong'] = 'Phường/xã không được để trống';
        }
//        if (empty($data['ma_buu_dien'])) {
//            $errors['ma_buu_dien'] = 'Mã bưu điện không được để trống';
//        }
        if (empty($data['so_dien_thoai'])) {
            $errors['so_dien_thoai'] = 'Số điện thoại không được để trống';
        }

        // Validate payment method
        if (empty($data['payment_method'])) {
            $errors['payment_method'] = 'Vui lòng chọn phương thức thanh toán';
        } else {
            $validPaymentMethods = ['cod', 'banking', 'momo', 'zalopay'];
            if (!in_array($data['payment_method'], $validPaymentMethods)) {
                $errors['payment_method'] = 'Phương thức thanh toán không hợp lệ';
            }
        }

        // Validate discount code if provided
        if (!empty($data['applied_discount_code'])) {
            $dichVuMaGiamGia = new DichVuMaGiamGia($this->em);
            $maGiamGia = $dichVuMaGiamGia->kiemTraMaGiamGia($data['applied_discount_code']);
            
            if (!$maGiamGia) {
                $errors['discount_code'] = 'Mã giảm giá không hợp lệ';
            } else {
                // Calculate order total for validation
                $cartItems = $this->getCartItems();
                $tongPhu = 0;
                foreach ($cartItems as $item) {
                    $tongPhu += $item['bienThe']->getGia() * $item['qty'];
                }
                
                if (!$dichVuMaGiamGia->kiemTraGiaTriToiThieu($maGiamGia, $tongPhu)) {
                    $errors['discount_code'] = 'Đơn hàng chưa đủ giá trị tối thiểu để áp dụng mã giảm giá';
                }
            }
        }

        return $errors;
    }

    private function createOrder(array $data, array $cartItems): DonHang
    {
        $this->em->beginTransaction();

        try {
            // Create order
            $donHang = new DonHang();
            
            // Set user if logged in, otherwise set guest email
            if (isset($_SESSION['user_id'])) {
                $nguoiDung = $this->em->getReference(NguoiDung::class, $_SESSION['user_id']);
                $donHang->setNguoiDung($nguoiDung);
            } else {
                $donHang->setEmailKhach($data['guest_email']);
            }

            // Calculate totals
            $tongPhu = 0;
            foreach ($cartItems as $item) {
                $tongPhu += $item['bienThe']->getGia() * $item['qty'];
            }
            
            $donHang->setTongPhu($tongPhu);
            $donHang->setPhiVanChuyen(30000); // Fixed shipping fee
            $donHang->setGhiChu($data['ghi_chu'] ?? '');

            // Apply discount code if provided
            if (!empty($data['applied_discount_code'])) {
                $dichVuMaGiamGia = new DichVuMaGiamGia($this->em);
                $maGiamGia = $dichVuMaGiamGia->kiemTraMaGiamGia($data['applied_discount_code']);
                
                if ($maGiamGia && $dichVuMaGiamGia->kiemTraGiaTriToiThieu($maGiamGia, $tongPhu)) {
                    $donHang->apDungMaGiamGia($maGiamGia);
                }
            }

            $this->em->persist($donHang);
            $this->em->flush(); // Need to flush to get the ID

            // Create shipping address
            $diaChiGiaoHang = new DiaChiDonHang();
            $diaChiGiaoHang->setDonHang($donHang);
            $diaChiGiaoHang->setLoai('giao_hang');
            $diaChiGiaoHang->setHo($data['ho']);
            $diaChiGiaoHang->setTen($data['ten']);
            $diaChiGiaoHang->setCongTy($data['cong_ty'] ?? null);
            $diaChiGiaoHang->setDiaChi1($data['dia_chi_1']);
//            $diaChiGiaoHang->setDiaChi2($data['dia_chi_2'] ?? null);
            $diaChiGiaoHang->setThanhPho($data['thanh_pho'] ?? $data['tinh_thanh']); // Use province for city if city not provided
            $diaChiGiaoHang->setTinhThanh($data['tinh_thanh']);
            $diaChiGiaoHang->setHuyenQuan($data['huyen_quan']);
            $diaChiGiaoHang->setXaPhuong($data['xa_phuong']);
//            $diaChiGiaoHang->setMaBuuDien($data['ma_buu_dien']);
            $diaChiGiaoHang->setSoDienThoai($data['so_dien_thoai']);

            $this->em->persist($diaChiGiaoHang);

            // Create order items
            foreach ($cartItems as $item) {
                $chiTiet = new ChiTietDonHang();
                $chiTiet->setDonHang($donHang);
                $chiTiet->setBienTheSanPham($item['bienThe']);
                $chiTiet->setTenSanPham($item['bienThe']->getSanPham()->getTen());
                $chiTiet->setMaSanPham($item['bienThe']->getMaSanPham());
                $chiTiet->setSoLuong($item['qty']);
                $chiTiet->setGiaDonVi($item['bienThe']->getGia());
                
                // Store variant details as JSON
                $chiTietBienThe = [
                    'mau_sac' => $item['bienThe']->getMauSac(),
                    'bo_nho' => $item['bienThe']->getBoNho(),
                ];
                $chiTiet->setChiTietBienThe($chiTietBienThe);

                $this->em->persist($chiTiet);
            }

            // Create payment record
            $thanhToan = new ThanhToan();
            $thanhToan->setDonHang($donHang);
            $thanhToan->setPhuongThucThanhToan($this->getPaymentMethodName($data['payment_method']));
            $thanhToan->setCongThanhToan($this->getPaymentGateway($data['payment_method']));
            $thanhToan->setSoTien($donHang->getTongTien());
            $thanhToan->setTienTe('VND');
            
            // Set payment status based on method
            if ($data['payment_method'] === 'cod') {
                $thanhToan->setTrangThai('cho_thanh_toan');
            } else {
                $thanhToan->setTrangThai('cho_xu_ly');
            }

            $this->em->persist($thanhToan);

            $this->em->flush();
            $this->em->commit();

            // Send admin notification email
            $this->sendAdminNotification($donHang);

            return $donHang;

        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    private function clearCart(): void
    {
        if (isset($_SESSION['user_id'])) {
            // Clear user cart from database
            $nguoiDung = $this->em->getReference(NguoiDung::class, $_SESSION['user_id']);
            $cartItems = $this->em->getRepository(GioHang::class)->findBy([
                'nguoiDung' => $nguoiDung
            ]);
            
            foreach ($cartItems as $item) {
                $this->em->remove($item);
            }
            $this->em->flush();
        } else {
            // Clear guest cart from session
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

    private function sendAdminNotification(DonHang $donHang): void
    {
        try {
            $emailConfig = require __DIR__ . '/../../config/email.php';
            $emailService = new EmailService($emailConfig);

            // Refresh the order to ensure all relationships are loaded
            $this->em->refresh($donHang);
            
            // Get shipping address
            $diaChiGiaoHang = null;
            foreach ($donHang->getDiaChis() as $diaChi) {
                if ($diaChi->getLoai() === 'giao_hang') {
                    $diaChiGiaoHang = $diaChi;
                    break;
                }
            }

            // Determine customer name and contact info
            $tenKhachHang = 'Khách vãng lai';
            $emailKhachHang = $donHang->getEmailKhach() ?? 'N/A';
            $soDienThoai = 'N/A';

            if ($donHang->getNguoiDung()) {
                $tenKhachHang = $donHang->getNguoiDung()->getHoTen();
                $emailKhachHang = $donHang->getNguoiDung()->getEmail();
                $soDienThoai = $donHang->getNguoiDung()->getSoDienThoai() ?? 'N/A';
            }

            if ($diaChiGiaoHang) {
                $tenKhachHang = $diaChiGiaoHang->getHoTen();
                $soDienThoai = $diaChiGiaoHang->getSoDienThoai() ?? $soDienThoai;
            }

            $orderData = [
                'id' => $donHang->getId(),
                'ma_don_hang' => $donHang->getMaDonHang(),
                'ngay_tao' => $donHang->getNgayTao()->format('d/m/Y H:i'),
                'ten_khach_hang' => $tenKhachHang,
                'email_khach_hang' => $emailKhachHang,
                'so_dien_thoai' => $soDienThoai,
                'trang_thai' => ucfirst(str_replace('_', ' ', $donHang->getTrangThai())),
                'tong_phu_formatted' => number_format($donHang->getTongPhu(), 0, ',', '.') . ' ₫',
                'phi_van_chuyen_formatted' => number_format($donHang->getPhiVanChuyen(), 0, ',', '.') . ' ₫',
                'tong_tien_formatted' => $donHang->getTongTienFormatted(),
                'dia_chi' => [
                    'ho_ten' => $diaChiGiaoHang ? $diaChiGiaoHang->getHoTen() : $tenKhachHang,
                    'dia_chi_day_du' => $diaChiGiaoHang ? $diaChiGiaoHang->getDiaChiDayDu() : 'Chưa có địa chỉ',
                    'so_dien_thoai' => $soDienThoai
                ],
                'items' => []
            ];

            // Add order items
            foreach ($donHang->getChiTiets() as $chiTiet) {
                $orderData['items'][] = [
                    'ten_san_pham' => $chiTiet->getTenSanPham(),
                    'so_luong' => $chiTiet->getSoLuong(),
                    'gia_don_vi_formatted' => number_format($chiTiet->getGiaDonVi(), 0, ',', '.') . ' ₫',
                    'tong_gia_formatted' => number_format($chiTiet->getGiaDonVi() * $chiTiet->getSoLuong(), 0, ',', '.') . ' ₫'
                ];
            }

            $emailService->sendAdminOrderNotification($orderData);
        } catch (\Exception $e) {
            error_log('Failed to send admin notification: ' . $e->getMessage());
        }
    }

    public function validateDiscount()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $discountCode = trim($input['discount_code'] ?? '');
        $orderTotal = floatval($input['order_total'] ?? 0);

        if (empty($discountCode)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá']);
            return;
        }

        try {
            $dichVuMaGiamGia = new DichVuMaGiamGia($this->em);
            $maGiamGia = $dichVuMaGiamGia->kiemTraMaGiamGia($discountCode);

            if (!$maGiamGia) {
                $message = $dichVuMaGiamGia->layThongBaoKiemTra($discountCode);
                echo json_encode(['success' => false, 'message' => $message]);
                return;
            }

            // Check minimum order value
            if (!$dichVuMaGiamGia->kiemTraGiaTriToiThieu($maGiamGia, $orderTotal)) {
                $minOrderMessage = $dichVuMaGiamGia->layThongBaoGiaTriToiThieu($maGiamGia);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Đơn hàng chưa đủ giá trị tối thiểu. ' . $minOrderMessage
                ]);
                return;
            }

            // Calculate discount
            $discountAmount = $maGiamGia->tinhTienGiam($orderTotal);
            
            echo json_encode([
                'success' => true,
                'discount' => [
                    'code' => $maGiamGia->getMaGiamGia(),
                    'name' => $maGiamGia->getTen(),
                    'discount_amount' => $discountAmount,
                    'value_display' => $maGiamGia->layGiaTriGiamDinhDang(),
                    'min_order' => $maGiamGia->getGiaTriDonHangToiThieu(),
                    'description' => $maGiamGia->getMoTa()
                ]
            ]);

        } catch (\Exception $e) {
            error_log('Discount validation error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi kiểm tra mã giảm giá']);
        }
    }

    private function getPaymentMethodName(string $paymentMethod): string
    {
        $names = [
            'cod' => 'Thanh toán khi nhận hàng',
            'banking' => 'Chuyển khoản ngân hàng',
            'momo' => 'Ví điện tử MoMo',
            'zalopay' => 'Ví điện tử ZaloPay'
        ];

        return $names[$paymentMethod] ?? 'Không xác định';
    }

    private function getPaymentGateway(string $paymentMethod): string
    {
        $gateways = [
            'cod' => 'cash_on_delivery',
            'banking' => 'bank_transfer',
            'momo' => 'momo_wallet',
            'zalopay' => 'zalopay_wallet'
        ];

        return $gateways[$paymentMethod] ?? 'unknown';
    }
}
