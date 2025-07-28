<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'gio_hang')]
class GioHang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: NguoiDung::class, inversedBy: 'gioHangs')]
    #[ORM\JoinColumn(name: 'nguoi_dung_id', referencedColumnName: 'id', nullable: false)]
    private NguoiDung $nguoiDung;

    #[ORM\ManyToOne(targetEntity: BienTheSanPham::class, inversedBy: 'gioHangs')]
    #[ORM\JoinColumn(name: 'bien_the_san_pham_id', referencedColumnName: 'id', nullable: false)]
    private BienTheSanPham $bienTheSanPham;

    #[ORM\Column(name: 'so_luong', type: 'integer')]
    private int $soLuong = 1;

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

    public function getNguoiDung(): NguoiDung
    {
        return $this->nguoiDung;
    }

    public function setNguoiDung(NguoiDung $nguoiDung): self
    {
        $this->nguoiDung = $nguoiDung;
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

    public function getSoLuong(): int
    {
        return $this->soLuong;
    }

    public function setSoLuong(int $soLuong): self
    {
        $this->soLuong = $soLuong;
        $this->setNgayCapNhat();
        return $this;
    }

    public function tangSoLuong(int $soLuong = 1): self
    {
        $this->soLuong += $soLuong;
        $this->setNgayCapNhat();
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

    public function getTongGia(): int
    {
        return $this->bienTheSanPham->getGia() * $this->soLuong;
    }

    public function getTongGiaFormatted(): string
    {
        return number_format($this->getTongGia(), 0, ',', '.') . ' ₫';
    }
}
