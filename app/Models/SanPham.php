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
        if ($maSanPham == '') {
            return $this;
        }

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
        if ($duongDan == '') {
            $duongDan = $this->create_slug($this->ten);
        }
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


    /**
     * Converts a string into a URL-friendly slug.
     *
     * @param string $text The input string.
     * @return string The sanitized slug.
     */
    function create_slug($text)
    {
        // 1. Replace Vietnamese characters with their non-accented counterparts
        $vietnamese_map = [
            'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
            'đ' => 'd',
            'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
            'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
            'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
            'Á' => 'A', 'À' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A',
            'Ă' => 'A', 'Ằ' => 'A', 'Ắ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A',
            'Â' => 'A', 'Ầ' => 'A', 'Ấ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
            'Đ' => 'D',
            'É' => 'E', 'È' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E',
            'Ê' => 'E', 'Ề' => 'E', 'Ế' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O',
            'Ô' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O',
            'Ơ' => 'O', 'Ờ' => 'O', 'Ớ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U',
            'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
            'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y'
        ];
        $text = strtr($text, $vietnamese_map);

        // 2. Convert to lowercase
        $text = strtolower($text);

        // 3. Remove characters that are not letters, numbers, or hyphens
        $text = preg_replace('/[^a-z0-9-]+/', '-', $text);

        // 4. Remove duplicate hyphens
        $text = preg_replace('/-+/', '-', $text);

        // 5. Trim hyphens from the start and end of the string
        $text = trim($text, '-');

        return $text;
    }

}
