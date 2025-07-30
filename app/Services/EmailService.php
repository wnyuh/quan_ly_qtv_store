<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private PHPMailer $mailer;
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => PHPMailer::ENCRYPTION_STARTTLS,
            'username' => '',
            'password' => '',
            'from_email' => '',
            'from_name' => 'Cửa hàng Store',
            'charset' => 'UTF-8'
        ], $config);

        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer(): void
    {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['username'];
            $this->mailer->Password = $this->config['password'];
            $this->mailer->Port = $this->config['port'];
            $this->mailer->CharSet = $this->config['charset'];
            
            // Set encryption only if specified
            if (!empty($this->config['encryption'])) {
                $this->mailer->SMTPSecure = $this->config['encryption'];
            }

            // Default sender
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
        } catch (Exception $e) {
            throw new \Exception("Email configuration error: " . $e->getMessage());
        }
    }

    public function sendWelcomeEmail(string $toEmail, string $toName): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Chào mừng bạn đến với Cửa hàng Store!';
            
            $this->mailer->Body = $this->getWelcomeEmailTemplate($toName);
            $this->mailer->AltBody = "Chào mừng {$toName} đến với Cửa hàng Store! Cảm ơn bạn đã đăng ký tài khoản.";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendOrderConfirmation(string $toEmail, string $toName, array $orderData): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Xác nhận đơn hàng #{$orderData['ma_don_hang']}";
            
            $this->mailer->Body = $this->getOrderConfirmationTemplate($toName, $orderData);
            $this->mailer->AltBody = "Đơn hàng #{$orderData['ma_don_hang']} của bạn đã được xác nhận. Tổng tiền: {$orderData['tong_tien_formatted']}.";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Order confirmation email failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendPasswordReset(string $toEmail, string $resetToken): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Đặt lại mật khẩu - Cửa hàng Store';
            
            $resetLink = "http://localhost:8000/reset-password?token=" . $resetToken;
            $this->mailer->Body = $this->getPasswordResetTemplate($resetLink);
            $this->mailer->AltBody = "Nhấp vào liên kết sau để đặt lại mật khẩu: {$resetLink}";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Password reset email failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendContactForm(string $fromEmail, string $fromName, string $subject, string $message): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($this->config['from_email']); // Send to store admin
            $this->mailer->addReplyTo($fromEmail, $fromName);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Liên hệ từ website: {$subject}";
            
            $this->mailer->Body = $this->getContactFormTemplate($fromName, $fromEmail, $subject, $message);
            $this->mailer->AltBody = "Tên: {$fromName}\nEmail: {$fromEmail}\nChủ đề: {$subject}\nTin nhắn: {$message}";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Contact form email failed: " . $e->getMessage());
            return false;
        }
    }

    public function sendEmailConfirmation(string $toEmail, string $toName, string $confirmationCode): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Xác nhận đăng ký tài khoản - Cửa hàng Store';
            
            $confirmationLink = "http://localhost:8000/xac-nhan?code=" . $confirmationCode;
            $this->mailer->Body = $this->getEmailConfirmationTemplate($toName, $confirmationLink);
            $this->mailer->AltBody = "Chào {$toName}, vui lòng nhấp vào liên kết sau để xác nhận tài khoản: {$confirmationLink}";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Email confirmation failed: " . $e->getMessage());
            return false;
        }
    }

    private function getWelcomeEmailTemplate(string $name): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .btn { display: inline-block; padding: 10px 20px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Chào mừng đến với Cửa hàng Store!</h1>
                </div>
                <div class='content'>
                    <h2>Xin chào {$name},</h2>
                    <p>Cảm ơn bạn đã đăng ký tài khoản tại Cửa hàng Store. Chúng tôi rất vui mừng chào đón bạn!</p>
                    <p>Tại đây, bạn có thể:</p>
                    <ul>
                        <li>Khám phá các sản phẩm iPhone mới nhất</li>
                        <li>Theo dõi đơn hàng của bạn</li>
                        <li>Nhận thông báo về khuyến mãi đặc biệt</li>
                    </ul>
                    <p style='text-align: center;'>
                        <a href='http://localhost:8000' class='btn'>Bắt đầu mua sắm</a>
                    </p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Cửa hàng Store. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    private function getOrderConfirmationTemplate(string $name, array $orderData): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #10b981; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .order-info { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Xác nhận đơn hàng</h1>
                </div>
                <div class='content'>
                    <h2>Xin chào {$name},</h2>
                    <p>Đơn hàng của bạn đã được xác nhận thành công!</p>
                    
                    <div class='order-info'>
                        <h3>Thông tin đơn hàng</h3>
                        <p><strong>Mã đơn hàng:</strong> {$orderData['ma_don_hang']}</p>
                        <p><strong>Ngày đặt:</strong> {$orderData['ngay_tao']}</p>
                        <p><strong>Tổng tiền:</strong> {$orderData['tong_tien_formatted']}</p>
                        <p><strong>Trạng thái:</strong> {$orderData['trang_thai']}</p>
                    </div>
                    
                    <p>Chúng tôi sẽ xử lý đơn hàng và giao hàng trong thời gian sớm nhất. Bạn sẽ nhận được email cập nhật khi đơn hàng được giao.</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Cửa hàng Store. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    private function getPasswordResetTemplate(string $resetLink): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #ef4444; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .btn { display: inline-block; padding: 10px 20px; background: #ef4444; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Đặt lại mật khẩu</h1>
                </div>
                <div class='content'>
                    <h2>Yêu cầu đặt lại mật khẩu</h2>
                    <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình. Nhấp vào nút bên dưới để tạo mật khẩu mới:</p>
                    
                    <p style='text-align: center;'>
                        <a href='{$resetLink}' class='btn'>Đặt lại mật khẩu</a>
                    </p>
                    
                    <p><small>Liên kết này sẽ hết hạn sau 1 giờ. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</small></p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Cửa hàng Store. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    private function getContactFormTemplate(string $fromName, string $fromEmail, string $subject, string $message): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6366f1; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Liên hệ từ Website</h1>
                </div>
                <div class='content'>
                    <div class='info-box'>
                        <h3>Thông tin người gửi</h3>
                        <p><strong>Tên:</strong> {$fromName}</p>
                        <p><strong>Email:</strong> {$fromEmail}</p>
                        <p><strong>Chủ đề:</strong> {$subject}</p>
                    </div>
                    
                    <div class='info-box'>
                        <h3>Nội dung tin nhắn</h3>
                        <p>" . nl2br(htmlspecialchars($message)) . "</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }

    private function getEmailConfirmationTemplate(string $name, string $confirmationLink): string
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #10b981; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .btn { display: inline-block; padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Xác nhận đăng ký tài khoản</h1>
                </div>
                <div class='content'>
                    <h2>Xin chào {$name},</h2>
                    <p>Cảm ơn bạn đã đăng ký tài khoản tại Cửa hàng Store!</p>
                    <p>Để hoàn tất quá trình đăng ký, vui lòng nhấp vào nút bên dưới để xác nhận địa chỉ email của bạn:</p>
                    
                    <p style='text-align: center;'>
                        <a href='{$confirmationLink}' class='btn'>Xác nhận tài khoản</a>
                    </p>
                    
                    <p><small>Liên kết này sẽ hết hạn sau 24 giờ. Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email này.</small></p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Cửa hàng Store. Tất cả quyền được bảo lưu.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}