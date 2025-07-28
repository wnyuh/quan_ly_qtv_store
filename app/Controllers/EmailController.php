<?php

namespace App\Controllers;

use App\Services\EmailService;
use App\Models\NguoiDung;
use App\Models\DonHang;

class EmailController
{
    private EmailService $emailService;

    public function __construct()
    {
        $emailConfig = require __DIR__ . '/../../config/email.php';
        $this->emailService = new EmailService($emailConfig);
    }

    /**
     * Gửi email chào mừng khi người dùng đăng ký
     */
    public function sendWelcomeEmail(NguoiDung $nguoiDung): bool
    {
        return $this->emailService->sendWelcomeEmail(
            $nguoiDung->getEmail(),
            $nguoiDung->getHoTen()
        );
    }

    /**
     * Gửi email xác nhận đơn hàng
     */
    public function sendOrderConfirmation(DonHang $donHang): bool
    {
        $orderData = [
            'ma_don_hang' => $donHang->getMaDonHang(),
            'ngay_tao' => $donHang->getNgayTao()->format('d/m/Y H:i'),
            'tong_tien_formatted' => $donHang->getTongTienFormatted(),
            'trang_thai' => $this->getVietnameseOrderStatus($donHang->getTrangThai())
        ];

        $email = $donHang->getNguoiDung() 
            ? $donHang->getNguoiDung()->getEmail() 
            : $donHang->getEmailKhach();
            
        $name = $donHang->getNguoiDung() 
            ? $donHang->getNguoiDung()->getHoTen() 
            : 'Khách hàng';

        return $this->emailService->sendOrderConfirmation($email, $name, $orderData);
    }

    /**
     * Gửi email đặt lại mật khẩu
     */
    public function sendPasswordReset(string $email, string $resetToken): bool
    {
        return $this->emailService->sendPasswordReset($email, $resetToken);
    }

    /**
     * Gửi email từ form liên hệ
     */
    public function sendContactForm(array $formData): bool
    {
        return $this->emailService->sendContactForm(
            $formData['email'],
            $formData['name'],
            $formData['subject'],
            $formData['message']
        );
    }

    /**
     * Test gửi email đơn giản
     */
    public function testEmail(): void
    {
        try {
            $result = $this->emailService->sendWelcomeEmail(
                'test@example.com',
                'Người dùng thử nghiệm'
            );

            if ($result) {
                echo "Email đã được gửi thành công!";
            } else {
                echo "Gửi email thất bại!";
            }
        } catch (\Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    private function getVietnameseOrderStatus(string $status): string
    {
        $statusMap = [
            'cho_xu_ly' => 'Chờ xử lý',
            'xac_nhan' => 'Đã xác nhận',
            'dang_xu_ly' => 'Đang xử lý',
            'da_giao' => 'Đã giao hàng',
            'hoan_thanh' => 'Hoàn thành',
            'huy' => 'Đã hủy',
            'hoan_tien' => 'Hoàn tiền'
        ];

        return $statusMap[$status] ?? $status;
    }
}
