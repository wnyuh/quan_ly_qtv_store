<?php

namespace App\Controllers;

use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;

class SanPhamController
{
    public function timKiem()
    {
        $em = require __DIR__ . '/../../config/doctrine.php';

        $tuKhoaTimKiem = $_GET['q'] ?? '';
        $danhMucId = $_GET['danh_muc'] ?? null;
        $thuongHieuId = $_GET['thuong_hieu'] ?? null;
        $giaThapNhat = $_GET['gia_thap_nhat'] ?? null;
        $giaCaoNhat = $_GET['gia_cao_nhat'] ?? null;
        $sapXep = $_GET['sap_xep'] ?? 'moi_nhat';
        $trang = max(1, intval($_GET['trang'] ?? 1));
        $gioiHan = 12;
        $boQua = ($trang - 1) * $gioiHan;

        $qb = $em->createQueryBuilder();
        $qb->select('sp')
           ->from(SanPham::class, 'sp')
           ->where('sp.kichHoat = :kichHoat')
           ->setParameter('kichHoat', true);

        if (!empty($tuKhoaTimKiem)) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('LOWER(sp.ten)', ':tuKhoa'),
                $qb->expr()->like('LOWER(sp.moTaNgan)', ':tuKhoa'),
                $qb->expr()->like('LOWER(sp.maSanPham)', ':tuKhoa')
            ))->setParameter('tuKhoa', '%' . strtolower($tuKhoaTimKiem) . '%');
        }

        if ($danhMucId) {
            $qb->andWhere('sp.danhMuc = :danhMucId')
               ->setParameter('danhMucId', $danhMucId);
        }

        if ($thuongHieuId) {
            $qb->andWhere('sp.thuongHieu = :thuongHieuId')
               ->setParameter('thuongHieuId', $thuongHieuId);
        }

        if ($giaThapNhat) {
            $qb->andWhere('sp.gia >= :giaThapNhat')
               ->setParameter('giaThapNhat', $giaThapNhat);
        }

        if ($giaCaoNhat) {
            $qb->andWhere('sp.gia <= :giaCaoNhat')
               ->setParameter('giaCaoNhat', $giaCaoNhat);
        }

        switch ($sapXep) {
            case 'gia_tang':
                $qb->orderBy('sp.gia', 'ASC');
                break;
            case 'gia_giam':
                $qb->orderBy('sp.gia', 'DESC');
                break;
            case 'ten_tang':
                $qb->orderBy('sp.ten', 'ASC');
                break;
            case 'ten_giam':
                $qb->orderBy('sp.ten', 'DESC');
                break;
            case 'moi_nhat':
            default:
                $qb->orderBy('sp.ngayTao', 'DESC');
                break;
        }

        $tongSanPhamQuery = clone $qb;
        $tongSanPham = $tongSanPhamQuery->select('COUNT(sp.id)')->getQuery()->getSingleScalarResult();
        $tongTrang = ceil($tongSanPham / $gioiHan);

        $sanPhams = $qb->setFirstResult($boQua)
                      ->setMaxResults($gioiHan)
                      ->getQuery()
                      ->getResult();

        $danhMucs = $em->getRepository(DanhMuc::class)
                      ->findBy(['kichHoat' => true], ['thuTu' => 'ASC']);

        $thuongHieus = $em->getRepository(ThuongHieu::class)
                         ->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        $data = [
            'pageTitle' => 'Tìm Kiếm Sản Phẩm',
            'sanPhams' => $sanPhams,
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus,
            'tuKhoaTimKiem' => $tuKhoaTimKiem,
            'danhMucId' => $danhMucId,
            'thuongHieuId' => $thuongHieuId,
            'giaThapNhat' => $giaThapNhat,
            'giaCaoNhat' => $giaCaoNhat,
            'sapXep' => $sapXep,
            'trangHienTai' => $trang,
            'tongTrang' => $tongTrang,
            'tongSanPham' => $tongSanPham
        ];

        view('san-pham/tim-kiem', $data);
    }

    public function chiTiet($duongDan)
    {
        $em = require __DIR__ . '/../../config/doctrine.php';

        $sanPham = $em->getRepository(SanPham::class)
                     ->findOneBy(['duongDan' => $duongDan, 'kichHoat' => true]);

        if (!$sanPham) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $bienTheMacDinh = $sanPham->getBienThes()->filter(function($bienThe) {
            return $bienThe->isKichHoat() && !$bienThe->isHetHang();
        })->first();

        $data = [
            'pageTitle' => $sanPham->getTen() . ' - Chi Tiết Sản Phẩm',
            'sanPham' => $sanPham,
            'bienTheList' => $sanPham->getBienThes()->filter(function($bienThe) {
                return $bienThe->isKichHoat() && !$bienThe->isHetHang();
            }),
            'bienTheSelected' => $bienTheMacDinh
        ];

        view('san-pham/chi-tiet', $data);
    }
}
