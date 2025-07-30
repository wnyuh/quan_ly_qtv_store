<?php

namespace App\Controllers;

use App\Models\DonHang;
use Doctrine\ORM\EntityManager;

class DonHangController
{
    private EntityManager $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
    }

    public function thanhCong(string $orderCode)
    {
        // Find the order by order code
        $donHang = $this->em->getRepository(DonHang::class)->findOneBy([
            'maDonHang' => $orderCode
        ]);

        if (!$donHang) {
            // Order not found, redirect to home or show error
            header('Location: /');
            exit;
        }

        // Check if user has access to this order
        $hasAccess = false;
        
        if (isset($_SESSION['user_id'])) {
            // Logged in user - check if order belongs to them
            $hasAccess = $donHang->getNguoiDung() && $donHang->getNguoiDung()->getId() === $_SESSION['user_id'];
        } elseif ($donHang->isGuestOrder()) {
            // Guest order - allow access (could add session-based verification if needed)
            $hasAccess = true;
        }

        if (!$hasAccess) {
            // No access to this order
            header('Location: /');
            exit;
        }

        view('donhang/thanh-cong', [
            'donHang' => $donHang
        ]);
    }
}