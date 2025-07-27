<?php
// app/Controllers/mail_controller.php

class mail_controller
{
    /**
     * Hiển thị form gửi mail
     * GET /?url=mail/form
     */
    public function form()
    {
        // nếu cần bảo vệ route, kiểm tra session ở đây
        // ví dụ: nếu chưa đăng nhập admin thì redirect
        // if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro']!=='admin') {
        //     header('Location: index.php?url=admin/login');
        //     exit;
        // }

        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/mail/form.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Xử lý gửi mail
     * POST /?url=mail/send
     */
    public function send()
    {
        // lấy dữ liệu từ POST
        $to      = trim($_POST['to']      ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $body    = trim($_POST['body']    ?? '');

        $errors = [];

        if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Địa chỉ email không hợp lệ.';
        }
        if (!$subject) {
            $errors[] = 'Chủ đề không được để trống.';
        }
        if (!$body) {
            $errors[] = 'Nội dung mail không được để trống.';
        }

        $success = false;
        if (empty($errors)) {
            // thiết lập header cho mail
            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "From: no-reply@yourdomain.com\r\n";

            // gọi hàm mail()
            $success = mail($to, $subject, $body, $headers);
            if (!$success) {
                $errors[] = 'Gửi mail thất bại. Hãy thử lại sau.';
            }
        }

        // truyền kết quả qua view
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/mail/result.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }
}
