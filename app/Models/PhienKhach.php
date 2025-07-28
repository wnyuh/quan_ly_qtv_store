<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'phien_khach')]
class PhienKhach
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'ma_phien', type: 'string', length: 255, unique: true)]
    private string $maPhien;

    #[ORM\Column(name: 'du_lieu_gio_hang', type: 'json', nullable: true)]
    private ?array $duLieuGioHang = null;

    #[ORM\Column(name: 'thong_tin_khach', type: 'json', nullable: true)]
    private ?array $thongTinKhach = null;

    #[ORM\Column(name: 'het_han', type: 'datetime')]
    private \DateTime $hetHan;

    #[ORM\Column(name: 'ngay_tao', type: 'datetime')]
    private \DateTime $ngayTao;

    public function __construct()
    {
        $this->ngayTao = new \DateTime();
        // Phiên khách có thời hạn 24 giờ
        $this->hetHan = new \DateTime('+1 day');
        $this->maPhien = $this->generateSessionId();
    }

    private function generateSessionId(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMaPhien(): string
    {
        return $this->maPhien;
    }

    public function setMaPhien(string $maPhien): self
    {
        $this->maPhien = $maPhien;
        return $this;
    }

    public function getDuLieuGioHang(): ?array
    {
        return $this->duLieuGioHang;
    }

    public function setDuLieuGioHang(?array $duLieuGioHang): self
    {
        $this->duLieuGioHang = $duLieuGioHang;
        return $this;
    }

    public function getThongTinKhach(): ?array
    {
        return $this->thongTinKhach;
    }

    public function setThongTinKhach(?array $thongTinKhach): self
    {
        $this->thongTinKhach = $thongTinKhach;
        return $this;
    }

    public function getHetHan(): \DateTime
    {
        return $this->hetHan;
    }

    public function setHetHan(\DateTime $hetHan): self
    {
        $this->hetHan = $hetHan;
        return $this;
    }

    public function getNgayTao(): \DateTime
    {
        return $this->ngayTao;
    }

    public function isExpired(): bool
    {
        return new \DateTime() > $this->hetHan;
    }

    public function extendSession(int $hours = 24): self
    {
        $this->hetHan = new \DateTime("+{$hours} hours");
        return $this;
    }

    public function addToCart(int $bienTheId, int $soLuong = 1): self
    {
        $cart = $this->duLieuGioHang ?? [];
        
        if (isset($cart[$bienTheId])) {
            $cart[$bienTheId]['so_luong'] += $soLuong;
        } else {
            $cart[$bienTheId] = [
                'bien_the_id' => $bienTheId,
                'so_luong' => $soLuong,
                'ngay_them' => (new \DateTime())->format('Y-m-d H:i:s')
            ];
        }
        
        $this->duLieuGioHang = $cart;
        return $this;
    }

    public function removeFromCart(int $bienTheId): self
    {
        $cart = $this->duLieuGioHang ?? [];
        unset($cart[$bienTheId]);
        $this->duLieuGioHang = $cart;
        return $this;
    }

    public function updateCartQuantity(int $bienTheId, int $soLuong): self
    {
        $cart = $this->duLieuGioHang ?? [];
        
        if (isset($cart[$bienTheId])) {
            if ($soLuong <= 0) {
                unset($cart[$bienTheId]);
            } else {
                $cart[$bienTheId]['so_luong'] = $soLuong;
            }
        }
        
        $this->duLieuGioHang = $cart;
        return $this;
    }

    public function getCartItemCount(): int
    {
        if (!$this->duLieuGioHang) {
            return 0;
        }
        
        return array_sum(array_column($this->duLieuGioHang, 'so_luong'));
    }
}
