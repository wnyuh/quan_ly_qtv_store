<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'san_pham')]
class SanPham
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'ma_san_pham', type: 'string', length: 100, unique: true)]
    private string $maSanPham;

    #[ORM\Column(name: 'ten', type: 'string', length: 255)]
    private string $ten;

    #[ORM\Column(name: 'duong_dan', type: 'string', length: 255, unique: true)]
    private string $duongDan;

    #[ORM\ManyToOne(targetEntity: ThuongHieu::class, inversedBy: 'sanPhams')]
    #[ORM\JoinColumn(name: 'thuong_hieu_id', referencedColumnName: 'id', nullable: false)]
    private ThuongHieu $thuongHieu;

    #[ORM\ManyToOne(targetEntity: DanhMuc::class, inversedBy: 'sanPhams')]
    #[ORM\JoinColumn(name: 'danh_muc_id', referencedColumnName: 'id', nullable: false)]
    private DanhMuc $danhMuc;

    #[ORM\Column(name: 'mo_ta_ngan', type: 'text', nullable: true)]
    private ?string $moTaNgan = null;

    #[ORM\Column(name: 'mo_ta', type: 'text', nullable: true)]
    private ?string $moTa = null;

    #[ORM\Column(name: 'gia', type: 'float')]
    private float $gia;

    #[ORM\Column(name: 'gia_so_sanh', type: 'float', nullable: true)]
    private ?float $giaSoSanh = null;

    #[ORM\Column(name: 'gia_goc', type: 'float', nullable: true)]
    private ?float $giaGoc = null;

    #[ORM\Column(name: 'trong_luong', type: 'float', nullable: true)]
    private ?float $trongLuong = null;

    #[ORM\Column(name: 'kich_thuoc', type: 'json', nullable: true)]
    private ?array $kichThuoc = null;

    #[ORM\Column(name: 'kich_hoat', type: 'boolean')]
    private bool $kichHoat = true;

    #[ORM\Column(name: 'noi_bat', type: 'boolean')]
    private bool $noiBat = false;

    #[ORM\Column(name: 'sp_moi', type: 'boolean')]
    private bool $spMoi = false;

    #[ORM\Column(name: 'tieu_de_meta', type: 'string', length: 255, nullable: true)]
    private ?string $tieuDeMeta = null;

    #[ORM\Column(name: 'mo_ta_meta', type: 'text', nullable: true)]
    private ?string $moTaMeta = null;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    #[ORM\Column(name: 'ngay_cap_nhat', type: 'datetime')]
    private \DateTime $ngayCapNhat;

    #[ORM\OneToOne(mappedBy: 'sanPham', targetEntity: ThongSoSanPham::class, cascade: ['persist', 'remove'])]
    private ?ThongSoSanPham $thongSo = null;

    #[ORM\OneToMany(mappedBy: 'sanPham', targetEntity: BienTheSanPham::class, cascade: ['persist', 'remove'])]
    private Collection $bienThes;

    #[ORM\OneToMany(mappedBy: 'sanPham', targetEntity: HinhAnhSanPham::class, cascade: ['persist', 'remove'])]
    private Collection $hinhAnhs;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        $this->ngayCapNhat = new \DateTime();
        $this->bienThes = new ArrayCollection();
        $this->hinhAnhs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getThuongHieu(): ThuongHieu
    {
        return $this->thuongHieu;
    }

    public function setThuongHieu(ThuongHieu $thuongHieu): self
    {
        $this->thuongHieu = $thuongHieu;
        return $this;
    }

    public function getDanhMuc(): DanhMuc
    {
        return $this->danhMuc;
    }

    public function setDanhMuc(DanhMuc $danhMuc): self
    {
        $this->danhMuc = $danhMuc;
        return $this;
    }

    public function getMoTaNgan(): ?string
    {
        return $this->moTaNgan;
    }

    public function setMoTaNgan(?string $moTaNgan): self
    {
        $this->moTaNgan = $moTaNgan;
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

    public function getGiaGoc(): ?float
    {
        return $this->giaGoc;
    }

    public function setGiaGoc(?float $giaGoc): self
    {
        $this->giaGoc = $giaGoc;
        return $this;
    }

    public function getGiaFormatted(): string
    {
        return number_format($this->gia, 0, ',', '.') . ' ₫';
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

    public function getKichThuoc(): ?array
    {
        return $this->kichThuoc;
    }

    public function setKichThuoc(?array $kichThuoc): self
    {
        $this->kichThuoc = $kichThuoc;
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

    public function isNoiBat(): bool
    {
        return $this->noiBat;
    }

    public function setNoiBat(bool $noiBat): self
    {
        $this->noiBat = $noiBat;
        return $this;
    }

    public function isSpMoi(): bool
    {
        return $this->spMoi;
    }

    public function setSpMoi(bool $spMoi): self
    {
        $this->spMoi = $spMoi;
        return $this;
    }

    public function getTieuDeMeta(): ?string
    {
        return $this->tieuDeMeta;
    }

    public function setTieuDeMeta(?string $tieuDeMeta): self
    {
        $this->tieuDeMeta = $tieuDeMeta;
        return $this;
    }

    public function getMoTaMeta(): ?string
    {
        return $this->moTaMeta;
    }

    public function setMoTaMeta(?string $moTaMeta): self
    {
        $this->moTaMeta = $moTaMeta;
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

    public function getThongSo(): ?ThongSoSanPham
    {
        return $this->thongSo;
    }

    public function setThongSo(?ThongSoSanPham $thongSo): self
    {
        $this->thongSo = $thongSo;
        if ($thongSo && $thongSo->getSanPham() !== $this) {
            $thongSo->setSanPham($this);
        }
        return $this;
    }

    public function getBienThes(): Collection
    {
        return $this->bienThes;
    }

    public function getHinhAnhs(): Collection
    {
        return $this->hinhAnhs;
    }

    public function getHinhAnhChinh(): ?HinhAnhSanPham
    {
        foreach ($this->hinhAnhs as $hinhAnh) {
            if ($hinhAnh->isHinhChinh()) {
                return $hinhAnh;
            }
        }
        return $this->hinhAnhs->first() ?: null;
    }
}
