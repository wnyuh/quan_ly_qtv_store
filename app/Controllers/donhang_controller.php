<?php
// app/Controllers/donhang_controller.php

class donhang_controller
{
    protected $dhModel;
    protected $ctModel;

    public function __construct(PDO $pdo)
    {
        require_once __DIR__ . '/../Models/donhang.php';
        require_once __DIR__ . '/../Models/chitietdonhang.php';
        $this->dhModel = new DonHang($pdo);
        $this->ctModel = new chitietdonhang($pdo);

        // Bật session nếu chưa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Chỉ cho user đã login
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    /**
     * GET /?url=don-hang/index
     * Hiển thị danh sách đơn hàng của user
     */
    public function index()
    {
        $maNd   = $_SESSION['user']['ma_nguoi_dung'];
        $donHs  = $this->dhModel->allByUser($maNd);

        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/donhang/index.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * GET /?url=don-hang/detail&id=xxx
     * Hiển thị chi tiết đơn hàng
     */
    public function detail()
    {
        $id    = (int)($_GET['id'] ?? 0);
        $dh    = $this->dhModel->find($id);
        if (!$dh || $dh['ma_nguoi_dung'] != $_SESSION['user']['ma_nguoi_dung']) {
            http_response_code(404);
            exit('Không tìm thấy đơn hàng.');
        }
        $items = $this->ctModel->allByOrder($id);

        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/donhang/detail.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }
}
