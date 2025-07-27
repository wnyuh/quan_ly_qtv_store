<?php
// app/Controllers/anh_controller.php
class anh_controller
{
    protected $model;
    public function __construct(PDO $pdo)
    {
        // chỉ admin mới được vào
        if (session_status()===PHP_SESSION_NONE) session_start();
        require_once __DIR__ . '/../Models/anh.php';
        $this->model = new anh($pdo);
    }

    /** GET  ?url=anh/index */
    public function index()
    {
        $images = $this->model->all();
        require __DIR__ . '/../Views/admin/them_anh.php';
    }

    /** POST ?url=anh/store */
    public function store()
    {
        if (!empty($_FILES['hinh_anh']['tmp_name'])) {
            $file  = $_FILES['hinh_anh'];
            $ext   = pathinfo($file['name'], PATHINFO_EXTENSION);
            $name  = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], __DIR__ . '/../../public/images/' . $name);

            $thuTu = (int)($_POST['thu_tu'] ?? 0);
            $this->model->create($name, $thuTu);
        }
        header('Location: index.php?url=anh/index');
        exit;
    }
}
