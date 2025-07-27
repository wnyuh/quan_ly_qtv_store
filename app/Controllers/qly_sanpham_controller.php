<?php

class qly_sanpham_controller
{
    protected $pdo;
    protected $model;

    public function __construct()
    {
        global $pdo;
        $this->pdo   = $pdo;
        require_once __DIR__ . '/../Models/sanpham.php';
        $this->model = new sanpham($pdo);

        // Khởi session & kiểm tra admin
        if (session_status()===PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['user']['vai_tro']!=='admin') {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    public function index()
    {
        // 1) Load model Sản phẩm
        require_once __DIR__ . '/../Models/sanpham.php';
        $spModel = new SanPham($this->pdo);

        // 2) Lấy tất cả sản phẩm
        $products = $spModel->all(); // hoặc phương thức phù hợp của bạn

        // 3) Nạp giao diện
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/admin/menu_chung.php';
        include __DIR__ . '/../Views/admin/qly_sanpham/index.php';  // view danh sách
        include __DIR__ . '/../Views/layouts/footer.php';
    }




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
//        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/admin/menu_chung.php';
        include __DIR__ . '/../Views/admin/thongkedoanhthu.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    // SHOW form thêm mới
    // GET ?url=qly_sanpham/create
// app/Controllers/qly_sanpham_controller.php
    public function create()
    {
        // load danh mục
        $stmt = $this->pdo->query("SELECT * FROM danh_muc");
        $dms  = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../Views/admin/qly_sanpham/form.php';
    }


    // PROCESS lưu mới
    // POST ?url=qly_sanpham/store
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD']!=='POST') {
            header('Location:index.php?url=qly_sanpham/create');
            exit;
        }

        // Lấy dữ liệu từ form
        $ten   = trim($_POST['ten_san_pham'] ?? '');
        $gia   = (float)$_POST['gia_ban']       ?: 0;
        $dm    = (int)$_POST['ma_danh_muc']     ?: 0;
        $img   = ''; // xử lý upload file nếu có
        if (!empty($_FILES['hinh_anh']['name'])) {
            // ví dụ upload về public/images
            $ext      = pathinfo($_FILES['hinh_anh']['name'], PATHINFO_EXTENSION);
            $newName  = uniqid('sp_').'.'.$ext;
            move_uploaded_file($_FILES['hinh_anh']['tmp_name'],
                __DIR__.'/../../public/images/'.$newName);
            $img = $newName;
        }

        // Gọi model để thêm
        $this->model->create($ten, $gia, $dm, $img);

        header('Location:index.php?url=qly_sanpham/index');
        exit;
    }
    // route: ?url=qly_sanpham/uploadBanner
    public function uploadBanner()
    {
        // thư mục lưu ảnh
        $targetDir = __DIR__ . '/../../public/images/banners/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // kiểm tra file được gửi lên
        if (empty($_FILES['hinh_anh']) || $_FILES['hinh_anh']['error'] !== UPLOAD_ERR_OK) {
            die("Lỗi khi upload file");
        }

        $file     = $_FILES['hinh_anh'];
        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowExt = ['jpg','jpeg','png','webp'];
        if (!in_array(strtolower($ext), $allowExt)) {
            die("Chỉ cho phép định dạng: " . implode(', ', $allowExt));
        }

        // sinh tên file mới tránh trùng
        $newName = uniqid('banner_') . '.' . $ext;
        $dest    = $targetDir . $newName;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            die("Không lưu được file lên server");
        }

        // lưu vào DB
        $thuTu = (int)($_POST['thu_tu'] ?? 0);
        $stmt  = $this->pdo->prepare("INSERT INTO banners (hinh_anh, thu_tu) VALUES (:img, :ord)");
        $stmt->execute([
            ':img' => $newName,
            ':ord' => $thuTu
        ]);

        // chuyển về trang quản lý
        header("Location: /phan_mem_quan_ly_ban_dien_thoai_iphone/public/index.php?url=qly_sanpham/qly_sanpham");
    }
}
