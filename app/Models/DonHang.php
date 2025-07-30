<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'don_hang')]
class DonHang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'ma_don_hang', type: 'string', length: 50, unique: true)]
    private string $maDonHang;

    #[ORM\ManyToOne(targetEntity: NguoiDung::class, inversedBy: 'donHangs')]
    #[ORM\JoinColumn(name: 'nguoi_dung_id', referencedColumnName: 'id', nullable: true)]
    private ?NguoiDung $nguoiDung = null;

    #[ORM\Column(name: 'email_khach', type: 'string', length: 255, nullable: true)]
    private ?string $emailKhach = null;

    #[ORM\Column(name: 'trang_thai', type: 'string', length: 20)]
    private string $trangThai = 'cho_xu_ly';

    #[ORM\Column(name: 'trang_thai_thanh_toan', type: 'string', length: 20)]
    private string $trangThaiThanhToan = 'cho_thanh_toan';

    #[ORM\Column(name: 'tong_phu', type: 'float')]
    private float $tongPhu;

    #[ORM\Column(name: 'tien_thue', type: 'float')]
    private float $tienThue = 0;

    #[ORM\Column(name: 'phi_van_chuyen', type: 'float')]
    private float $phiVanChuyen = 0;

    #[ORM\Column(name: 'tien_giam_gia', type: 'float')]
    private float $tienGiamGia = 0;

    #[ORM\ManyToOne(targetEntity: MaGiamGia::class)]
    #[ORM\JoinColumn(name: 'ma_giam_gia_id', referencedColumnName: 'id', nullable: true)]
    private ?MaGiamGia $maGiamGia = null;

    #[ORM\Column(name: 'ma_giam_gia_code', type: 'string', length: 50, nullable: true)]
    private ?string $maGiamGiaCode = null;

    #[ORM\Column(name: 'tong_tien', type: 'float')]
    private float $tongTien;

    #[ORM\Column(name: 'tien_te', type: 'string', length: 3)]
    private string $tienTe = 'VND';

    #[ORM\Column(name: 'ghi_chu', type: 'text', nullable: true)]
    private ?string $ghiChu = null;

    #[ORM\Column(name: 'ngay_giao', type: 'datetime', nullable: true)]
    private ?\DateTime $ngayGiao = null;

    #[ORM\Column(name: 'ngay_nhan', type: 'datetime', nullable: true)]
    private ?\DateTime $ngayNhan = null;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    #[ORM\Column(name: 'ngay_cap_nhat', type: 'datetime')]
    private \DateTime $ngayCapNhat;

    #[ORM\OneToMany(mappedBy: 'donHang', targetEntity: ChiTietDonHang::class, cascade: ['persist', 'remove'])]
    private Collection $chiTiets;

    #[ORM\OneToMany(mappedBy: 'donHang', targetEntity: DiaChiDonHang::class, cascade: ['persist', 'remove'])]
    private Collection $diaChis;

    #[ORM\OneToMany(mappedBy: 'donHang', targetEntity: ThanhToan::class, cascade: ['persist', 'remove'])]
    private Collection $thanhToans;

    #[ORM\OneToOne(mappedBy: 'donHang', targetEntity: TheoDoiDonHang::class, cascade: ['persist', 'remove'])]
    private ?TheoDoiDonHang $theoDoi = null;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        $this->ngayCapNhat = new \DateTime();
        $this->maDonHang = $this->generateOrderNumber();
        $this->chiTiets = new ArrayCollection();
        $this->diaChis = new ArrayCollection();
        $this->thanhToans = new ArrayCollection();
    }

    private function generateOrderNumber(): string
    {
        // current timestamp with milliseconds
        $date = (new \DateTime())->format('YmdHisv');
        // secure 4‑digit random
        $random = random_int(1000, 9999);
        return 'DH' . $date . $random;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMaDonHang(): string
    {
        return $this->maDonHang;
    }

    public function setMaDonHang(string $maDonHang): self
    {
        $this->maDonHang = $maDonHang;
        return $this;
    }

    public function getNguoiDung(): ?NguoiDung
    {
        return $this->nguoiDung;
    }

    public function setNguoiDung(?NguoiDung $nguoiDung): self
    {
        $this->nguoiDung = $nguoiDung;
        return $this;
    }

    public function getEmailKhach(): ?string
    {
        return $this->emailKhach;
    }

    public function setEmailKhach(?string $emailKhach): self
    {
        $this->emailKhach = $emailKhach;
        return $this;
    }

    public function getTrangThai(): string
    {
        return $this->trangThai;
    }

    public function setTrangThai(string $trangThai): self
    {
        $this->trangThai = $trangThai;
        $this->setNgayCapNhat();
        return $this;
    }

    public function getTrangThaiThanhToan(): string
    {
        return $this->trangThaiThanhToan;
    }

    public function setTrangThaiThanhToan(string $trangThaiThanhToan): self
    {
        $this->trangThaiThanhToan = $trangThaiThanhToan;
        $this->setNgayCapNhat();
        return $this;
    }

    public function getTongPhu(): float
    {
        return $this->tongPhu;
    }

    public function setTongPhu(float $tongPhu): self
    {
        $this->tongPhu = $tongPhu;
        $this->calculateTongTien();
        return $this;
    }

    public function getTienThue(): float
    {
        return $this->tienThue;
    }

    public function setTienThue(float $tienThue): self
    {
        $this->tienThue = $tienThue;
        $this->calculateTongTien();
        return $this;
    }

    public function getPhiVanChuyen(): float
    {
        return $this->phiVanChuyen;
    }

    public function setPhiVanChuyen(float $phiVanChuyen): self
    {
        $this->phiVanChuyen = $phiVanChuyen;
        $this->calculateTongTien();
        return $this;
    }

    public function getTienGiamGia(): float
    {
        return $this->tienGiamGia;
    }

    public function setTienGiamGia(float $tienGiamGia): self
    {
        $this->tienGiamGia = $tienGiamGia;
        $this->calculateTongTien();
        return $this;
    }

    public function getTongTien(): float
    {
        return $this->tongTien;
    }

    private function calculateTongTien(): void
    {
        $this->tongTien = $this->tongPhu + $this->tienThue + $this->phiVanChuyen - $this->tienGiamGia;
    }

    public function getTienTe(): string
    {
        return $this->tienTe;
    }

    public function setTienTe(string $tienTe): self
    {
        $this->tienTe = $tienTe;
        return $this;
    }

    public function getGhiChu(): ?string
    {
        return $this->ghiChu;
    }

    public function setGhiChu(?string $ghiChu): self
    {
        $this->ghiChu = $ghiChu;
        return $this;
    }

    public function getNgayGiao(): ?\DateTime
    {
        return $this->ngayGiao;
    }

    public function setNgayGiao(?\DateTime $ngayGiao): self
    {
        $this->ngayGiao = $ngayGiao;
        return $this;
    }

    public function getNgayNhan(): ?\DateTime
    {
        return $this->ngayNhan;
    }

    public function setNgayNhan(?\DateTime $ngayNhan): self
    {
        $this->ngayNhan = $ngayNhan;
        return $this;
    }

    public function setNgayTao(?\DateTime $ngayTao): self
    {
        $this->ngayTao = $ngayTao;
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

    public function getChiTiets(): Collection
    {
        return $this->chiTiets;
    }

    public function getDiaChis(): Collection
    {
        return $this->diaChis;
    }

    public function getThanhToans(): Collection
    {
        return $this->thanhToans;
    }

    public function getTheoDoi(): ?TheoDoiDonHang
    {
        return $this->theoDoi;
    }

    public function setTheoDoi(?TheoDoiDonHang $theoDoi): self
    {
        $this->theoDoi = $theoDoi;
        if ($theoDoi && $theoDoi->getDonHang() !== $this) {
            $theoDoi->setDonHang($this);
        }
        return $this;
    }

    public function getTongTienFormatted(): string
    {
        return number_format($this->tongTien, 0, ',', '.') . ' ₫';
    }

    public function isGuestOrder(): bool
    {
        return $this->nguoiDung === null;
    }

    public function getDiaChiGiaoHang(): ?DiaChiDonHang
    {
        foreach ($this->diaChis as $diaChi) {
            if ($diaChi->getLoai() === 'giao_hang') {
                return $diaChi;
            }
        }
        return null;
    }

    public function getDiaChiThanhToan(): ?DiaChiDonHang
    {
        foreach ($this->diaChis as $diaChi) {
            if ($diaChi->getLoai() === 'thanh_toan') {
                return $diaChi;
            }
        }
        return null;
    }

    public function getMaGiamGia(): ?MaGiamGia
    {
        return $this->maGiamGia;
    }

    public function setMaGiamGia(?MaGiamGia $maGiamGia): self
    {
        $this->maGiamGia = $maGiamGia;
        if ($maGiamGia) {
            $this->maGiamGiaCode = $maGiamGia->getMaGiamGia();
        } else {
            $this->maGiamGiaCode = null;
        }
        return $this;
    }

    public function getMaGiamGiaCode(): ?string
    {
        return $this->maGiamGiaCode;
    }

    public function setMaGiamGiaCode(?string $maGiamGiaCode): self
    {
        $this->maGiamGiaCode = $maGiamGiaCode;
        return $this;
    }

    public function apDungMaGiamGia(MaGiamGia $maGiamGia): bool
    {
        if (!$maGiamGia->coTheSuDung()) {
            return false;
        }

        $tienGiam = $maGiamGia->tinhTienGiam($this->tongPhu);
        if ($tienGiam <= 0) {
            return false;
        }

        $this->setMaGiamGia($maGiamGia);
        $this->setTienGiamGia($tienGiam);
        $maGiamGia->tangSoLuongSuDung();
        
        return true;
    }

    public function boMaGiamGia(): self
    {
        $this->maGiamGia = null;
        $this->maGiamGiaCode = null;
        $this->setTienGiamGia(0);
        return $this;
    }

    public function coMaGiamGia(): bool
    {
        return $this->maGiamGia !== null && $this->tienGiamGia > 0;
    }

   
}
