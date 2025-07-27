<?php
// app/Controllers/admin_controller.php

class admin_controller
{
    /** @var PDO */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * GET|POST ?url=admin/login
     */
    public function login()
    {
        // Nếu đã login admin, redirect vào dashboard
        if (!empty($_SESSION['user']) && $_SESSION['user']['vai_tro'] === 'admin') {
            header('Location: index.php?url=admin/dashboard');
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_dn   = trim($_POST['ten_dn']   ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';

            $stmt = $this->pdo->prepare("
                SELECT * 
                FROM nguoi_dung 
                WHERE ten_dang_nhap = :dn 
                  AND vai_tro = 'admin'
                LIMIT 1
            ");
            $stmt->execute([':dn' => $ten_dn]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                $_SESSION['user'] = $user;
                header('Location: index.php?url=admin/dashboard');
                exit;
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
            }
        }

        include __DIR__ . '/../Views/admin/login.php';
    }

    /**
     * GET ?url=admin/logout
     */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?url=admin/login');
        exit;
    }

    /**
     * GET ?url=admin/dashboard
     */
    public function dashboard()
    {
        // Bảo vệ route chỉ admin được xem
        if (empty($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
            header('Location: index.php?url=admin/login');
            exit;
        }

        // Thống kê sản phẩm & đơn hàng
        $totalSp = (int)$this->pdo->query("SELECT COUNT(*) FROM san_pham")->fetchColumn();
        $totalDh = (int)$this->pdo->query("SELECT COUNT(*) FROM don_hang")->fetchColumn();

        // Doanh thu hôm nay
        $today = date('Y-m-d');
        $stmt  = $this->pdo->prepare("
            SELECT SUM(so_tien)
            FROM thanh_toan
            WHERE DATE(ngay_thanh_toan) = :today
        ");
        $stmt->execute([':today' => $today]);
        $doanh_thu = $stmt->fetchColumn() ?: 0;

        // Thống kê trạng thái đơn hàng
        $statusCounts = $this->pdo
            ->query("SELECT trang_thai, COUNT(*) AS cnt FROM don_hang GROUP BY trang_thai")
            ->fetchAll(PDO::FETCH_ASSOC);

        // Include header, menu, view thống kê, footer
        include __DIR__ . '/../Views/layouts/admin_head.php';
        include __DIR__ . '/../Views/admin/thongkedoanhthu.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    public function upload_anh()
    {
        // bảo vệ: chỉ admin
        if (empty($_SESSION['user']) || $_SESSION['user']['vai_tro']!=='admin') {
            header('Location: index.php?url=admin/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD']==='POST' && !empty($_FILES['anh_file'])) {
            $file = $_FILES['anh_file'];

            // 1. Kiểm tra lỗi cơ bản
            if ($file['error'] !== UPLOAD_ERR_OK) {
                die("Upload lỗi: " . $file['error']);
            }

            // 2. Đặt tên file an toàn
            $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName   = uniqid('img_') . '.' . $ext;
            $uploadDir = __DIR__ . '/../../public/images/';
            $destPath  = $uploadDir . $newName;

            // 3. Di chuyển file lên thư mục public/images
            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                die("Không thể lưu file lên server.");
            }

            // 4. Lưu bản ghi vào DB
            $thuTu = (int)($_POST['thu_tu'] ?? 0);
            $stmt = $this->pdo->prepare("
            INSERT INTO anh (hinh_anh, thu_tu)
            VALUES (:img, :thu_tu)
        ");
            $stmt->execute([
                ':img'    => $newName,
                ':thu_tu' => $thuTu,
            ]);

            // 5. Redirect về danh sách ảnh hoặc dashboard
            header('Location: index.php?url=admin/dashboard');
            exit;
        }

        die("Form không hợp lệ.");
    }
}
