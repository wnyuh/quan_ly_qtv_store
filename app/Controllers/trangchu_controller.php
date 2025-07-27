<?php
// app/Controllers/trangchu_controller.php
class trangchu_controller
{
    protected $pdo, $anhModel, $dmModel, $spModel;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        require_once __DIR__ . '/../Models/anh.php';
        require_once __DIR__ . '/../Models/danhmuc.php';
        require_once __DIR__ . '/../Models/sanpham.php';

        $this->anhModel = new anh($pdo);
        $this->dmModel  = new danhmuc($pdo);
        $this->spModel  = new sanpham($pdo);
    }

    public function index()
    {
        $banners = $this->anhModel->all();             // từ bảng anh
        $dms     = $this->dmModel->all();              // tabs danh mục
        $sps     = $this->spModel->latest(8);          // 8 sp mới nhất

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/trangchu/index.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
