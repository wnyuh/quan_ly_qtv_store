<?php
namespace App\Controllers;

use App\Models\ThuongHieu;
use App\Models\SanPham;

class ThuongHieuController
{
    // Hiển thị sản phẩm theo thương hiệu (slug)
    public function chiTiet(string $slug)
    {
        $em = require __DIR__ . '/../../config/doctrine.php';
        $thuongHieu = $em->getRepository(ThuongHieu::class)
            ->findOneBy(['duongDan' => $slug, 'kichHoat' => true]);
        if (!$thuongHieu) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $sanPhams   = $em->getRepository(SanPham::class)
            ->findBy(
                ['thuongHieu' => $thuongHieu, 'kichHoat' => true],
                ['ngayTao' => 'DESC']
            );
        // Lấy luôn list để hiển thị nav brand
        $thuongHieus = $em->getRepository(ThuongHieu::class)
            ->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        view('thuong-hieu/chi-tiet', compact('thuongHieu','sanPhams','thuongHieus'));
    }
}
