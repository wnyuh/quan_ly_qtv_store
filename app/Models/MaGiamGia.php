<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ma_giam_gia')]
class MaGiamGia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'ma_giam_gia', type: 'string', length: 50, unique: true)]
    private string $maGiamGia;

    #[ORM\Column(name: 'ten', type: 'string', length: 255)]
    private string $ten;

    #[ORM\Column(name: 'mo_ta', type: 'text', nullable: true)]
    private ?string $moTa = null;

    #[ORM\Column(name: 'loai_giam_gia', type: 'string', length: 20)]
    private string $loaiGiamGia; // 'phan_tram' or 'so_tien'

    #[ORM\Column(name: 'gia_tri_giam', type: 'float')]
    private float $giaTriGiam;

    #[ORM\Column(name: 'gia_tri_don_hang_toi_thieu', type: 'float', nullable: true)]
    private ?float $giaTriDonHangToiThieu = null;

    #[ORM\Column(name: 'so_luong_toi_da', type: 'integer', nullable: true)]
    private ?int $soLuongToiDa = null;

    #[ORM\Column(name: 'so_luong_da_dung', type: 'integer')]
    private int $soLuongDaDung = 0;

    #[ORM\Column(name: 'ngay_bat_dau', type: 'datetime')]
    private \DateTime $ngayBatDau;

    #[ORM\Column(name: 'ngay_ket_thuc', type: 'datetime')]
    private \DateTime $ngayKetThuc;

    #[ORM\Column(name: 'kich_hoat', type: 'boolean')]
    private bool $kichHoat = true;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    #[ORM\Column(name: 'ngay_cap_nhat', type: 'datetime')]
    private \DateTime $ngayCapNhat;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        $this->ngayCapNhat = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMaGiamGia(): string
    {
        return $this->maGiamGia;
    }

    public function setMaGiamGia(string $maGiamGia): self
    {
        $this->maGiamGia = strtoupper(trim($maGiamGia));
        return $this;
    }

    public function getTen(): string
    {
        return $this->ten;
    }

    public function setTen(string $ten): self
    {
        $this->ten = $ten;
        return $this;
    }

    public function getMoTa(): ?string
    {
        return $this->moTa;
    }

    public function setMoTa(?string $moTa): self
    {
        $this->moTa = $moTa;
        return $this;
    }

    public function getLoaiGiamGia(): string
    {
        return $this->loaiGiamGia;
    }

    public function setLoaiGiamGia(string $loaiGiamGia): self
    {
        $this->loaiGiamGia = $loaiGiamGia;
        return $this;
    }

    public function getGiaTriGiam(): float
    {
        return $this->giaTriGiam;
    }

    public function setGiaTriGiam(float $giaTriGiam): self
    {
        $this->giaTriGiam = $giaTriGiam;
        return $this;
    }

    public function getGiaTriDonHangToiThieu(): ?float
    {
        return $this->giaTriDonHangToiThieu;
    }

    public function setGiaTriDonHangToiThieu(?float $giaTriDonHangToiThieu): self
    {
        $this->giaTriDonHangToiThieu = $giaTriDonHangToiThieu;
        return $this;
    }

    public function getSoLuongToiDa(): ?int
    {
        return $this->soLuongToiDa;
    }

    public function setSoLuongToiDa(?int $soLuongToiDa): self
    {
        $this->soLuongToiDa = $soLuongToiDa;
        return $this;
    }

    public function getSoLuongDaDung(): int
    {
        return $this->soLuongDaDung;
    }

    public function setSoLuongDaDung(int $soLuongDaDung): self
    {
        $this->soLuongDaDung = $soLuongDaDung;
        return $this;
    }

    public function getNgayBatDau(): \DateTime
    {
        return $this->ngayBatDau;
    }

    public function setNgayBatDau(\DateTime $ngayBatDau): self
    {
        $this->ngayBatDau = $ngayBatDau;
        return $this;
    }

    public function getNgayKetThuc(): \DateTime
    {
        return $this->ngayKetThuc;
    }

    public function setNgayKetThuc(\DateTime $ngayKetThuc): self
    {
        $this->ngayKetThuc = $ngayKetThuc;
        return $this;
    }

    public function isKichHoat(): bool
    {
        return $this->kichHoat;
    }

    public function setKichHoat(bool $kichHoat): self
    {
        $this->kichHoat = $kichHoat;
        return $this;
    }

    public function getNgayTao(): \DateTime
    {
        return $this->ngayTao;
    }

    public function getNgayCapNhat(): \DateTime
    {
        return $this->ngayCapNhat;
    }

    public function setNgayCapNhat(): self
    {
        $this->ngayCapNhat = new \DateTime();
        return $this;
    }

    public function laHopLe(): bool
    {
        if (!$this->kichHoat) {
            return false;
        }

        $hienTai = new \DateTime();
        return $hienTai >= $this->ngayBatDau && $hienTai <= $this->ngayKetThuc;
    }

    public function daHetHan(): bool
    {
        $hienTai = new \DateTime();
        return $hienTai > $this->ngayKetThuc;
    }

    public function coTheSuDung(): bool
    {
        if (!$this->laHopLe()) {
            return false;
        }

        if ($this->soLuongToiDa !== null) {
            return $this->soLuongDaDung < $this->soLuongToiDa;
        }

        return true;
    }

    public function tinhTienGiam(float $tongTienDonHang): float
    {
        if (!$this->coTheSuDung()) {
            return 0;
        }

        if ($this->giaTriDonHangToiThieu !== null && $tongTienDonHang < $this->giaTriDonHangToiThieu) {
            return 0;
        }

        if ($this->loaiGiamGia === 'phan_tram') {
            return ($tongTienDonHang * $this->giaTriGiam) / 100;
        }

        return min($this->giaTriGiam, $tongTienDonHang);
    }

    public function tangSoLuongSuDung(): self
    {
        $this->soLuongDaDung++;
        $this->setNgayCapNhat();
        return $this;
    }

    public function laySoLuongConLai(): ?int
    {
        if ($this->soLuongToiDa === null) {
            return null;
        }

        return max(0, $this->soLuongToiDa - $this->soLuongDaDung);
    }

    public function layGiaTriGiamDinhDang(): string
    {
        if ($this->loaiGiamGia === 'phan_tram') {
            return $this->giaTriGiam . '%';
        }

        return number_format($this->giaTriGiam, 0, ',', '.') . ' ₫';
    }

    public function layTrangThaiText(): string
    {
        if (!$this->kichHoat) {
            return 'Tạm dừng';
        }

        if ($this->daHetHan()) {
            return 'Hết hạn';
        }

        if (!$this->laHopLe()) {
            return 'Chưa bắt đầu';
        }

        if ($this->soLuongToiDa !== null && $this->soLuongDaDung >= $this->soLuongToiDa) {
            return 'Hết lượt sử dụng';
        }

        return 'Hoạt động';
    }
}