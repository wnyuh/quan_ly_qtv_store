<?php
// app/Models/thanhtoan.php

class thanhtoan
{
    /** @var PDO */
    protected $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả bản ghi thanh toán
     * @return array
     */
    public function all()
    {
        $stmt = $this->pdo->query(
            "SELECT * 
             FROM thanh_toan 
             ORDER BY ngay_thanh_toan DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm một bản ghi thanh toán theo ID
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * 
             FROM thanh_toan 
             WHERE ma_thanh_toan = :id 
             LIMIT 1"
        );
        $stmt->execute([':id' => (int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Lấy tất cả thanh toán của một đơn hàng
     * @param int $ma_don_hang
     * @return array
     */
    public function findByOrder($ma_don_hang)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * 
             FROM thanh_toan 
             WHERE ma_don_hang = :mdh 
             ORDER BY ngay_thanh_toan DESC"
        );
        $stmt->execute([':mdh' => (int)$ma_don_hang]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo mới bản ghi thanh toán
     * @param array $data  (ma_don_hang, so_tien, phuong_thuc, trang_thai)
     * @return int ID vừa tạo
     */
    public function create($data)
    {
        $sql = "INSERT INTO thanh_toan 
                (ma_don_hang, so_tien, phuong_thuc, trang_thai) 
                VALUES (:mdh, :st, :pt, :tt)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':mdh' => (int)$data['ma_don_hang'],
            ':st'  => (float)$data['so_tien'],
            ':pt'  => trim($data['phuong_thuc']),
            ':tt'  => trim($data['trang_thai']),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật bản ghi thanh toán
     * @param int   $id
     * @param array $data  (so_tien?, phuong_thuc?, trang_thai?)
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => (int)$id];

        if (isset($data['so_tien'])) {
            $fields[]         = "so_tien = :st";
            $params[':st']    = (float)$data['so_tien'];
        }
        if (isset($data['phuong_thuc'])) {
            $fields[]             = "phuong_thuc = :pt";
            $params[':pt']        = trim($data['phuong_thuc']);
        }
        if (isset($data['trang_thai'])) {
            $fields[]             = "trang_thai = :tt";
            $params[':tt']        = trim($data['trang_thai']);
        }

        if (empty($fields)) {
            return;
        }

        $sql = "UPDATE thanh_toan 
                SET " . implode(', ', $fields) . " 
                WHERE ma_thanh_toan = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Xóa bản ghi thanh toán theo ID
     * @param int $id
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM thanh_toan 
             WHERE ma_thanh_toan = :id"
        );
        $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Tính tổng doanh thu trong khoảng ngày
     * @param string $from  'YYYY-MM-DD'
     * @param string $to    'YYYY-MM-DD'
     * @return float
     */
    public function sumBetween($from, $to)
    {
        $stmt = $this->pdo->prepare(
            "SELECT SUM(so_tien) 
             FROM thanh_toan 
             WHERE DATE(ngay_thanh_toan) BETWEEN :from AND :to"
        );
        $stmt->execute([
            ':from' => $from,
            ':to'   => $to,
        ]);
        $sum = $stmt->fetchColumn();
        return $sum !== null ? (float)$sum : 0.0;
    }
}
