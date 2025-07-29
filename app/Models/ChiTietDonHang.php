<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'chi_tiet_don_hang')]
class ChiTietDonHang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: DonHang::class, inversedBy: 'chiTiets')]
    #[ORM\JoinColumn(name: 'don_hang_id', referencedColumnName: 'id', nullable: false)]
    private DonHang $donHang;

    #[ORM\ManyToOne(targetEntity: BienTheSanPham::class)]
    #[ORM\JoinColumn(name: 'bien_the_san_pham_id', referencedColumnName: 'id', nullable: false)]
    private BienTheSanPham $bienTheSanPham;

    #[ORM\Column(name: 'ten_san_pham', type: 'string', length: 255)]
    private string $tenSanPham;

    #[ORM\Column(name: 'ma_san_pham', type: 'string', length: 100)]
    private string $maSanPham;

    #[ORM\Column(name: 'chi_tiet_bien_the', type: 'json', nullable: true)]
    private ?array $chiTietBienThe = null;

    #[ORM\Column(name: 'so_luong', type: 'integer')]
    private int $soLuong;

    #[ORM\Column(name: 'gia_don_vi', type: 'float')]
    private float $giaDonVi;

    #[ORM\Column(name: 'tong_gia', type: 'float')]
    private float $tongGia;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDonHang(): DonHang
    {
        return $this->donHang;
    }

    public function setDonHang(DonHang $donHang): self
    {
        $this->donHang = $donHang;
        return $this;
    }

    public function getBienTheSanPham(): BienTheSanPham
    {
        return $this->bienTheSanPham;
    }

    public function setBienTheSanPham(BienTheSanPham $bienTheSanPham): self
    {
        $this->bienTheSanPham = $bienTheSanPham;
        return $this;
    }

    public function getTenSanPham(): string
    {
        return $this->tenSanPham;
    }

    public function setTenSanPham(string $tenSanPham): self
    {
        $this->tenSanPham = $tenSanPham;
        return $this;
    }

    public function getMaSanPham(): string
    {
        return $this->maSanPham;
    }

    public function setMaSanPham(string $maSanPham): self
    {
        $this->maSanPham = $maSanPham;
        return $this;
    }

    public function getChiTietBienThe(): ?array
    {
        return $this->chiTietBienThe;
    }

    public function setChiTietBienThe(?array $chiTietBienThe): self
    {
        $this->chiTietBienThe = $chiTietBienThe;
        return $this;
    }

    public function getSoLuong(): int
    {
        return $this->soLuong;
    }

    public function setSoLuong(int $soLuong): self
    {
        $this->soLuong = $soLuong;
        $this->calculateTongGia();
        return $this;
    }

    public function getGiaDonVi(): float
    {
        return $this->giaDonVi;
    }

    public function setGiaDonVi(float $giaDonVi): self
    {
        $this->giaDonVi = $giaDonVi;
        $this->calculateTongGia();
        return $this;
    }

    public function getTongGia(): float
    {
        return $this->tongGia;
    }

    private function calculateTongGia(): void
    {
        if (isset($this->soLuong) && isset($this->giaDonVi)) {
            $this->tongGia = $this->giaDonVi * $this->soLuong;
        }
    }

    public function getNgayTao(): \DateTime
    {
        return $this->ngayTao;
    }

    public function getTongGiaFormatted(): string
    {
        return number_format($this->tongGia, 0, ',', '.') . ' ₫';
    }
}
