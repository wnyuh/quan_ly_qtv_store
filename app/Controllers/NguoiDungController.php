<?php

namespace App\Controllers;

use App\Models\NguoiDung;
use App\Models\DonHang;


class NguoiDungController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
    }

    public function taiKhoan()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = '/tai-khoan';
            header("Location: /dang-nhap");
            exit;
        }

        $userId = $_SESSION['user_id'];

        $nguoi_dung = $this->em->getRepository(NguoiDung::class)->find($userId);

        // Lấy danh sách đơn hàng
        $don_hangs = $this->em->getRepository(DonHang::class)->findBy(
            ['nguoiDung' => $nguoi_dung],
            ['ngayTao' => 'DESC']
        );

        view('tai-khoan/index', [
            'nguoi_dung' => $nguoi_dung,
            'don_hangs' => $don_hangs,
        ]);
    }

    public function capNhat()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /dang-nhap");
            exit;
        }

        $nguoi_dung = $this->em->getRepository(NguoiDung::class)->find($_SESSION['user_id']);

        $ho_ten = $_POST['ho_ten'] ?? '';
        $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';

        $parts = explode(' ', trim($ho_ten));
        $ho = array_shift($parts);
        $ten = implode(' ', $parts);

        $nguoi_dung->setHo($ho)
            ->setTen($ten)
            ->setSoDienThoai($so_dien_thoai)
            ->setNgayCapNhat();

        $this->em->flush();

        header("Location: /tai-khoan?success=1");
        exit;
    }

    public function doiMatKhau()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /dang-nhap");
            exit;
        }

        $nguoi_dung = $this->em->getRepository(NguoiDung::class)->find($_SESSION['user_id']);

        $mat_khau_cu = $_POST['mat_khau_cu'] ?? '';
        $mat_khau_moi = $_POST['mat_khau_moi'] ?? '';
        $mat_khau_moi_confirm = $_POST['mat_khau_moi_confirm'] ?? '';

        if (!$nguoi_dung->kiemTraMatKhau($mat_khau_cu)) {
            header("Location: /tai-khoan?error=matkhaucusai");
            exit;
        }

        if ($mat_khau_moi !== $mat_khau_moi_confirm) {
            header("Location: /tai-khoan?error=matkhaumoi_khongkhop");
            exit;
        }

        $nguoi_dung->setMatKhau($mat_khau_moi);
        $nguoi_dung->setNgayCapNhat();

        $this->em->flush();

        header("Location: /tai-khoan?success=doimatkhau");
        exit;
    }

    public function dangXuat()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
        session_regenerate_id(true);

        header("Location: /");
        exit;
    }
}
