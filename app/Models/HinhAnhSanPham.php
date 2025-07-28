<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'hinh_anh_san_pham')]
class HinhAnhSanPham
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: SanPham::class, inversedBy: 'hinhAnhs')]
    #[ORM\JoinColumn(name: 'san_pham_id', referencedColumnName: 'id', nullable: false)]
    private SanPham $sanPham;

    #[ORM\ManyToOne(targetEntity: BienTheSanPham::class, inversedBy: 'hinhAnhs')]
    #[ORM\JoinColumn(name: 'bien_the_id', referencedColumnName: 'id', nullable: true)]
    private ?BienTheSanPham $bienThe = null;

    #[ORM\Column(name: 'duong_dan_hinh', type: 'string', length: 500)]
    private string $duongDanHinh;

    #[ORM\Column(name: 'text_thay_the', type: 'string', length: 255, nullable: true)]
    private ?string $textThayThe = null;

    #[ORM\Column(name: 'thu_tu', type: 'integer')]
    private int $thuTu = 0;

    #[ORM\Column(name: 'hinh_chinh', type: 'boolean')]
    private bool $hinhChinh = false;

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

    public function getSanPham(): SanPham
    {
        return $this->sanPham;
    }

    public function setSanPham(SanPham $sanPham): self
    {
        $this->sanPham = $sanPham;
        return $this;
    }

    public function getBienThe(): ?BienTheSanPham
    {
        return $this->bienThe;
    }

    public function setBienThe(?BienTheSanPham $bienThe): self
    {
        $this->bienThe = $bienThe;
        return $this;
    }

    public function getDuongDanHinh(): string
    {
        return $this->duongDanHinh;
    }

    public function setDuongDanHinh(string $duongDanHinh): self
    {
        $this->duongDanHinh = $duongDanHinh;
        return $this;
    }

    public function getTextThayThe(): ?string
    {
        return $this->textThayThe;
    }

    public function setTextThayThe(?string $textThayThe): self
    {
        $this->textThayThe = $textThayThe;
        return $this;
    }

    public function getThuTu(): int
    {
        return $this->thuTu;
    }

    public function setThuTu(int $thuTu): self
    {
        $this->thuTu = $thuTu;
        return $this;
    }

    public function isHinhChinh(): bool
    {
        return $this->hinhChinh;
    }

    public function setHinhChinh(bool $hinhChinh): self
    {
        $this->hinhChinh = $hinhChinh;
        return $this;
    }

    public function getNgayTao(): \DateTime
    {
        return $this->ngayTao;
    }

    public function getFullUrl(): string
    {
        return '/images/' . $this->duongDanHinh;
    }
}
