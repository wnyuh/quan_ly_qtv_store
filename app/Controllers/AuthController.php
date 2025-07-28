<?php

namespace App\Controllers;

use App\Models\NguoiDung;

class AuthController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function dangNhap()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $matKhau = $_POST['mat_khau'] ?? '';

            // Tìm người dùng theo email
            $user = $this->em->getRepository(NguoiDung::class)->findOneBy(['email' => $email]);

            if ($user && password_verify($matKhau, $user->getMatKhau())) {
                $_SESSION['user_id'] = $user->getId();

                // Redirect sau khi đăng nhập thành công
                $redirect = $_SESSION['redirect_after_login'] ?? '/';
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect");
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
                view('auth/login', ['error' => $error]);
                return;
            }
        } else {
            // GET request hiển thị form đăng nhập
            view('auth/login');
        }
    }

    public function dangXuat()
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
