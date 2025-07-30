<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'nguoi_dung')]
class NguoiDung
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(name: 'mat_khau', type: 'string', length: 255)]
    private string $matKhau;

    #[ORM\Column(name: 'ho', type: 'string', length: 100)]
    private string $ho;

    #[ORM\Column(name: 'ten', type: 'string', length: 100)]
    private string $ten;

    #[ORM\Column(name: 'so_dien_thoai', type: 'string', length: 20, nullable: true)]
    private ?string $soDienThoai = null;

    #[ORM\Column(name: 'ngay_sinh', type: 'date', nullable: true)]
    private ?\DateTime $ngaySinh = null;

    #[ORM\Column(name: 'email_xac_thuc', type: 'datetime', nullable: true)]
    private ?\DateTime $emailXacThuc = null;

    #[ORM\Column(name: 'ma_xac_thuc', type: 'string', length: 255, nullable: true)]
    private ?string $maXacThuc = null;

    #[ORM\Column(name: 'xac_thuc_het_han', type: 'datetime', nullable: true)]
    private ?\DateTime $xacThucHetHan = null;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    #[ORM\Column(name: 'ngay_cap_nhat', type: 'datetime')]
    private \DateTime $ngayCapNhat;

    #[ORM\OneToMany(mappedBy: 'nguoiDung', targetEntity: DiaChiNguoiDung::class)]
    private Collection $diaChis;

    #[ORM\OneToMany(mappedBy: 'nguoiDung', targetEntity: GioHang::class)]
    private Collection $gioHangs;

    #[ORM\OneToMany(mappedBy: 'nguoiDung', targetEntity: DonHang::class)]
    private Collection $donHangs;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        $this->ngayCapNhat = new \DateTime();
        $this->diaChis = new ArrayCollection();
        $this->gioHangs = new ArrayCollection();
        $this->donHangs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMatKhau(): string
    {
        return $this->matKhau;
    }

    // public function setMatKhau(string $matKhau): self
    // {
    //     $this->matKhau = password_hash($matKhau, PASSWORD_DEFAULT);
    //     return $this;
    // }

    public function setMatKhau(string $matKhau): self
    {
        // Nếu string không phải hash, thì hash
        if (password_get_info($matKhau)['algo'] === 0) {
            $this->matKhau = password_hash($matKhau, PASSWORD_DEFAULT);
        } else {
            $this->matKhau = $matKhau; // Đã là hash rồi
        }
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

    public function getSoDienThoai(): ?string
    {
        return $this->soDienThoai;
    }

    public function setSoDienThoai(?string $soDienThoai): self
    {
        $this->soDienThoai = $soDienThoai;
        return $this;
    }

    public function getNgaySinh(): ?\DateTime
    {
        return $this->ngaySinh;
    }

    public function setNgaySinh(?\DateTime $ngaySinh): self
    {
        $this->ngaySinh = $ngaySinh;
        return $this;
    }

    public function getEmailXacThuc(): ?\DateTime
    {
        return $this->emailXacThuc;
    }

    public function setEmailXacThuc(?\DateTime $emailXacThuc): self
    {
        $this->emailXacThuc = $emailXacThuc;
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

    public function getDiaChis(): Collection
    {
        return $this->diaChis;
    }

    public function getGioHangs(): Collection
    {
        return $this->gioHangs;
    }

    public function getDonHangs(): Collection
    {
        return $this->donHangs;
    }

    public function getMaXacThuc(): ?string
    {
        return $this->maXacThuc;
    }

    public function setMaXacThuc(?string $maXacThuc): self
    {
        $this->maXacThuc = $maXacThuc;
        return $this;
    }

    public function getXacThucHetHan(): ?\DateTime
    {
        return $this->xacThucHetHan;
    }

    public function setXacThucHetHan(?\DateTime $xacThucHetHan): self
    {
        $this->xacThucHetHan = $xacThucHetHan;
        return $this;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailXacThuc !== null;
    }

    public function isConfirmationCodeValid(): bool
    {
        if ($this->maXacThuc === null || $this->xacThucHetHan === null) {
            return false;
        }
        
        return new \DateTime() < $this->xacThucHetHan;
    }

    public function generateConfirmationCode(): string
    {
        $code = bin2hex(random_bytes(32));
        $this->maXacThuc = $code;
        $this->xacThucHetHan = new \DateTime('+24 hours');
        return $code;
    }

    public function confirmEmail(): self
    {
        $this->emailXacThuc = new \DateTime();
        $this->maXacThuc = null;
        $this->xacThucHetHan = null;
        return $this;
    }

    // Hàm kiểm tra mật khẩu nhập vào có khớp mật khẩu hiện tại hay không
    public function kiemTraMatKhau(string $matKhau): bool
    {
        return password_verify($matKhau, $this->matKhau);
    }
}
