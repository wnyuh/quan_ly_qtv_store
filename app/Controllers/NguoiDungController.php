<?php

namespace App\Controllers;

use App\Models\NguoiDung;

class NguoiDungController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
    }

    public function taiKhoan()
    {
        // session_start();

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = '/tai-khoan';
            header("Location: /dang-nhap");
            exit;
        }

        $userId = $_SESSION['user_id'];
        // $user = $this->em->getRepository(NguoiDung::class)->find($userId);
        // view('tai-khoan/index', ['user' => $user]);

        $nguoi_dung = $this->em->getRepository(NguoiDung::class)->find($userId);
        view('tai-khoan/index', ['nguoi_dung' => $nguoi_dung]);
    }

    public function capNhat()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /dang-nhap");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $nguoi_dung = $this->em->getRepository(NguoiDung::class)->find($userId);

        // Lấy dữ liệu từ form
        $ho_ten = $_POST['ho_ten'] ?? '';
        $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
        $ngay_sinh = $_POST['ngay_sinh'] ?? '';

        // Cập nhật thông tin
        $nguoi_dung->setHoTen($ho_ten);
        $nguoi_dung->setSoDienThoai($so_dien_thoai);
        $nguoi_dung->setNgaySinh($ngay_sinh ? new \DateTime($ngay_sinh) : null);

        $this->em->flush();

        // Quay lại trang tài khoản kèm thông báo
        header("Location: /tai-khoan?success=1");
        exit;
    }

    public function dangXuat()
    {
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        session_destroy();
        header("Location: /dang-nhap");
        exit;
    }
}
