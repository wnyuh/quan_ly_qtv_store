<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'theo_doi_don_hang')]
class TheoDoiDonHang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(inversedBy: 'theoDoi', targetEntity: DonHang::class)]
    #[ORM\JoinColumn(name: 'don_hang_id', referencedColumnName: 'id', nullable: false)]
    private DonHang $donHang;

    #[ORM\Column(name: 'don_vi_van_chuyen', type: 'string', length: 100, nullable: true)]
    private ?string $donViVanChuyen = null;

    #[ORM\Column(name: 'ma_theo_doi', type: 'string', length: 255, nullable: true)]
    private ?string $maTheoDoi = null;

    #[ORM\Column(name: 'link_theo_doi', type: 'string', length: 500, nullable: true)]
    private ?string $linkTheoDoi = null;

    #[ORM\Column(name: 'trang_thai_hien_tai', type: 'string', length: 100, nullable: true)]
    private ?string $trangThaiHienTai = null;

    #[ORM\Column(name: 'ngay_giao_du_kien', type: 'datetime', nullable: true)]
    private ?\DateTime $ngayGiaoDuKien = null;

    #[ORM\Column(name: 'su_kien_theo_doi', type: 'json', nullable: true)]
    private ?array $suKienTheoDoi = null;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    #[ORM\Column(name: 'ngay_cap_nhat', type: 'datetime')]
    private \DateTime $ngayCapNhat;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        $this->ngayCapNhat = new \DateTime();
    }

    // Getters and setters
    public function getId(): int { return $this->id; }
    public function getDonHang(): DonHang { return $this->donHang; }
    public function setDonHang(DonHang $donHang): self { $this->donHang = $donHang; return $this; }
    public function getDonViVanChuyen(): ?string { return $this->donViVanChuyen; }
    public function setDonViVanChuyen(?string $donViVanChuyen): self { $this->donViVanChuyen = $donViVanChuyen; return $this; }
    public function getMaTheoDoi(): ?string { return $this->maTheoDoi; }
    public function setMaTheoDoi(?string $maTheoDoi): self { $this->maTheoDoi = $maTheoDoi; return $this; }
    public function getLinkTheoDoi(): ?string { return $this->linkTheoDoi; }
    public function setLinkTheoDoi(?string $linkTheoDoi): self { $this->linkTheoDoi = $linkTheoDoi; return $this; }
    public function getTrangThaiHienTai(): ?string { return $this->trangThaiHienTai; }
    public function setTrangThaiHienTai(?string $trangThaiHienTai): self { $this->trangThaiHienTai = $trangThaiHienTai; $this->setNgayCapNhat(); return $this; }
    public function getNgayGiaoDuKien(): ?\DateTime { return $this->ngayGiaoDuKien; }
    public function setNgayGiaoDuKien(?\DateTime $ngayGiaoDuKien): self { $this->ngayGiaoDuKien = $ngayGiaoDuKien; return $this; }
    public function getSuKienTheoDoi(): ?array { return $this->suKienTheoDoi; }
    public function setSuKienTheoDoi(?array $suKienTheoDoi): self { $this->suKienTheoDoi = $suKienTheoDoi; return $this; }
    public function getNgayTao(): \DateTime { return $this->ngayTao; }
    public function getNgayCapNhat(): \DateTime { return $this->ngayCapNhat; }
    public function setNgayCapNhat(): self { $this->ngayCapNhat = new \DateTime(); return $this; }
    
    public function addTrackingEvent(string $trangThai, string $moTa, ?\DateTime $thoiGian = null): self
    {
        $suKien = $this->suKienTheoDoi ?? [];
        $suKien[] = [
            'trang_thai' => $trangThai,
            'mo_ta' => $moTa,
            'thoi_gian' => ($thoiGian ?? new \DateTime())->format('Y-m-d H:i:s')
        ];
        $this->suKienTheoDoi = $suKien;
        $this->setNgayCapNhat();
        return $this;
    }
}
