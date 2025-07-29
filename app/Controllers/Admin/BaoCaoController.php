<?php

namespace App\Controllers\Admin;

use Doctrine\DBAL\Connection;

class BaoCaoController
{
    private Connection $conn;

    public function __construct()
    {
        $em = require __DIR__ . '/../../../config/doctrine.php';
        $this->conn = $em->getConnection();
        $this->requireAuth();
    }

    public function doanhThu()
    {
        $tuNgay  = $_GET['from'] ?? date('Y-m-01');
        $denNgay = $_GET['to']   ?? date('Y-m-d');
        $donVi   = $_GET['unit'] ?? 'month';  // mặc định tháng

        switch ($donVi) {
            case 'year':
                $chuoiDinhDang = '%Y';       // chỉ năm
                break;
            case 'week':
                $chuoiDinhDang = '%x-W%v';   // tuần
                break;
            case 'day':
                $chuoiDinhDang = '%Y-%m-%d'; // ngày
                break;
            case 'month':
            default:
                $chuoiDinhDang = '%Y-%m';    // tháng
                break;
        }

        // 3) Dùng chung biểu thức cho SELECT, GROUP BY, ORDER BY
        $expr = "DATE_FORMAT(ngay_tao, '$chuoiDinhDang')";

        $sql = <<<SQL
SELECT
    $expr AS donVi,
    SUM(tong_tien) AS doanhThu
FROM don_hang
WHERE trang_thai = :tt
  AND ngay_tao BETWEEN :from AND :to
GROUP BY $expr
ORDER BY $expr ASC
SQL;

        $rows = $this->conn->fetchAllAssociative($sql, [
            'tt'   => 'hoan_thanh',
            'from' => "{$tuNgay} 00:00:00",
            'to'   => "{$denNgay} 23:59:59",
        ]);

        $tongTatCa = array_sum(array_column($rows, 'doanhThu'));

        admin_view('admin/bao-cao/doanh-thu', [
            'pageTitle' => 'Báo cáo Doanh thu',
            'rows'      => $rows,
            'totalAll'  => $tongTatCa,
            'from'      => $tuNgay,
            'to'        => $denNgay,
            'unit'      => $donVi,
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
