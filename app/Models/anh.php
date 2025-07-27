<?php
// app/Models/anh.php
class anh
{
    protected $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /** Lấy tất cả ảnh theo thứ tự */
    public function all() {
        return $this->pdo
            ->query("SELECT * FROM anh ORDER BY thu_tu")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Thêm một ảnh mới */
    public function create(string $fileName, int $thuTu) {
        $stmt = $this->pdo->prepare("INSERT INTO anh (hinh_anh, thu_tu) VALUES (?, ?)");
        $stmt->execute([$fileName, $thuTu]);
    }
}
