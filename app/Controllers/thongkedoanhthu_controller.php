<?php
// app/Controllers/thongkedoanhthu_controller.php

class thongkedoanhthu_controller
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Chỉ admin mới được xem
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    /**
     * GET /?url=thong-ke-doanh-thu/index
     * Hiển thị thống kê doanh thu và trạng thái đơn hàng
     */
    public function index()
    {
        // 1. Doanh thu theo tháng (6 tháng gần nhất)
        $sql =
            "SELECT DATE_FORMAT(ngay_thanh_toan, '%Y-%m') AS thang,"
            . " SUM(so_tien) AS doanh_thu"
            . " FROM thanh_toan"
            . " GROUP BY thang"
            . " ORDER BY thang DESC"
            . " LIMIT 6";
        $stmt = $this->pdo->query($sql);
        $dtThang = $stmt->fetchAll();

        // 2. Thống kê trạng thái đơn hàng
        $sql2 =
            "SELECT trang_thai, COUNT(*) AS so_luong"
            . " FROM don_hang"
            . " GROUP BY trang_thai";
        $stmt2 = $this->pdo->query($sql2);
        $sttDh = $stmt2->fetchAll();

        include __DIR__ . '/../Views/admin/thongkedoanhthu.php';
    }
}
