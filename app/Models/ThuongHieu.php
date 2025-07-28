<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'thuong_hieu')]
class ThuongHieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'ten', type: 'string', length: 255)]
    private string $ten;

    #[ORM\Column(name: 'duong_dan', type: 'string', length: 255, unique: true)]
    private string $duongDan;

    #[ORM\Column(name: 'logo', type: 'string', length: 500, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(name: 'mo_ta', type: 'text', nullable: true)]
    private ?string $moTa = null;

    #[ORM\Column(name: 'website', type: 'string', length: 500, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(name: 'kich_hoat', type: 'boolean')]
    private bool $kichHoat = true;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    #[ORM\Column(name: 'ngay_cap_nhat', type: 'datetime')]
    private \DateTime $ngayCapNhat;

    #[ORM\OneToMany(mappedBy: 'thuongHieu', targetEntity: SanPham::class)]
    private Collection $sanPhams;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        $this->ngayCapNhat = new \DateTime();
        $this->sanPhams = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getDuongDan(): string
    {
        return $this->duongDan;
    }

    public function setDuongDan(string $duongDan): self
    {
        $this->duongDan = $duongDan;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;
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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;
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

    public function getSanPhams(): Collection
    {
        return $this->sanPhams;
    }
}
