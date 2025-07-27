<?php
// app/Controllers/sanpham_controller.php

// Khởi session (nếu chưa khởi)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class sanpham_controller
{
    /** @var PDO */
    protected $pdo;

    /** @var SanPham */
    protected $spModel;

    /** @var DanhMuc */
    protected $dmModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        // load các model
        require_once __DIR__ . '/../Models/sanpham.php';
        require_once __DIR__ . '/../Models/danhmuc.php';

        $this->spModel = new sanpham($this->pdo);
        $this->dmModel = new danhmuc($this->pdo);
    }

    /**
     * GET ?route=sanpham/index
     */
    public function index()
    {
        // 1) Lấy ảnh slider từ bảng `anh`
        //    giả sử bạn đã tạo bảng `anh (id, hinh_anh, thu_tu)`
        $stmt    = $this->pdo->query("SELECT hinh_anh FROM anh ORDER BY thu_tu ASC");
        $banners = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // 2) Lấy danh sách danh mục
        $dms = $this->dmModel->all(); // cần có method all() trong Model danhMuc

        // 3) Lấy danh sách sản phẩm (có thể lọc theo danh mục)
        $ma_dm = isset($_GET['dm']) ? (int)$_GET['dm'] : null;
        $sps   = $this->spModel->all($ma_dm);

        // 4) Render view
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/sanpham/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * GET ?route=sanpham/detail&id=123
     */
    public function detail()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $sp = $this->spModel->find($id);
        if (!$sp) {
            http_response_code(404);
            echo "Không tìm thấy sản phẩm #{$id}";
            exit;
        }

        // Lấy sản phẩm liên quan (cùng danh mục, loại trừ chính nó)
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM san_pham 
            WHERE ma_danh_muc = :mdm 
              AND ma_san_pham <> :msph 
            ORDER BY ngay_tao DESC 
            LIMIT 4
        ");
        $stmt->execute([
            ':mdm' => $sp['ma_danh_muc'],
            ':msph'=> $sp['ma_san_pham']
        ]);
        $related = $stmt->fetchAll();

        // Render view
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/sanpham/detail.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }
}
