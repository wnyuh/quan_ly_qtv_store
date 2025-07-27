<?php
// app/Models/danhmuc.php

class danhmuc
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
     * Lấy tất cả danh mục
     * @return array
     */
    public function all()
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM danh_muc ORDER BY ngay_tao DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm 1 danh mục theo ID
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM danh_muc WHERE ma_danh_muc = :id LIMIT 1"
        );
        $stmt->execute([':id' => (int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    /**
     * Thêm danh mục mới
     * @param array $data ['ten_danh_muc']
     * @return int ID vừa tạo
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO danh_muc (ten_danh_muc) VALUES (:ten)"
        );
        $stmt->execute([
            ':ten' => trim($data['ten_danh_muc'])
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Cập nhật tên danh mục
     * @param int   $id
     * @param array $data ['ten_danh_muc']
     */
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE danh_muc 
             SET ten_danh_muc = :ten 
             WHERE ma_danh_muc = :id"
        );
        $stmt->execute([
            ':ten' => trim($data['ten_danh_muc']),
            ':id'  => (int)$id
        ]);
    }

    /**
     * Xóa danh mục theo ID
     * @param int $id
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM danh_muc WHERE ma_danh_muc = :id"
        );
        $stmt->execute([':id' => (int)$id]);
    }
}
