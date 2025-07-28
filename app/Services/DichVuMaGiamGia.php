<?php

namespace App\Services;

use App\Models\MaGiamGia;
use Doctrine\ORM\EntityManager;

class DichVuMaGiamGia
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function kiemTraMaGiamGia(string $maSo): ?MaGiamGia
    {
        $maSo = strtoupper(trim($maSo));
        
        if (empty($maSo)) {
            return null;
        }

        $maGiamGia = $this->em->getRepository(MaGiamGia::class)
            ->findOneBy(['maGiamGia' => $maSo]);

        if (!$maGiamGia) {
            return null;
        }

        return $maGiamGia->coTheSuDung() ? $maGiamGia : null;
    }

    public function layThongBaoKiemTra(string $maSo): string
    {
        $maSo = strtoupper(trim($maSo));
        
        if (empty($maSo)) {
            return 'Vui lòng nhập mã giảm giá';
        }

        $maGiamGia = $this->em->getRepository(MaGiamGia::class)
            ->findOneBy(['maGiamGia' => $maSo]);

        if (!$maGiamGia) {
            return 'Mã giảm giá không tồn tại';
        }

        if (!$maGiamGia->isKichHoat()) {
            return 'Mã giảm giá đã bị tạm dừng';
        }

        if ($maGiamGia->daHetHan()) {
            return 'Mã giảm giá đã hết hạn';
        }

        if (!$maGiamGia->laHopLe()) {
            return 'Mã giảm giá chưa có hiệu lực';
        }

        if ($maGiamGia->getSoLuongToiDa() !== null && 
            $maGiamGia->getSoLuongDaDung() >= $maGiamGia->getSoLuongToiDa()) {
            return 'Mã giảm giá đã hết lượt sử dụng';
        }

        return 'Mã giảm giá hợp lệ';
    }

    public function kiemTraGiaTriToiThieu(MaGiamGia $maGiamGia, float $tongTienDonHang): bool
    {
        if ($maGiamGia->getGiaTriDonHangToiThieu() === null) {
            return true;
        }

        return $tongTienDonHang >= $maGiamGia->getGiaTriDonHangToiThieu();
    }

    public function layThongBaoGiaTriToiThieu(MaGiamGia $maGiamGia): string
    {
        if ($maGiamGia->getGiaTriDonHangToiThieu() === null) {
            return '';
        }

        return 'Đơn hàng tối thiểu: ' . number_format($maGiamGia->getGiaTriDonHangToiThieu(), 0, ',', '.') . ' ₫';
    }

    public function tinhToanGiamGia(MaGiamGia $maGiamGia, float $tongTienDonHang): array
    {
        $tienGiam = $maGiamGia->tinhTienGiam($tongTienDonHang);
        $tongTienCuoi = max(0, $tongTienDonHang - $tienGiam);

        return [
            'tong_tien_goc' => $tongTienDonHang,
            'tien_giam' => $tienGiam,
            'tong_tien_cuoi' => $tongTienCuoi,
            'phan_tram_giam' => $tongTienDonHang > 0 ? ($tienGiam / $tongTienDonHang) * 100 : 0
        ];
    }

    public function layDanhSachMaHoatDong(int $gioi_han = 10): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('mg')
           ->from(MaGiamGia::class, 'mg')
           ->where('mg.kichHoat = :hoat_dong')
           ->andWhere('mg.ngayBatDau <= :hien_tai')
           ->andWhere('mg.ngayKetThuc >= :hien_tai')
           ->setParameter('hoat_dong', true)
           ->setParameter('hien_tai', new \DateTime())
           ->orderBy('mg.ngayTao', 'DESC')
           ->setMaxResults($gioi_han);

        return $qb->getQuery()->getResult();
    }

    public function layDanhSachMaHetHan(int $gioi_han = 10): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('mg')
           ->from(MaGiamGia::class, 'mg')
           ->where('mg.ngayKetThuc < :hien_tai')
           ->setParameter('hien_tai', new \DateTime())
           ->orderBy('mg.ngayKetThuc', 'DESC')
           ->setMaxResults($gioi_han);

        return $qb->getQuery()->getResult();
    }

    public function layThongKeSuDung(MaGiamGia $maGiamGia): array
    {
        $phanTramSuDung = 0;
        if ($maGiamGia->getSoLuongToiDa() !== null && $maGiamGia->getSoLuongToiDa() > 0) {
            $phanTramSuDung = ($maGiamGia->getSoLuongDaDung() / $maGiamGia->getSoLuongToiDa()) * 100;
        }

        return [
            'tong_luot_su_dung' => $maGiamGia->getSoLuongDaDung(),
            'so_luot_toi_da' => $maGiamGia->getSoLuongToiDa(),
            'so_luot_con_lai' => $maGiamGia->laySoLuongConLai(),
            'phan_tram_su_dung' => round($phanTramSuDung, 2),
            'khong_gioi_han' => $maGiamGia->getSoLuongToiDa() === null
        ];
    }
}