<?php
// app/Controllers/qly_danhmuc_controller.php

require __DIR__ . '/../Models/danhmuc.php';

class qly_danhmuc_controller
{
    private $m;

    public function __construct(PDO $pdo)
    {
        $this->m = new DanhMuc($pdo);
    }

    // DS danh mục
    public function index()
    {
        $dms = $this->m->all();
        include __DIR__ . '/../Views/admin/menu_chung.php';
        include __DIR__ . '/../Views/admin/qly_danhmuc.php';
    }

    // form thêm
    public function create()
    {
        include __DIR__ . '/../Views/admin/menu_chung.php';
        include __DIR__ . '/../Views/admin/them_danhmuc.php';
    }

    // xử lý thêm
    public function store()
    {
        $ten = trim($_POST['ten'] ?? '');
        $this->m->insert(['ten_danh_muc'=>$ten]);
        header('Location: index.php?url=quan-ly-dm/index');
    }

    // form sửa
    public function edit()
    {
        $id  = (int)($_GET['id'] ?? 0);
        $dm  = $this->m->find($id);
        include __DIR__ . '/../Views/admin/menu_chung.php';
        include __DIR__ . '/../Views/admin/sua_danhmuc.php';
    }

    // xử lý cập nhật
    public function update()
    {
        $id  = (int)($_POST['id'] ?? 0);
        $ten = trim($_POST['ten'] ?? '');
        $this->m->update($id, ['ten_danh_muc'=>$ten]);
        header('Location: index.php?url=quan-ly-dm/index');
    }

    // xóa
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->m->delete($id);
        header('Location: index.php?url=quan-ly-dm/index');
    }
}
