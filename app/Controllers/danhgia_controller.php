<?php
// app/Controllers/danhgia_controller.php

class danhgia_controller
{
    protected $danhGiaModel;
    protected $sanPhamModel;

    public function __construct(PDO $pdo)
    {
        // khởi model DanhGia và SanPham để liên quan
        require_once __DIR__ . '/../Models/danhgia.php';
        require_once __DIR__ . '/../Models/sanpham.php';
        $this->danhGiaModel  = new DanhGia($pdo);
        $this->sanPhamModel  = new SanPham($pdo);

        // chỉ dùng session, không cookie
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Admin: hiển thị danh sách đánh giá
     * URL: index.php?url=danh-gia/index
     */
    public function index()
    {
        $this->checkAdmin();

        // lấy toàn bộ đánh giá, có join sản phẩm + user nếu cần
        $dgs = $this->danhGiaModel->allWithInfo();

        include __DIR__ . '/../Views/admin/QuanLyDanhGia.php';
    }

    /**
     * Admin: xóa đánh giá
     * URL: index.php?url=danh-gia/delete&id=123
     */
    public function delete()
    {
        $this->checkAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->danhGiaModel->delete($id);
        }
        header('Location: index.php?url=danh-gia/index');
        exit;
    }

    /**
     * User: thêm đánh giá từ trang chi tiết sản phẩm
     * POST tới index.php?url=danh-gia/store
     */
    public function store()
    {
        // phải đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=admin/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_sp     = (int) ($_POST['ma_san_pham'] ?? 0);
            $ma_nd     = $_SESSION['user']['ma_nguoi_dung'];
            $so_sao    = min(5, max(1, (int)($_POST['so_sao'] ?? 5)));
            $binh_luan = trim($_POST['binh_luan'] ?? '');

            // Lưu vào CSDL
            $this->danhGiaModel->create($ma_sp, $ma_nd, $so_sao, $binh_luan);
        }

        // quay lại trang chi tiết
        header('Location: index.php?url=san-pham/detail&id='.$ma_sp);
        exit;
    }

    /**
     * Kiểm tra quyền admin, nếu không chuyển về login
     */
    protected function checkAdmin()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }
}
