<?php

namespace App\Controllers\Admin;

use App\Models\SanPham;
use App\Models\BienTheSanPham;
use App\Models\DanhMuc;
use App\Models\HinhAnhSanPham;
use App\Models\ThongSoSanPham;
use App\Models\ThuongHieu;

class SanPhamController
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../../config/doctrine.php';
        $this->requireAuth();
    }

    public function danhSach()
    {
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get search parameters
        $search = trim($_GET['search'] ?? '');
        $danhMucId = intval($_GET['danh_muc'] ?? 0);
        $thuongHieuId = intval($_GET['thuong_hieu'] ?? 0);
        $trangThai = $_GET['trang_thai'] ?? '';
        $noiBat = $_GET['noi_bat'] ?? '';
        $spMoi = $_GET['sp_moi'] ?? '';

        // Build query with search conditions
        $qb = $this->em->createQueryBuilder();
        $qb->select('sp')
           ->from(SanPham::class, 'sp')
           ->leftJoin('sp.danhMuc', 'dm')
           ->leftJoin('sp.thuongHieu', 'th')
           ->orderBy('sp.ngayTao', 'DESC');

        // Apply search filters
        if (!empty($search)) {
            $qb->andWhere('sp.ten LIKE :search OR sp.maSanPham LIKE :search OR sp.moTaNgan LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($danhMucId > 0) {
            $qb->andWhere('sp.danhMuc = :danhMucId')
               ->setParameter('danhMucId', $danhMucId);
        }

        if ($thuongHieuId > 0) {
            $qb->andWhere('sp.thuongHieu = :thuongHieuId')
               ->setParameter('thuongHieuId', $thuongHieuId);
        }

        if ($trangThai !== '') {
            $qb->andWhere('sp.kichHoat = :trangThai')
               ->setParameter('trangThai', $trangThai === '1');
        }

        if ($noiBat === '1') {
            $qb->andWhere('sp.noiBat = :noiBat')
               ->setParameter('noiBat', true);
        }

        if ($spMoi === '1') {
            $qb->andWhere('sp.spMoi = :spMoi')
               ->setParameter('spMoi', true);
        }

        // Get total count for pagination
        $totalQb = clone $qb;
        $total = $totalQb->select('COUNT(sp.id)')
                        ->setFirstResult(0)
                        ->setMaxResults(null)
                        ->getQuery()
                        ->getSingleScalarResult();

        // Apply pagination
        $qb->setFirstResult($offset)
           ->setMaxResults($limit);

        $sanPhams = $qb->getQuery()->getResult();
        $totalPages = ceil($total / $limit);

        // Get filter options
        $danhMucs = $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        $thuongHieus = $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        admin_view('admin/san-pham/danh-sach', [
            'pageTitle' => 'Quản lý Sản phẩm',
            'sanPhams' => $sanPhams,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'selectedDanhMuc' => $danhMucId,
            'selectedThuongHieu' => $thuongHieuId,
            'selectedTrangThai' => $trangThai,
            'selectedNoiBat' => $noiBat,
            'selectedSpMoi' => $spMoi,
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus
        ]);
    }

    public function them()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleThem();
            return;
        }

        $danhMucs = $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        $thuongHieus = $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        $sanPham = new SanPham();
        $thongSo = new ThongSoSanPham();
        $thongSo->setSanPham($sanPham);
        $sanPham->setThongSo($thongSo);

        admin_view('admin/san-pham/them', [
            'pageTitle' => 'Thêm Sản phẩm',
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus,
            'sanPham' => $sanPham
        ]);
    }

    public function sua($id)
    {
        $sanPham = $this->em->getRepository(SanPham::class)->find($id);
        if (!$sanPham) {
            header('Location: /admin/san-pham');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSua($sanPham);
            return;
        }

        $danhMucs = $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);
        $thuongHieus = $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true], ['ten' => 'ASC']);

        admin_view('admin/san-pham/sua', [
            'pageTitle' => 'Sửa Sản phẩm',
            'sanPham' => $sanPham,
            'danhMucs' => $danhMucs,
            'thuongHieus' => $thuongHieus
        ]);
    }

    public function chiTiet($id)
    {
        $sanPham = $this->em->getRepository(SanPham::class)->find($id);
        if (!$sanPham) {
            header('Location: /admin/san-pham');
            exit;
        }

        admin_view('admin/san-pham/chi-tiet', [
            'pageTitle' => 'Chi tiết Sản phẩm',
            'sanPham' => $sanPham
        ]);
    }

    public function xoa($id)
    {
        $sanPham = $this->em->getRepository(SanPham::class)->find($id);
        if (!$sanPham) {
            header('Location: /admin/san-pham');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->em->remove($sanPham);
                $this->em->flush();
                $_SESSION['success'] = 'Xóa sản phẩm thành công!';
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa sản phẩm!';
            }
        }

        header('Location: /admin/san-pham');
        exit;
    }

    private function handleThem()
    {
        try {
            $sanPham = new SanPham();
            $this->fillSanPhamData($sanPham);
            // tạo mới thông số sản phẩm
            $ts = new ThongSoSanPham();
            $this->fillThongSoData($ts);
            $ts->setSanPham($sanPham);
            $sanPham->setThongSo($ts);
            
            $this->em->persist($sanPham);
            $this->em->flush();
            
            $_SESSION['success'] = 'Thêm sản phẩm thành công!';
            header('Location: /admin/san-pham');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/san-pham/them', [
                'pageTitle' => 'Thêm Sản phẩm',
                'error' => $error,
                'danhMucs' => $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true]),
                'thuongHieus' => $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true])
            ]);
        }
    }

    private function handleSua($sanPham)
    {
        try {
            // lấy hoặc tạo mới ThongSo nếu chưa có
            $ts = $sanPham->getThongSo()
                ?: (new ThongSoSanPham())->setSanPham($sanPham);
            $this->fillThongSoData($ts);
            $sanPham->setThongSo($ts);

            $this->fillSanPhamData($sanPham);
            $sanPham->setNgayCapNhat();
            
            $this->em->flush();
            
            $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
            header('Location: /admin/san-pham');
            exit;
        } catch (\Exception $e) {
            $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            admin_view('admin/san-pham/sua', [
                'pageTitle' => 'Sửa Sản phẩm',
                'error' => $error,
                'sanPham' => $sanPham,
                'danhMucs' => $this->em->getRepository(DanhMuc::class)->findBy(['kichHoat' => true]),
                'thuongHieus' => $this->em->getRepository(ThuongHieu::class)->findBy(['kichHoat' => true])
            ]);
        }
    }

    private function fillSanPhamData($sanPham)
    {
        $sanPham->setTen($_POST['ten'] ?? '');
        $sanPham->setMaSanPham($_POST['ma_san_pham'] ?? '');
        $sanPham->setMoTaNgan($_POST['mo_ta_ngan'] ?? '');
        $sanPham->setMoTa($_POST['mo_ta'] ?? '');
        $sanPham->setGia(floatval($_POST['gia'] ?? 0));
        $sanPham->setGiaSoSanh(floatval($_POST['gia_so_sanh'] ?? 0));
        $sanPham->setKichHoat(isset($_POST['kich_hoat']));
        $sanPham->setNoiBat(isset($_POST['noi_bat']));
        $sanPham->setSpMoi(isset($_POST['sp_moi']));

        $sanPham->setDuongDan($_POST['duong_dan']);

        if (!empty($_POST['danh_muc_id'])) {
            $danhMuc = $this->em->getRepository(DanhMuc::class)->find($_POST['danh_muc_id']);
            if ($danhMuc) {
                $sanPham->setDanhMuc($danhMuc);
            }
        }

        if (!empty($_POST['thuong_hieu_id'])) {
            $thuongHieu = $this->em->getRepository(ThuongHieu::class)->find($_POST['thuong_hieu_id']);
            if ($thuongHieu) {
                $sanPham->setThuongHieu($thuongHieu);
            }
        }

        // Xử lý biến thể sản phẩm
        $this->xuLyBienTheSanPham($sanPham);
    }

    /**
     * Xử lý thêm/cập nhật biến thể sản phẩm
     */
    private function xuLyBienTheSanPham($sanPham)
    {
        if (!isset($_POST['bien_thes']) || !is_array($_POST['bien_thes'])) {
            return;
        }

        foreach ($_POST['bien_thes'] as $bienTheData) {
            if (empty($bienTheData['ma_san_pham'])) {
                continue;
            }

            $bienThe = null;
            
            // Nếu có ID, tìm biến thể để cập nhật
            if (!empty($bienTheData['id'])) {
                $bienThe = $this->em->getRepository(BienTheSanPham::class)->find($bienTheData['id']);
                if ($bienThe && $bienThe->getSanPham()->getId() !== $sanPham->getId()) {
                    continue; // Không cho phép cập nhật biến thể của sản phẩm khác
                }
            }

            // Tạo biến thể mới nếu không tìm thấy
            if (!$bienThe) {
                $bienThe = new BienTheSanPham();
                $bienThe->setSanPham($sanPham);
            }

            // Cập nhật thông tin biến thể
            $bienThe->setMaSanPham($bienTheData['ma_san_pham']);
            $bienThe->setMauSac($bienTheData['mau_sac'] ?? null);
            $bienThe->setBoNho($bienTheData['bo_nho'] ?? null);
            $bienThe->setGia(floatval($bienTheData['gia'] ?? 0));
            $bienThe->setGiaSoSanh(floatval($bienTheData['gia_so_sanh'] ?? 0));
            $bienThe->setSoLuongTon(intval($bienTheData['so_luong_ton'] ?? 0));
            $bienThe->setNguongTonThap(intval($bienTheData['nguong_ton_thap'] ?? 5));
            $bienThe->setTrongLuong(floatval($bienTheData['trong_luong'] ?? 0));
            $bienThe->setKichHoat(isset($bienTheData['kich_hoat']));

            $this->em->persist($bienThe);
            
            // Xử lý hình ảnh mới cho biến thể
            $this->xuLyHinhAnhBienThe($bienThe, $bienTheData, $sanPham);
        }

        // Xử lý xóa biến thể
        if (isset($_POST['xoa_bien_thes']) && is_array($_POST['xoa_bien_thes'])) {
            foreach ($_POST['xoa_bien_thes'] as $bienTheId) {
                $bienThe = $this->em->getRepository(BienTheSanPham::class)->find($bienTheId);
                if ($bienThe && $bienThe->getSanPham()->getId() === $sanPham->getId()) {
                    // Với cascade=['persist', 'remove'], Doctrine sẽ tự động xóa các bản ghi liên quan
                    $this->em->remove($bienThe);
                }
            }
        }
        
        // Xử lý xóa hình ảnh
        if (isset($_POST['xoa_hinh_anhs']) && is_array($_POST['xoa_hinh_anhs'])) {
            foreach ($_POST['xoa_hinh_anhs'] as $hinhAnhId) {
                $hinhAnh = $this->em->getRepository(HinhAnhSanPham::class)->find($hinhAnhId);
                if ($hinhAnh && $hinhAnh->getBienThe() && 
                    $hinhAnh->getBienThe()->getSanPham()->getId() === $sanPham->getId()) {
                    $this->em->remove($hinhAnh);
                }
            }
        }
    }

    /**
     * Xử lý hình ảnh cho biến thể sản phẩm
     */
    private function xuLyHinhAnhBienThe($bienThe, $bienTheData, $sanPham)
    {
        if (!isset($bienTheData['hinh_anhs_moi']) || !is_array($bienTheData['hinh_anhs_moi'])) {
            return;
        }

        foreach ($bienTheData['hinh_anhs_moi'] as $hinhAnhData) {
            if (empty($hinhAnhData['url'])) {
                continue;
            }

            $imageUrl = trim($hinhAnhData['url']);
            
            // Validate URL
            if (!$this->isValidImageUrl($imageUrl)) {
                continue;
            }
            
            // Tạo record trong database
            $hinhAnh = new HinhAnhSanPham();
            $hinhAnh->setSanPham($sanPham);
            $hinhAnh->setBienThe($bienThe);
            $hinhAnh->setDuongDanHinh($imageUrl);
            $hinhAnh->setTextThayThe('Hình ảnh biến thể ' . $bienThe->getTenDayDu());
            $hinhAnh->setThuTu(0);
            
            $this->em->persist($hinhAnh);
        }
    }

    /**
     * Kiểm tra URL hình ảnh có hợp lệ không
     */
    private function isValidImageUrl($url)
    {
        // Kiểm tra URL có hợp lệ không
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        // Kiểm tra extension hoặc domain phổ biến
        return preg_match('/\.(jpg|jpeg|png|gif|webp|svg)(\?.*)?$/i', $url) ||
               strpos($url, 'data:image/') === 0 ||
               strpos($url, 'imgur.com') !== false ||
               strpos($url, 'cloudinary.com') !== false ||
               strpos($url, 'unsplash.com') !== false ||
               strpos($url, 'pexels.com') !== false ||
               strpos($url, 'pixabay.com') !== false;
    }


    private function fillThongSoData(ThongSoSanPham $ts)
    {
        $data = $_POST['thong_so'] ?? [];
        $ts->setKichThuocManHinh($data['kich_thuoc_man_hinh'] ?? null)
            ->setDoPhanGiai($data['do_phan_giai'] ?? null)
            ->setLoaiManHinh($data['loai_man_hinh'] ?? null)
            ->setHeDieuHanh($data['he_dieu_hanh'] ?? null)
            ->setBoXuLy($data['bo_xu_ly'] ?? null)
            ->setRam($data['ram'] ?? null)
            ->setBoNho($data['bo_nho'] ?? null)
            ->setMoRongBoNho(isset($data['mo_rong_bo_nho']))
            ->setCameraSau($data['camera_sau'] ?? null)
            ->setCameraTruoc($data['camera_truoc'] ?? null)
            ->setDungLuongPin($data['dung_luong_pin'] ?? null)
            ->setLoaiSac($data['loai_sac'] ?? null)
//            ->setKetNoi($data['ket_noi'] ?? null)          // array
//            ->setMauSacCoSan($data['mau_sac_co_san'] ?? null) // array
            ->setThoiGianBaoHanh($data['thoi_gian_bao_hanh'] ?? null)
            ->setChongNuoc($data['chong_nuoc'] ?? null)
            ->setCamBienVanTay(isset($data['cam_bien_van_tay']))
            ->setMoKhoaKhuonMat(isset($data['mo_khoa_khuon_mat']));

        // Chuyển chuỗi “wifi, bluetooth” thành mảng ['wifi','bluetooth']
        $ketNoiArr = [];
        if (!empty($data['ket_noi'])) {
            $ketNoiArr = array_filter(array_map('trim', explode(',', $data['ket_noi'])));
        }
        $ts->setKetNoi($ketNoiArr);

        $mauSacArr = null;
        if (!empty($data['mau_sac_co_san'])) {
            $mauSacArr = array_filter(array_map('trim', explode(',', $data['mau_sac_co_san'])));
        }
        $ts->setMauSacCoSan($mauSacArr);
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}