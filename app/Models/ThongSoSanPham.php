<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'thong_so_san_pham')]
class ThongSoSanPham
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\OneToOne(inversedBy: 'thongSo', targetEntity: SanPham::class)]
    #[ORM\JoinColumn(name: 'san_pham_id', referencedColumnName: 'id', nullable: false)]
    private SanPham $sanPham;

    #[ORM\Column(name: 'kich_thuoc_man_hinh', type: 'string', length: 50, nullable: true)]
    private ?string $kichThuocManHinh = null;

    #[ORM\Column(name: 'do_phan_giai', type: 'string', length: 100, nullable: true)]
    private ?string $doPhanGiai = null;

    #[ORM\Column(name: 'loai_man_hinh', type: 'string', length: 100, nullable: true)]
    private ?string $loaiManHinh = null;

    #[ORM\Column(name: 'he_dieu_hanh', type: 'string', length: 100, nullable: true)]
    private ?string $heDieuHanh = null;

    #[ORM\Column(name: 'bo_xu_ly', type: 'string', length: 255, nullable: true)]
    private ?string $boXuLy = null;

    #[ORM\Column(name: 'ram', type: 'string', length: 50, nullable: true)]
    private ?string $ram = null;

    #[ORM\Column(name: 'bo_nho', type: 'string', length: 50, nullable: true)]
    private ?string $boNho = null;

    #[ORM\Column(name: 'mo_rong_bo_nho', type: 'boolean')]
    private bool $moRongBoNho = false;

    #[ORM\Column(name: 'camera_sau', type: 'string', length: 255, nullable: true)]
    private ?string $cameraSau = null;

    #[ORM\Column(name: 'camera_truoc', type: 'string', length: 255, nullable: true)]
    private ?string $cameraTruoc = null;

    #[ORM\Column(name: 'dung_luong_pin', type: 'string', length: 50, nullable: true)]
    private ?string $dungLuongPin = null;

    #[ORM\Column(name: 'loai_sac', type: 'string', length: 100, nullable: true)]
    private ?string $loaiSac = null;

    #[ORM\Column(name: 'ket_noi', type: 'json', nullable: true)]
    private ?array $ketNoi = null;

    #[ORM\Column(name: 'mau_sac_co_san', type: 'json', nullable: true)]
    private ?array $mauSacCoSan = null;

    #[ORM\Column(name: 'thoi_gian_bao_hanh', type: 'string', length: 100, nullable: true)]
    private ?string $thoiGianBaoHanh = null;

    #[ORM\Column(name: 'chong_nuoc', type: 'string', length: 50, nullable: true)]
    private ?string $chongNuoc = null;

    #[ORM\Column(name: 'cam_bien_van_tay', type: 'boolean')]
    private bool $camBienVanTay = false;

    #[ORM\Column(name: 'mo_khoa_khuon_mat', type: 'boolean')]
    private bool $moKhoaKhuonMat = false;

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

    public function getSanPham(): SanPham
    {
        return $this->sanPham;
    }

    public function setSanPham(SanPham $sanPham): self
    {
        $this->sanPham = $sanPham;
        return $this;
    }

    public function getKichThuocManHinh(): ?string
    {
        return $this->kichThuocManHinh;
    }

    public function setKichThuocManHinh(?string $kichThuocManHinh): self
    {
        $this->kichThuocManHinh = $kichThuocManHinh;
        return $this;
    }

    public function getDoPhanGiai(): ?string
    {
        return $this->doPhanGiai;
    }

    public function setDoPhanGiai(?string $doPhanGiai): self
    {
        $this->doPhanGiai = $doPhanGiai;
        return $this;
    }

    public function getLoaiManHinh(): ?string
    {
        return $this->loaiManHinh;
    }

    public function setLoaiManHinh(?string $loaiManHinh): self
    {
        $this->loaiManHinh = $loaiManHinh;
        return $this;
    }

    public function getHeDieuHanh(): ?string
    {
        return $this->heDieuHanh;
    }

    public function setHeDieuHanh(?string $heDieuHanh): self
    {
        $this->heDieuHanh = $heDieuHanh;
        return $this;
    }

    public function getBoXuLy(): ?string
    {
        return $this->boXuLy;
    }

    public function setBoXuLy(?string $boXuLy): self
    {
        $this->boXuLy = $boXuLy;
        return $this;
    }

    public function getRam(): ?string
    {
        return $this->ram;
    }

    public function setRam(?string $ram): self
    {
        $this->ram = $ram;
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

    public function isMoRongBoNho(): bool
    {
        return $this->moRongBoNho;
    }

    public function setMoRongBoNho(bool $moRongBoNho): self
    {
        $this->moRongBoNho = $moRongBoNho;
        return $this;
    }

    public function getCameraSau(): ?string
    {
        return $this->cameraSau;
    }

    public function setCameraSau(?string $cameraSau): self
    {
        $this->cameraSau = $cameraSau;
        return $this;
    }

    public function getCameraTruoc(): ?string
    {
        return $this->cameraTruoc;
    }

    public function setCameraTruoc(?string $cameraTruoc): self
    {
        $this->cameraTruoc = $cameraTruoc;
        return $this;
    }

    public function getDungLuongPin(): ?string
    {
        return $this->dungLuongPin;
    }

    public function setDungLuongPin(?string $dungLuongPin): self
    {
        $this->dungLuongPin = $dungLuongPin;
        return $this;
    }

    public function getLoaiSac(): ?string
    {
        return $this->loaiSac;
    }

    public function setLoaiSac(?string $loaiSac): self
    {
        $this->loaiSac = $loaiSac;
        return $this;
    }

    public function getKetNoi(): ?array
    {
        return $this->ketNoi;
    }

    public function setKetNoi(?array $ketNoi): self
    {
        $this->ketNoi = $ketNoi;
        return $this;
    }

    public function getMauSacCoSan(): ?array
    {
        return $this->mauSacCoSan;
    }

    public function setMauSacCoSan(?array $mauSacCoSan): self
    {
        $this->mauSacCoSan = $mauSacCoSan;
        return $this;
    }

    public function getThoiGianBaoHanh(): ?string
    {
        return $this->thoiGianBaoHanh;
    }

    public function setThoiGianBaoHanh(?string $thoiGianBaoHanh): self
    {
        $this->thoiGianBaoHanh = $thoiGianBaoHanh;
        return $this;
    }

    public function getChongNuoc(): ?string
    {
        return $this->chongNuoc;
    }

    public function setChongNuoc(?string $chongNuoc): self
    {
        $this->chongNuoc = $chongNuoc;
        return $this;
    }

    public function isCamBienVanTay(): bool
    {
        return $this->camBienVanTay;
    }

    public function setCamBienVanTay(bool $camBienVanTay): self
    {
        $this->camBienVanTay = $camBienVanTay;
        return $this;
    }

    public function isMoKhoaKhuonMat(): bool
    {
        return $this->moKhoaKhuonMat;
    }

    public function setMoKhoaKhuonMat(bool $moKhoaKhuonMat): self
    {
        $this->moKhoaKhuonMat = $moKhoaKhuonMat;
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
}
