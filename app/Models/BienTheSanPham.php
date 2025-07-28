<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'bien_the_san_pham')]
class BienTheSanPham
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: SanPham::class, inversedBy: 'bienThes')]
    #[ORM\JoinColumn(name: 'san_pham_id', referencedColumnName: 'id', nullable: false)]
    private SanPham $sanPham;

    #[ORM\Column(name: 'ma_san_pham', type: 'string', length: 100, unique: true)]
    private string $maSanPham;

    #[ORM\Column(name: 'mau_sac', type: 'string', length: 50, nullable: true)]
    private ?string $mauSac = null;

    #[ORM\Column(name: 'bo_nho', type: 'string', length: 50, nullable: true)]
    private ?string $boNho = null;

    #[ORM\Column(name: 'gia', type: 'float')]
    private float $gia;

    #[ORM\Column(name: 'gia_so_sanh', type: 'float', nullable: true)]
    private ?float $giaSoSanh = null;

    #[ORM\Column(name: 'so_luong_ton', type: 'integer')]
    private int $soLuongTon = 0;

    #[ORM\Column(name: 'nguong_ton_thap', type: 'integer')]
    private int $nguongTonThap = 5;

    #[ORM\Column(name: 'trong_luong', type: 'float', nullable: true)]
    private ?float $trongLuong = null;

    #[ORM\Column(name: 'kich_hoat', type: 'boolean')]
    private bool $kichHoat = true;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    #[ORM\Column(name: 'ngay_cap_nhat', type: 'datetime')]
    private \DateTime $ngayCapNhat;

    #[ORM\OneToMany(mappedBy: 'bienThe', targetEntity: HinhAnhSanPham::class)]
    private Collection $hinhAnhs;

    #[ORM\OneToMany(mappedBy: 'bienTheSanPham', targetEntity: GioHang::class)]
    private Collection $gioHangs;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        $this->ngayCapNhat = new \DateTime();
        $this->hinhAnhs = new ArrayCollection();
        $this->gioHangs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSanPham(): SanPham
    {
        return $this->sanPham;
    }

    public function setSanPham(SanPham $sanPham): self
    {
        $this->sanPham = $sanPham;
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

    public function getMauSac(): ?string
    {
        return $this->mauSac;
    }

    public function setMauSac(?string $mauSac): self
    {
        $this->mauSac = $mauSac;
        return $this;
    }

    public function getBoNho(): ?string
    {
        return $this->boNho;
    }

    public function setBoNho(?string $boNho): self
    {
        $this->boNho = $boNho;
        return $this;
    }

    public function getGia(): float
    {
        return $this->gia;
    }

    public function setGia(float $gia): self
    {
        $this->gia = $gia;
        return $this;
    }

    public function getGiaSoSanh(): ?float
    {
        return $this->giaSoSanh;
    }

    public function setGiaSoSanh(?float $giaSoSanh): self
    {
        $this->giaSoSanh = $giaSoSanh;
        return $this;
    }

    public function getGiaFormatted(): string
    {
        return number_format($this->gia, 0, ',', '.') . ' ₫';
    }

    public function getSoLuongTon(): int
    {
        return $this->soLuongTon;
    }

    public function setSoLuongTon(int $soLuongTon): self
    {
        $this->soLuongTon = $soLuongTon;
        return $this;
    }

    public function getNguongTonThap(): int
    {
        return $this->nguongTonThap;
    }

    public function setNguongTonThap(int $nguongTonThap): self
    {
        $this->nguongTonThap = $nguongTonThap;
        return $this;
    }

    public function isTonThap(): bool
    {
        return $this->soLuongTon <= $this->nguongTonThap;
    }

    public function isHetHang(): bool
    {
        return $this->soLuongTon <= 0;
    }

    public function getTrongLuong(): ?float
    {
        return $this->trongLuong;
    }

    public function setTrongLuong(?float $trongLuong): self
    {
        $this->trongLuong = $trongLuong;
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

    public function getHinhAnhs(): Collection
    {
        return $this->hinhAnhs;
    }

    public function getGioHangs(): Collection
    {
        return $this->gioHangs;
    }

    public function getTenDayDu(): string
    {
        $ten = $this->sanPham->getTen();
        if ($this->boNho) {
            $ten .= ' ' . $this->boNho;
        }
        if ($this->mauSac) {
            $ten .= ' - ' . $this->mauSac;
        }
        return $ten;
    }
}
