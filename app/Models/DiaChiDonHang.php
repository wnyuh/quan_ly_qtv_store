<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'dia_chi_don_hang')]
class DiaChiDonHang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: DonHang::class, inversedBy: 'diaChis')]
    #[ORM\JoinColumn(name: 'don_hang_id', referencedColumnName: 'id', nullable: false)]
    private DonHang $donHang;

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

    #[ORM\Column(name: 'huyen_quan', type: 'string', length: 100, nullable: true)]
    private ?string $huyenQuan = null;

    #[ORM\Column(name: 'xa_phuong', type: 'string', length: 100, nullable: true)]
    private ?string $xaPhuong = null;

    #[ORM\Column(name: 'ma_buu_dien', type: 'string', length: 20)]
    private string $maBuuDien;

    #[ORM\Column(name: 'quoc_gia', type: 'string', length: 100)]
    private string $quocGia = 'Việt Nam';

    #[ORM\Column(name: 'so_dien_thoai', type: 'string', length: 20, nullable: true)]
    private ?string $soDienThoai = null;

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
    public function getLoai(): string { return $this->loai; }
    public function setLoai(string $loai): self { $this->loai = $loai; return $this; }
    public function getHo(): string { return $this->ho; }
    public function setHo(string $ho): self { $this->ho = $ho; return $this; }
    public function getTen(): string { return $this->ten; }
    public function setTen(string $ten): self { $this->ten = $ten; return $this; }
    public function getHoTen(): string { return $this->ho . ' ' . $this->ten; }
    public function getCongTy(): ?string { return $this->congTy; }
    public function setCongTy(?string $congTy): self { $this->congTy = $congTy; return $this; }
    public function getDiaChi1(): string { return $this->diaChi1; }
    public function setDiaChi1(string $diaChi1): self { $this->diaChi1 = $diaChi1; return $this; }
    public function getDiaChi2(): ?string { return $this->diaChi2; }
    public function setDiaChi2(?string $diaChi2): self { $this->diaChi2 = $diaChi2; return $this; }
    public function getThanhPho(): string { return $this->thanhPho; }
    public function setThanhPho(string $thanhPho): self { $this->thanhPho = $thanhPho; return $this; }
    public function getTinhThanh(): string { return $this->tinhThanh; }
    public function setTinhThanh(string $tinhThanh): self { $this->tinhThanh = $tinhThanh; return $this; }
    public function getMaBuuDien(): string { return $this->maBuuDien; }
    public function setMaBuuDien(string $maBuuDien): self { $this->maBuuDien = $maBuuDien; return $this; }
    public function getQuocGia(): string { return $this->quocGia; }
    public function setQuocGia(string $quocGia): self { $this->quocGia = $quocGia; return $this; }
    public function getSoDienThoai(): ?string { return $this->soDienThoai; }
    public function setSoDienThoai(?string $soDienThoai): self { $this->soDienThoai = $soDienThoai; return $this; }
    public function getHuyenQuan(): ?string { return $this->huyenQuan; }
    public function setHuyenQuan(?string $huyenQuan): self { $this->huyenQuan = $huyenQuan; return $this; }
    public function getXaPhuong(): ?string { return $this->xaPhuong; }
    public function setXaPhuong(?string $xaPhuong): self { $this->xaPhuong = $xaPhuong; return $this; }
    public function getNgayTao(): \DateTime { return $this->ngayTao; }
    
    public function getDiaChiDayDu(): string
    {
        $diaChi = $this->diaChi1;
        if ($this->diaChi2) {
            $diaChi .= ', ' . $this->diaChi2;
        }
        
        // Use hierarchical address if available
        if ($this->xaPhuong) {
            $diaChi .= ', ' . $this->xaPhuong;
        }
        if ($this->huyenQuan) {
            $diaChi .= ', ' . $this->huyenQuan;
        }
        $diaChi .= ', ' . $this->tinhThanh;
        
        // Add postal code if available
        if ($this->maBuuDien) {
            $diaChi .= ' ' . $this->maBuuDien;
        }
        
        return $diaChi;
    }
}
