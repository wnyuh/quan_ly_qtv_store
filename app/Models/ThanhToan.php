<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'thanh_toan')]
class ThanhToan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: DonHang::class, inversedBy: 'thanhToans')]
    #[ORM\JoinColumn(name: 'don_hang_id', referencedColumnName: 'id', nullable: false)]
    private DonHang $donHang;

    #[ORM\Column(name: 'phuong_thuc_thanh_toan', type: 'string', length: 50)]
    private string $phuongThucThanhToan;

    #[ORM\Column(name: 'cong_thanh_toan', type: 'string', length: 50)]
    private string $congThanhToan;

    #[ORM\Column(name: 'ma_giao_dich', type: 'string', length: 255, nullable: true)]
    private ?string $maGiaoDich = null;

    #[ORM\Column(name: 'phan_hoi_cong', type: 'json', nullable: true)]
    private ?array $phanHoiCong = null;

    #[ORM\Column(name: 'so_tien', type: 'float')]
    private float $soTien;

    #[ORM\Column(name: 'tien_te', type: 'string', length: 3)]
    private string $tienTe;

    #[ORM\Column(name: 'trang_thai', type: 'string', length: 20)]
    private string $trangThai = 'cho_xu_ly';

    #[ORM\Column(name: 'ngay_xu_ly', type: 'datetime', nullable: true)]
    private ?\DateTime $ngayXuLy = null;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
    }

    // Getters and setters
    public function getId(): int { return $this->id; }
    public function getDonHang(): DonHang { return $this->donHang; }
    public function setDonHang(DonHang $donHang): self { $this->donHang = $donHang; return $this; }
    public function getPhuongThucThanhToan(): string { return $this->phuongThucThanhToan; }
    public function setPhuongThucThanhToan(string $phuongThucThanhToan): self { $this->phuongThucThanhToan = $phuongThucThanhToan; return $this; }
    public function getCongThanhToan(): string { return $this->congThanhToan; }
    public function setCongThanhToan(string $congThanhToan): self { $this->congThanhToan = $congThanhToan; return $this; }
    public function getMaGiaoDich(): ?string { return $this->maGiaoDich; }
    public function setMaGiaoDich(?string $maGiaoDich): self { $this->maGiaoDich = $maGiaoDich; return $this; }
    public function getPhanHoiCong(): ?array { return $this->phanHoiCong; }
    public function setPhanHoiCong(?array $phanHoiCong): self { $this->phanHoiCong = $phanHoiCong; return $this; }
    public function getSoTien(): float { return $this->soTien; }
    public function setSoTien(float $soTien): self { $this->soTien = $soTien; return $this; }
    public function getTienTe(): string { return $this->tienTe; }
    public function setTienTe(string $tienTe): self { $this->tienTe = $tienTe; return $this; }
    public function getTrangThai(): string { return $this->trangThai; }
    public function setTrangThai(string $trangThai): self { $this->trangThai = $trangThai; if ($trangThai === 'hoan_thanh') $this->ngayXuLy = new \DateTime(); return $this; }
    public function getNgayXuLy(): ?\DateTime { return $this->ngayXuLy; }
    public function setNgayXuLy(?\DateTime $ngayXuLy): self { $this->ngayXuLy = $ngayXuLy; return $this; }
    public function getNgayTao(): \DateTime { return $this->ngayTao; }
    
    public function getSoTienFormatted(): string
    {
        return number_format($this->soTien, 0, ',', '.') . ' ₫';
    }
}
