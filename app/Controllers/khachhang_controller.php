<?php
// app/Controllers/khachhang_controller.php

require_once __DIR__ . '/../Models/khachhang.php';

class khachhang_controller
{
    /** @var KhachHang */
    protected $m;

    public function __construct(PDO $pdo)
    {
        session_start();
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

        include __DIR__ . '/../Views/khachhang/login.php';
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
        $this->auth();
        $dskh = $this->m->all();
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/khachhang/index.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * GET /?url=khachhang/create
     */
    public function create()
    {
        $this->auth();
        $kh = null; // để View biết là create (không có dữ liệu cũ)
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/khachhang/form.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * POST /?url=khachhang/store
     */
    public function store()
    {
        $this->auth();
        $data = [
            'ten_khach_hang' => $_POST['ten_khach_hang'] ?? '',
            'email'          => $_POST['email']          ?? '',
            'dien_thoai'     => $_POST['dien_thoai']     ?? '',
            'dia_chi'        => $_POST['dia_chi']        ?? '',
        ];
        $this->m->create($data);
        header('Location: index.php?url=khachhang/index');
        exit;
    }

    /**
     * GET /?url=khachhang/edit&id=123
     */
    public function edit()
    {
        $this->auth();
        $id = (int)($_GET['id'] ?? 0);
        $kh = $this->m->find($id);
        if (!$kh) {
            http_response_code(404);
            die("Khách hàng không tồn tại");
        }
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/khachhang/form.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * POST /?url=khachhang/update
     */
    public function update()
    {
        $this->auth();
        $id   = (int)($_POST['id'] ?? 0);
        $data = [
            'ten_khach_hang' => $_POST['ten_khach_hang'] ?? '',
            'email'          => $_POST['email']          ?? '',
            'dien_thoai'     => $_POST['dien_thoai']     ?? '',
            'dia_chi'        => $_POST['dia_chi']        ?? '',
        ];
        $this->m->update($id, $data);
        header('Location: index.php?url=khachhang/index');
        exit;
    }

    /**
     * GET /?url=khachhang/delete&id=123
     */
    public function delete()
    {
        $this->auth();
        $id = (int)($_GET['id'] ?? 0);
        $this->m->delete($id);
        header('Location: index.php?url=khachhang/index');
        exit;
    }
}
