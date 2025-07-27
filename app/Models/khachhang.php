<?php
// app/Models/khachhang.php

class khachhang
{
    /** @var PDO */
    public $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả khách hàng (vai_tro = 'khach_hang')
     * @return array
     */
    public function all()
    {
        $stmt = $this->pdo->prepare(
            "SELECT ma_nguoi_dung, ten_dang_nhap, email, ngay_tao 
             FROM nguoi_dung 
             WHERE vai_tro = 'khach_hang' 
             ORDER BY ngay_tao DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm khách hàng theo ID
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT ma_nguoi_dung, ten_dang_nhap, email, ngay_tao 
             FROM nguoi_dung 
             WHERE ma_nguoi_dung = :id 
               AND vai_tro = 'khach_hang'
             LIMIT 1"
        );
        $stmt->execute([':id' => (int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Thêm khách hàng mới
     * @param array $data  (ten_dang_nhap, mat_khau, email)
     * @return int ID vừa tạo
     */
    public function create($data)
    {
        $sql = "INSERT INTO nguoi_dung 
                (ten_dang_nhap, mat_khau, email, vai_tro) 
                VALUES (:dn, :mk, :em, 'khach_hang')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':dn' => trim($data['ten_dang_nhap']),
            ':mk' => password_hash($data['mat_khau'], PASSWORD_DEFAULT),
            ':em' => trim($data['email']),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật thông tin khách hàng
     * @param int   $id
     * @param array $data  (mat_khau?, email?)
     */
    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => (int)$id];

        if (!empty($data['mat_khau'])) {
            $fields[]        = "mat_khau = :mk";
            $params[':mk']   = password_hash($data['mat_khau'], PASSWORD_DEFAULT);
        }
        if (!empty($data['email'])) {
            $fields[]        = "email = :em";
            $params[':em']   = trim($data['email']);
        }

        if (empty($fields)) {
            return;
        }

        $sql = "UPDATE nguoi_dung 
                SET " . implode(', ', $fields) . " 
                WHERE ma_nguoi_dung = :id 
                  AND vai_tro = 'khach_hang'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Xóa khách hàng theo ID
     * @param int $id
     */
    public function delete($id)
    {
        // Chỉ xóa user nếu đúng vai trò
        $stmt = $this->pdo->prepare(
            "DELETE FROM nguoi_dung 
             WHERE ma_nguoi_dung = :id 
               AND vai_tro = 'khach_hang'"
        );
        $stmt->execute([':id' => (int)$id]);
    }
}
