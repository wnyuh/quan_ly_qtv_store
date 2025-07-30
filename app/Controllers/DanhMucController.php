<?php

namespace App\Controllers;

use App\Models\DanhMuc;
use App\Models\SanPham;

class DanhMucController
{
    public function danhSach()
    {
        $em = require __DIR__ . '/../../config/doctrine.php';

        // Lấy tất cả danh mục đang hoạt động, sắp xếp theo thứ tự
        $danhMucs = $em->getRepository(DanhMuc::class)
            ->findBy(['kichHoat' => true], ['thuTu' => 'ASC']);

        $data = [
            'pageTitle' => 'Danh Mục Sản Phẩm',
            'danhMucs' => $danhMucs
        ];

        view('danh-muc/danh-sach', $data);
    }

    public function chiTiet($duongDan)
    {
        $em = require __DIR__ . '/../../config/doctrine.php';

        // Lấy danh mục theo slug (đường dẫn)
        $danhMuc = $em->getRepository(DanhMuc::class)
            ->findOneBy(['duongDan' => $duongDan, 'kichHoat' => true]);

        if (!$danhMuc) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        // Lấy danh sách sản phẩm trong danh mục
        $qb = $em->createQueryBuilder();
        $qb->select('sp')
            ->from(SanPham::class, 'sp')
            ->where('sp.kichHoat = :kichHoat')
            ->andWhere('sp.danhMuc = :danhMuc')
            ->setParameter('kichHoat', true)
            ->setParameter('danhMuc', $danhMuc)
            ->orderBy('sp.ngayTao', 'DESC');

        $sanPhams = $qb->getQuery()->getResult();

        // Lấy danh mục để hiển thị trên thanh menu
        $danhMucs = $em->getRepository(DanhMuc::class)
            ->findBy(['kichHoat' => true], ['thuTu' => 'ASC']);

        $data = [
            'pageTitle' => $danhMuc->getTen(),
            'danhMuc' => $danhMuc,
            'sanPhams' => $sanPhams,
            'danhMucs' => $danhMucs,
            'currentDanhMuc' => $danhMuc
        ];

        view('danh-muc/chi-tiet', $data);
    }
}
