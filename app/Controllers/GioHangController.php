<?php

namespace App\Controllers;

use App\Models\GioHang;
use App\Models\PhienKhach;
use App\Models\BienTheSanPham;
use App\Models\NguoiDung;
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

        session_start();

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
                            $duLieu[$id]['so_luong'] = $soLuong;
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
}
