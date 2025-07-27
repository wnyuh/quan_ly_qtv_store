<?php
// app/Controllers/diachi_controller.php

class diachi_controller
{
    protected $model;

    public function __construct(PDO $pdo)
    {
        require_once __DIR__ . '/../Models/diachi.php';
        $this->model = new diachi($pdo);

        if (session_status()===PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?url=admin/login');
            exit;
        }
    }

    // GET ?url=diachi/index
    public function index()
    {
        $maNd = $_SESSION['user']['ma_nguoi_dung'];
        $dcs  = $this->model->findByUser($maNd);
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/diachi/index.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    // GET ?url=diachi/create
    public function create()
    {
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/diachi/form.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    // POST ?url=diachi/store
    public function store()
    {
        $maNd = $_SESSION['user']['ma_nguoi_dung'];
        $data = [
            'ma_nguoi_dung'  => $maNd,
            'dia_chi'        => trim($_POST['dia_chi'] ?? ''),
            'so_dien_thoai'  => trim($_POST['so_dien_thoai'] ?? ''),
            'ten_nguoi_nhan' => trim($_POST['ten_nguoi_nhan'] ?? '')
        ];
        $this->model->create($data);
        header('Location: index.php?url=diachi/index');
        exit;
    }

    // GET ?url=diachi/edit&id=xx
    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        $dc = $this->model->find($id);
        if (!$dc || $dc['ma_nguoi_dung'] != $_SESSION['user']['ma_nguoi_dung']) {
            http_response_code(404);
            exit('Địa chỉ không tồn tại.');
        }
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/diachi/form.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    // POST ?url=diachi/update
    public function update()
    {
        $id = (int)($_POST['ma_dia_chi'] ?? 0);
        $data = [
            'dia_chi'        => trim($_POST['dia_chi'] ?? ''),
            'so_dien_thoai'  => trim($_POST['so_dien_thoai'] ?? ''),
            'ten_nguoi_nhan' => trim($_POST['ten_nguoi_nhan'] ?? '')
        ];
        $this->model->update($id, $data);
        header('Location: index.php?url=diachi/index');
        exit;
    }

    // GET ?url=diachi/delete&id=xx
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->model->delete($id);
        header('Location: index.php?url=diachi/index');
        exit;
    }
}
