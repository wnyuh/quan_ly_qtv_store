<?php
// app/Models/danhgia.php

class danhgia
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
     * Lấy tất cả đánh giá
     * @return array
     */
    public function all()
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM danh_gia ORDER BY ngay_danh_gia DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm đánh giá theo ID
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM danh_gia WHERE ma_danh_gia = :id LIMIT 1"
        );
        $stmt->execute([':id' => (int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    /**
     * Lấy tất cả đánh giá của một sản phẩm
     * @param int $ma_san_pham
     * @return array
     */
    public function findByProduct($ma_san_pham)
    {
        $stmt = $this->pdo->prepare(
            "SELECT dg.*, nd.ten_dang_nhap 
             FROM danh_gia dg
             JOIN nguoi_dung nd ON dg.ma_nguoi_dung = nd.ma_nguoi_dung
             WHERE dg.ma_san_pham = :msp
             ORDER BY dg.ngay_danh_gia DESC"
        );
        $stmt->execute([':msp' => (int)$ma_san_pham]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả đánh giá của một người dùng
     * @param int $ma_nguoi_dung
     * @return array
     */
    public function findByUser($ma_nguoi_dung)
    {
        $stmt = $this->pdo->prepare(
            "SELECT dg.*, sp.ten_san_pham 
             FROM danh_gia dg
             JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham
             WHERE dg.ma_nguoi_dung = :mnd
             ORDER BY dg.ngay_danh_gia DESC"
        );
        $stmt->execute([':mnd' => (int)$ma_nguoi_dung]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm đánh giá mới
     * @param array $data  gồm ma_san_pham, ma_nguoi_dung, so_sao, binh_luan
     * @return int ID vừa tạo
     */
    public function create($data)
    {
        $sql = "INSERT INTO danh_gia 
                (ma_san_pham, ma_nguoi_dung, so_sao, binh_luan) 
                VALUES (:msp, :mnd, :ss, :bl)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':msp' => $data['ma_san_pham'],
            ':mnd' => $data['ma_nguoi_dung'],
            ':ss'  => $data['so_sao'],
            ':bl'  => $data['binh_luan'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật đánh giá
     * @param int   $id
     * @param array $data  có thể gồm so_sao, binh_luan
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => (int)$id];

        if (isset($data['so_sao'])) {
            $fields[]       = "so_sao = :ss";
            $params[':ss']  = $data['so_sao'];
        }
        if (isset($data['binh_luan'])) {
            $fields[]         = "binh_luan = :bl";
            $params[':bl']    = $data['binh_luan'];
        }
        if (empty($fields)) {
            return;
        }

        $sql = "UPDATE danh_gia SET " . implode(', ', $fields) . " 
                WHERE ma_danh_gia = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Xóa đánh giá theo ID
     * @param int $id
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM danh_gia WHERE ma_danh_gia = :id"
        );
        $stmt->execute([':id' => (int)$id]);
    }

    /**
     * Tính điểm trung bình của sản phẩm
     * @param int $ma_san_pham
     * @return float
     */
    public function averageRating($ma_san_pham)
    {
        $stmt = $this->pdo->prepare(
            "SELECT AVG(so_sao) as avg_rating 
             FROM danh_gia 
             WHERE ma_san_pham = :msp"
        );
        $stmt->execute([':msp' => (int)$ma_san_pham]);
        $avg = $stmt->fetchColumn();
        return $avg !== null ? (float)$avg : 0.0;
    }
}
