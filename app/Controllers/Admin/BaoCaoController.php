<?php

namespace App\Controllers\Admin;

use Doctrine\DBAL\Connection;

class BaoCaoController
{
    private Connection $conn;

    public function __construct()
    {
        // Lấy Doctrine EntityManager, rồi lấy DBAL Connection từ nó
        $em = require __DIR__ . '/../../../config/doctrine.php';
        $this->conn = $em->getConnection();
        // nếu cần auth admin
        $this->requireAuth();
    }

    public function doanhThu()
    {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to   = $_GET['to']   ?? date('Y-m-d');

        // SQL thuần
        $sql = <<<SQL
SELECT
    DATE(ngay_tao) AS ngay,
    SUM(tong_tien) AS doanhThu
FROM don_hang
WHERE trang_thai = :tt
  AND ngay_tao BETWEEN :from AND :to
GROUP BY DATE(ngay_tao)
ORDER BY DATE(ngay_tao) ASC
SQL;

        // Thực thi và tự bind tham số
        $rows = $this->conn->fetchAllAssociative($sql, [
            'tt'   => 'hoan_thanh',
            'from' => "{$from} 00:00:00",
            'to'   => "{$to} 23:59:59",
        ]);

        // Tính tổng cả giai đoạn
        $totalAll = array_sum(array_column($rows, 'doanhThu'));

        // Render view
        admin_view('admin/bao-cao/doanh-thu', [
            'pageTitle' => 'Báo cáo Doanh thu',
            'rows'      => $rows,
            'totalAll'  => $totalAll,
            'from'      => $from,
            'to'        => $to,
        ]);
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}
