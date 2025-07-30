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

    public function sendAdminOrderNotification(array $orderData): bool
    {
        try {
            $adminEmail = 'admin@store.com'; // Hardcoded admin email
            
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($adminEmail, 'Admin Store');

            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Đơn hàng mới #{$orderData['ma_don_hang']} - Cần xử lý";
            
            $this->mailer->Body = $this->getAdminOrderNotificationTemplate($orderData);
            $this->mailer->AltBody = "Đơn hàng mới #{$orderData['ma_don_hang']} với tổng tiền {$orderData['tong_tien_formatted']} cần được xử lý.";

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Admin order notification failed: " . $e->getMessage());
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

    private function getAdminOrderNotificationTemplate(array $orderData): string
    {
        $itemsList = '';
        if (!empty($orderData['items'])) {
            foreach ($orderData['items'] as $item) {
                $itemsList .= "<tr>
                    <td style='padding: 8px; border-bottom: 1px solid #eee;'>{$item['ten_san_pham']}</td>
                    <td style='padding: 8px; border-bottom: 1px solid #eee; text-align: center;'>{$item['so_luong']}</td>
                    <td style='padding: 8px; border-bottom: 1px solid #eee; text-align: right;'>{$item['gia_don_vi_formatted']}</td>
                    <td style='padding: 8px; border-bottom: 1px solid #eee; text-align: right;'>{$item['tong_gia_formatted']}</td>
                </tr>";
            }
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 700px; margin: 0 auto; padding: 20px; }
                .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .order-info { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
                .items-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                .items-table th { background: #f3f4f6; padding: 10px; text-align: left; font-weight: bold; }
                .items-table td { padding: 8px; border-bottom: 1px solid #eee; }
                .address-box { background: #fef3c7; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #f59e0b; }
                .total-box { background: #dcfce7; padding: 15px; margin: 10px 0; border-radius: 5px; font-size: 16px; font-weight: bold; }
                .btn { display: inline-block; padding: 12px 24px; background: #dc2626; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🚨 ĐƠN HÀNG MỚI CẦN XỬ LÝ</h1>
                </div>
                <div class='content'>
                    <div class='order-info'>
                        <h3>Thông tin đơn hàng</h3>
                        <table style='width: 100%;'>
                            <tr>
                                <td style='padding: 5px 0; font-weight: bold;'>Mã đơn hàng:</td>
                                <td style='padding: 5px 0;'>{$orderData['ma_don_hang']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0; font-weight: bold;'>Ngày đặt:</td>
                                <td style='padding: 5px 0;'>{$orderData['ngay_tao']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0; font-weight: bold;'>Khách hàng:</td>
                                <td style='padding: 5px 0;'>{$orderData['ten_khach_hang']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0; font-weight: bold;'>Email:</td>
                                <td style='padding: 5px 0;'>{$orderData['email_khach_hang']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0; font-weight: bold;'>Số điện thoại:</td>
                                <td style='padding: 5px 0;'>{$orderData['so_dien_thoai']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 5px 0; font-weight: bold;'>Trạng thái:</td>
                                <td style='padding: 5px 0;'><span style='background: #fbbf24; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px;'>{$orderData['trang_thai']}</span></td>
                            </tr>
                        </table>
                    </div>

                    <div class='address-box'>
                        <h3>📍 Địa chỉ giao hàng</h3>
                        <p><strong>{$orderData['dia_chi']['ho_ten']}</strong></p>
                        <p>{$orderData['dia_chi']['dia_chi_day_du']}</p>
                        <p>📞 {$orderData['dia_chi']['so_dien_thoai']}</p>
                    </div>

                    <div class='order-info'>
                        <h3>Sản phẩm đã đặt</h3>
                        <table class='items-table'>
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th style='text-align: center;'>Số lượng</th>
                                    <th style='text-align: right;'>Đơn giá</th>
                                    <th style='text-align: right;'>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$itemsList}
                            </tbody>
                        </table>
                    </div>

                    <div class='total-box'>
                        <div style='display: flex; justify-content: space-between; margin: 5px 0;'>
                            <span>Tạm tính:</span>
                            <span>{$orderData['tong_phu_formatted']}</span>
                        </div>
                        <div style='display: flex; justify-content: space-between; margin: 5px 0;'>
                            <span>Phí vận chuyển:</span>
                            <span>{$orderData['phi_van_chuyen_formatted']}</span>
                        </div>
                        <hr style='margin: 10px 0;'>
                        <div style='display: flex; justify-content: space-between; font-size: 18px; color: #dc2626;'>
                            <span>TỔNG CỘNG:</span>
                            <span>{$orderData['tong_tien_formatted']}</span>
                        </div>
                    </div>

                    <div style='text-align: center; margin: 20px 0;'>
                        <a href='http://localhost:8000/admin/don-hang/chi-tiet/{$orderData['id']}' class='btn'>Xem chi tiết đơn hàng</a>
                        <a href='http://localhost:8000/admin/don-hang' class='btn'>Quản lý đơn hàng</a>
                    </div>

                    <div style='background: #fee2e2; padding: 15px; border-radius: 5px; border-left: 4px solid #dc2626;'>
                        <p><strong>⏰ Lưu ý:</strong> Đơn hàng này cần được xử lý trong vòng 24 giờ để đảm bảo chất lượng dịch vụ khách hàng.</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Cửa hàng Store - Hệ thống quản lý đơn hàng</p>
                    <p>Email này được gửi tự động, vui lòng không trả lời.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}