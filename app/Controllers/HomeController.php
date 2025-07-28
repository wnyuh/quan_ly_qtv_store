<?php

namespace App\Controllers;

use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;

class HomeController
{
    public function index()
    {
        $em = require __DIR__ . '/../../config/doctrine.php';
        
        // Lấy sản phẩm nổi bật
        $sanPhamNoiBat = $em->getRepository(SanPham::class)
            ->findBy([
                'noiBat' => true,
                'kichHoat' => true
            ], ['ngayTao' => 'DESC'], 8);
        
        // Lấy sản phẩm mới nhất
        $sanPhamMoi = $em->getRepository(SanPham::class)
            ->findBy([
                'kichHoat' => true
            ], ['ngayTao' => 'DESC'], 4);
        
        // Lấy danh mục sản phẩm
        $danhMucs = $em->getRepository(DanhMuc::class)
            ->findBy(['kichHoat' => true], ['thuTu' => 'ASC']);
        
        // Lấy thương hiệu
        $thuongHieus = $em->getRepository(ThuongHieu::class)
            ->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        
        $data = [
            'pageTitle' => 'Cửa Hàng Điện Thoại - Trang Chủ',
            'sanPhamNoiBat' => $sanPhamNoiBat,
            'sanPhamMoi' => $sanPhamMoi,
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus
        ];

        view('home', $data);
    }
}
