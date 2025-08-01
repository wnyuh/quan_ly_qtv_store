<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'dia_chi_nguoi_dung')]
class DiaChiNguoiDung
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: NguoiDung::class, inversedBy: 'diaChis')]
    #[ORM\JoinColumn(name: 'nguoi_dung_id', referencedColumnName: 'id', nullable: false)]
    private NguoiDung $nguoiDung;

    #[ORM\Column(name: 'loai', type: 'string', length: 20)]
    private string $loai; // 'thanh_toan' hoặc 'giao_hang'

    #[ORM\Column(name: 'ho', type: 'string', length: 100)]
    private string $ho;

    #[ORM\Column(name: 'ten', type: 'string', length: 100)]
    private string $ten;

    #[ORM\Column(name: 'cong_ty', type: 'string', length: 255, nullable: true)]
    private ?string $congTy = null;

    #[ORM\Column(name: 'dia_chi_1', type: 'string', length: 255)]
    private string $diaChi1;

    #[ORM\Column(name: 'dia_chi_2', type: 'string', length: 255, nullable: true)]
    private ?string $diaChi2 = null;

    #[ORM\Column(name: 'thanh_pho', type: 'string', length: 100)]
    private string $thanhPho;

    #[ORM\Column(name: 'tinh_thanh', type: 'string', length: 100)]
    private string $tinhThanh;

    #[ORM\Column(name: 'ma_buu_dien', type: 'string', length: 20)]
    private string $maBuuDien;

    #[ORM\Column(name: 'quoc_gia', type: 'string', length: 100)]
    private string $quocGia = 'Việt Nam';

    #[ORM\Column(name: 'mac_dinh', type: 'boolean')]
    private bool $macDinh = false;

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

    public function getNguoiDung(): NguoiDung
    {
        return $this->nguoiDung;
    }

    public function setNguoiDung(NguoiDung $nguoiDung): self
    {
        $this->nguoiDung = $nguoiDung;
        return $this;
    }

    public function getLoai(): string
    {
        return $this->loai;
    }

    public function setLoai(string $loai): self
    {
        $this->loai = $loai;
        return $this;
    }

    public function getHo(): string
    {
        return $this->ho;
    }

    public function setHo(string $ho): self
    {
        $this->ho = $ho;
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

    public function getHoTen(): string
    {
        return $this->ho . ' ' . $this->ten;
    }

    public function getCongTy(): ?string
    {
        return $this->congTy;
    }

    public function setCongTy(?string $congTy): self
    {
        $this->congTy = $congTy;
        return $this;
    }

    public function getDiaChi1(): string
    {
        return $this->diaChi1;
    }

    public function setDiaChi1(string $diaChi1): self
    {
        $this->diaChi1 = $diaChi1;
        return $this;
    }

    public function getDiaChi2(): ?string
    {
        return $this->diaChi2;
    }

    public function setDiaChi2(?string $diaChi2): self
    {
        $this->diaChi2 = $diaChi2;
        return $this;
    }

    public function getThanhPho(): string
    {
        return $this->thanhPho;
    }

    public function setThanhPho(string $thanhPho): self
    {
        $this->thanhPho = $thanhPho;
        return $this;
    }

    public function getTinhThanh(): string
    {
        return $this->tinhThanh;
    }

    public function setTinhThanh(string $tinhThanh): self
    {
        $this->tinhThanh = $tinhThanh;
        return $this;
    }

    public function getMaBuuDien(): string
    {
        return $this->maBuuDien;
    }

    public function setMaBuuDien(string $maBuuDien): self
    {
        $this->maBuuDien = $maBuuDien;
        return $this;
    }

    public function getQuocGia(): string
    {
        return $this->quocGia;
    }

    public function setQuocGia(string $quocGia): self
    {
        $this->quocGia = $quocGia;
        return $this;
    }

    public function isMacDinh(): bool
    {
        return $this->macDinh;
    }

    public function setMacDinh(bool $macDinh): self
    {
        $this->macDinh = $macDinh;
        return $this;
    }

    public function getNgayTao(): \DateTime
    {
        return $this->ngayTao;
    }

    public function getDiaChiDayDu(): string
    {
        $diaChi = $this->diaChi1;
        if ($this->diaChi2) {
            $diaChi .= ', ' . $this->diaChi2;
        }
        $diaChi .= ', ' . $this->thanhPho . ', ' . $this->tinhThanh . ' ' . $this->maBuuDien;
        return $diaChi;
    }
}
