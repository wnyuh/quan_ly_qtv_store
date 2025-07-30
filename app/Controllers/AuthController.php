<?php

namespace App\Controllers;

use App\Models\NguoiDung;
use App\Services\EmailService;

class AuthController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
    }

    // public function dangNhap()
    // {
    //     // if (session_status() === PHP_SESSION_NONE) {
    //     //     session_start();
    //     // }

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $email = $_POST['email'] ?? '';
    //         $matKhau = $_POST['mat_khau'] ?? '';

    //         $user = $this->em->getRepository(NguoiDung::class)->findOneBy(['email' => $email]);

    //         if (!$user) {
    //             error_log("DangNhap: Khong tim thay user voi email: $email");
    //         } else {
    //             error_log("DangNhap: Tim thay user voi email: " . $user->getEmail());
    //             error_log("DangNhap: Mat khau hash trong DB: " . $user->getMatKhau());
    //             error_log("DangNhap: Mat khau nguoi dung nhap: $matKhau");
    //             error_log("DangNhap: password_verify ket qua: " . (password_verify($matKhau, $user->getMatKhau()) ? 'true' : 'false'));
    //         }


    //         // if ($user && ($matKhau === $user->getMatKhau() || password_verify($matKhau, $user->getMatKhau())))
    //         if ($user && password_verify($matKhau, $user->getMatKhau()))  {
    //             $_SESSION['user_id'] = $user->getId();

    //             $redirect = $_SESSION['redirect_after_login'] ?? '/';
    //             unset($_SESSION['redirect_after_login']);
    //             header("Location: $redirect");
    //             exit;
    //         } else {
    //             $error = 'Email hoặc mật khẩu không đúng.';
    //             view('auth/login', ['error' => $error]);
    //             return;
    //         }
    //     } else {
    //         view('auth/login');
    //     }
    // }

    public function dangNhap()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $matKhau = $_POST['mat_khau'] ?? '';

            $user = $this->em->getRepository(NguoiDung::class)->findOneBy(['email' => $email]);

            if (!$user) {
                $error = 'Email hoặc mật khẩu không đúng.';
                view('auth/login', ['error' => $error]);
                return;
            }

            // Kiểm tra email đã được xác nhận chưa
            if (!$user->isEmailVerified()) {
                $error = 'Vui lòng xác nhận email trước khi đăng nhập. Kiểm tra hộp thư của bạn.';
                view('auth/login', ['error' => $error]);
                return;
            }

            $matKhauDB = $user->getMatKhau();

            // Kiểm tra nếu mật khẩu lưu trong DB chưa mã hóa
            if ($matKhau === $matKhauDB) {
                // => tự động mã hóa lại mật khẩu và cập nhật vào DB
                $matKhauHash = password_hash($matKhau, PASSWORD_DEFAULT);
                $user->setMatKhau($matKhauHash);
                $this->em->persist($user);
                $this->em->flush();
            } elseif (!password_verify($matKhau, $matKhauDB)) {
                // Mật khẩu nhập sai
                $error = 'Email hoặc mật khẩu không đúng.';
                view('auth/login', ['error' => $error]);
                return;
            }

            // Lưu session nếu đúng mật khẩu
            $_SESSION['user_id'] = $user->getId();

            $redirect = $_SESSION['redirect_after_login'] ?? '/';
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit;
        } else {
            view('auth/login');
        }
    }

    public function dangXuat()
    {
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        // session_destroy();
        // header('Location: /');
        // exit;

        $_SESSION = [];
        session_destroy();
        session_regenerate_id(true);

        header("Location: /dang-nhap");
        exit;
    }

    public function dangKy()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $matKhau = $_POST['mat_khau'] ?? '';
            $xacNhanMatKhau = $_POST['xac_nhan_mat_khau'] ?? '';
            $hoTen = trim($_POST['ho_ten'] ?? '');

            // Kiểm tra dữ liệu đầu vào
            if (!$email || !$matKhau || !$xacNhanMatKhau || !$hoTen) {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email không hợp lệ.';
            } elseif ($matKhau !== $xacNhanMatKhau) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } else {
                // Kiểm tra email đã tồn tại chưa
                $userRepo = $this->em->getRepository(NguoiDung::class);
                $existingUser = $userRepo->findOneBy(['email' => $email]);
                if ($existingUser) {
                    $error = 'Email đã được đăng ký.';
                } else {
                    // Tạo user mới
                    $user = new NguoiDung();
                    $user->setEmail($email);

                    // Tách họ tên
                    $parts = explode(' ', $hoTen);
                    $ho = array_shift($parts);
                    $ten = implode(' ', $parts);

                    $user->setHo($ho);
                    $user->setTen($ten);
                    $user->setMatKhau($matKhau);

                    // Tạo mã xác thực
                    $confirmationCode = $user->generateConfirmationCode();

                    $this->em->persist($user);
                    $this->em->flush();

                    // Gửi email xác nhận
                    try {
                        $emailConfig = require __DIR__ . '/../../config/email.php';
                        $emailService = new EmailService($emailConfig);
                        $emailSent = $emailService->sendEmailConfirmation($email, $hoTen, $confirmationCode);
                        
                        if ($emailSent) {
                            $success = 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.';
                        } else {
                            $success = 'Đăng ký thành công! Tuy nhiên, không thể gửi email xác nhận. Vui lòng liên hệ admin.';
                        }
                    } catch (Exception $e) {
                        error_log('Email sending failed: ' . $e->getMessage());
                        $success = 'Đăng ký thành công! Tuy nhiên, không thể gửi email xác nhận. Vui lòng liên hệ admin.';
                    }
                }
            }
        }

        view('auth/register', ['error' => $error, 'success' => $success]);
    }


    public function kichHoatTaiKhoan()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $code = $_GET['code'] ?? null;
        $error = '';
        $success = '';

        if (!$code) {
            $error = 'Mã xác nhận không hợp lệ.';
        } else {
            $user = $this->em->getRepository(NguoiDung::class)->findOneBy(['maXacThuc' => $code]);
            if (!$user) {
                $error = 'Mã xác nhận không tồn tại hoặc đã được sử dụng.';
            } elseif (!$user->isConfirmationCodeValid()) {
                $error = 'Mã xác nhận đã hết hạn. Vui lòng đăng ký lại.';
            } else {
                // Xác nhận email
                $user->confirmEmail();
                $this->em->flush();
                $success = 'Xác nhận tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.';
            }
        }

        view('auth/confirmation', [
            'error' => $error,
            'success' => $success
        ]);
    }
}
