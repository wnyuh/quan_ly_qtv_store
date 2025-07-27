<?php
// app/Controllers/khachhang_controller.php

require_once __DIR__ . '/../Models/khachhang.php';

class qly_khachhang_controller
{
    /** @var KhachHang */
    protected $m;

    public function __construct(PDO $pdo)
    {
//        session_start();
        $this->m = new KhachHang($pdo);
    }

    /**
     * GET|POST ?url=khachhang/login
     */
    public function login()
    {
        // Nếu đã login rồi, chuyển về index
        if (!empty($_SESSION['user']) && $_SESSION['user']['vai_tro'] === 'customer') {
            header('Location: index.php?url=khachhang/index');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dn   = trim($_POST['ten_dn'] ?? '');
            $mk   = $_POST['mat_khau'] ?? '';

            // Tìm user role = customer
            $stmt = $this->m->pdo->prepare("
                SELECT * FROM nguoi_dung
                WHERE ten_dang_nhap = :dn
                  AND vai_tro = 'customer'
                LIMIT 1
            ");
            $stmt->execute([':dn' => $dn]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($mk, $user['mat_khau'])) {
                $_SESSION['user'] = $user;
                header('Location: index.php?url=khachhang/index');
                exit;
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
            }
        }

//        include __DIR__ . '/../Views/khachhang/login.php';
    }

    /**
     * GET ?url=khachhang/logout
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: index.php?url=khachhang/login');
        exit;
    }

    /**
     * Bảo vệ: chỉ cho khách hàng đã đăng nhập mới xem được
     */
    protected function auth()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'customer') {
            header('Location: index.php?url=khachhang/login');
            exit;
        }
    }

    /**
     * GET /?url=khachhang/index
     */
    public function index()
    {
        //        $this->auth();
        $dskh = $this->m->all();
        include __DIR__ . '/../Views/layouts/admin_head.php';
        include __DIR__ . '/../Views/admin/qly_khachhang.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }
}
