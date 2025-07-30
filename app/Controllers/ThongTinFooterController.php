<?php

namespace App\Controllers;

class ThongTinFooterController
{
    public function huongDanMuaHang()
    {
        view('thong-tin-footer/huong_dan_mua_hang');
    }

    public function doiTraSanPham()
    {
        view('thong-tin-footer/doi_tra_san_pham');
    }

    public function baoHanh()
    {
        view('thong-tin-footer/chinh_sach_bao_hanh');
    }

    public function hinhThucThanhToan()
    {
        view('thong-tin-footer/hinh_thuc_thanh_toan');
    }
}
