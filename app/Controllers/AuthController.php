<?php

namespace App\Controllers;

use App\Models\NguoiDung;
use App\Services\Mailer; // Giả sử bạn có service gửi mail

class AuthController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
    }

    public function dangNhap()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $matKhau = $_POST['mat_khau'] ?? '';

            $user = $this->em->getRepository(NguoiDung::class)->findOneBy(['email' => $email]);

            if (!$user) {
                error_log("DangNhap: Khong tim thay user voi email: $email");
            } else {
                error_log("DangNhap: Tim thay user voi email: " . $user->getEmail());
                error_log("DangNhap: Mat khau hash trong DB: " . $user->getMatKhau());
                error_log("DangNhap: Mat khau nguoi dung nhap: $matKhau");
                error_log("DangNhap: password_verify ket qua: " . (password_verify($matKhau, $user->getMatKhau()) ? 'true' : 'false'));
            }

            if ($user && password_verify($matKhau, $user->getMatKhau())) {
                $_SESSION['user_id'] = $user->getId();

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
            view('auth/login');
        }
    }



    public function dangXuat()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /');
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

                    // **Quan trọng: chỉ truyền mật khẩu thô**
                    $user->setMatKhau($matKhau);  // Hash sẽ được thực hiện trong model

                    $this->em->persist($user);
                    $this->em->flush();

                    //Bỏ gửi mail xác nhận tạm thời
                    // $mailer = new Mailer();
                    // $linkKichHoat = 'http://' . $_SERVER['HTTP_HOST'] . "/xac-nhan?code=$maKichHoat";
                    // $body = "Chào $hoTen, vui lòng nhấn vào link để kích hoạt tài khoản: <a href='$linkKichHoat'>$linkKichHoat</a>";
                    // $mailer->send($email, 'Xác nhận đăng ký tài khoản', $body);

                    // $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
                    // Đăng ký thành công thì redirect sang trang đăng nhập luôn
                    header('Location: /dang-nhap');
                    exit;
                    
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
        if (!$code) {
            echo "Mã kích hoạt không hợp lệ.";
            exit;
        }

        $user = $this->em->getRepository(NguoiDung::class)->findOneBy(['maKichHoat' => $code]);
        if (!$user) {
            echo "Mã kích hoạt không tồn tại hoặc đã hết hạn.";
            exit;
        }

        $user->setKichHoat(true);
        $user->setMaKichHoat(null); // Xóa mã kích hoạt sau khi dùng
        $this->em->flush();

        echo "Kích hoạt tài khoản thành công. Bạn có thể đăng nhập ngay bây giờ.";
        echo "<br><a href='/dang-nhap'>Đăng nhập</a>";
    }
}
