<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

use App\Models\ChiTietDonHang;
use App\Models\DonHang;
use App\Models\ThanhToan;
use App\Models\ThuongHieu;
use App\Models\DanhMuc;
use App\Models\SanPham;
use App\Models\ThongSoSanPham;
use App\Models\BienTheSanPham;
use App\Models\HinhAnhSanPham;

class SeedDataVietnam
{
    private $em;

    public function __construct()
    {
        $this->em = require __DIR__ . '/../../config/doctrine.php';
    }

    public function run()
    {
        echo "Bắt đầu seed dữ liệu tiếng Việt...\n";

        // 1. Tạo thương hiệu
        $brands = $this->createBrands();
        $apple  = $brands['apple'];
        $samsung = $brands['samsung'];
        $xiaomi  = $brands['xiaomi'];
        $huawei  = $brands['huawei'];
        $oppo    = $brands['oppo'];
        $nokia   = $brands['nokia'];
        $vivo    = $brands['vivo'];

        // 2. Tạo danh mục
        $categories = $this->createCategories();
        $smartphoneCat  = $categories['smartphone'];

        $foldableCat    = $this->getCategoryBySlug('dien-thoai-man-hinh-gap') ?? $smartphoneCat;
        $cameraCat      = $this->getCategoryBySlug('dien-thoai-chup-anh') ?? $smartphoneCat;
        $budgetCat      = $this->getCategoryBySlug('dien-thoai-gia-re') ?? $smartphoneCat;
        $featureCat     = $this->getCategoryBySlug('dien-thoai-pho-thong') ?? $smartphoneCat;

        /// 3. Sản phẩm theo yêu cầu
        $this->createIphone13To16Series($apple, $smartphoneCat);

        $this->createSamsungGalaxySeries($samsung, $foldableCat, $cameraCat, $smartphoneCat, $budgetCat);

        $this->createHuaweiNovaSeries($huawei, $smartphoneCat);

        $this->createNokiaFeaturePhones($nokia, $featureCat);

        $this->createOppoVivoXiaomi($oppo, $vivo, $xiaomi, $smartphoneCat, $budgetCat);

        //        4. Tạo đơn hàng mẫu để báo cáo
        $this->createSampleOrders();
//        Tạo 20 đơn hàng mẫu đã hoàn thành
        $this->createCompletedOrders();

        echo "Hoàn thành seed dữ liệu!\n";
    }

    private function createBrands(): array
    {
        $data = [
            [ 'ten' => 'Apple',   'slug' => 'apple',   'logo' => 'https://www.shareicon.net/data/256x256/2015/09/26/107646_apple_512x512.png',
                'moTa' => 'Công ty công nghệ hàng đầu thế giới, nổi tiếng với các sản phẩm iPhone, iPad, Mac và các thiết bị điện tử cao cấp.',
                'website' => 'https://www.apple.com' ],
            [ 'ten' => 'Samsung', 'slug' => 'samsung', 'logo' => 'https://cdn-icons-png.flaticon.com/256/882/882747.png',
                'moTa' => 'Tập đoàn điện tử Hàn Quốc lớn nhất thế giới, dẫn đầu về dòng Galaxy từ phân khúc phổ thông đến cao cấp.',
                'website' => 'https://www.samsung.com' ],
            [ 'ten' => 'Xiaomi',  'slug' => 'xiaomi',  'logo' => 'https://cdn-icons-png.flaticon.com/256/882/882720.png',
                'moTa' => 'Công ty công nghệ Trung Quốc, nổi bật với smartphone cấu hình cao mà giá cả hợp lý cùng giao diện MIUI.',
                'website' => 'https://www.mi.com/global' ],
            [ 'ten' => 'Huawei',  'slug' => 'huawei',  'logo' => 'https://pcr.cloud-mercato.com/static/img/logo/huawei.png',
                'moTa' => 'Tập đoàn viễn thông và thiết bị tiêu dùng Trung Quốc, chuyên về smartphone camera mạnh và hạ tầng mạng.',
                'website' => 'https://www.huawei.com' ],
            [ 'ten' => 'OPPO',    'slug' => 'oppo',    'logo' => 'https://img-prd-pim.poorvika.com/brand/Logo-0-0031-oppo.png',
                'moTa' => 'Thương hiệu smartphone Trung Quốc, nổi tiếng với thiết kế tinh tế và công nghệ sạc siêu nhanh VOOC.',
                'website' => 'https://www.oppo.com' ],
            [ 'ten' => 'Nokia',   'slug' => 'nokia',   'logo' => 'https://cdn-icons-png.freepik.com/256/882/882740.png?semt=ais_hybrid',
                'moTa' => 'Công ty Phần Lan danh tiếng, nổi bật với độ bền bỉ và hiện do HMD Global phát triển smartphone',
                'website' => 'https://www.nokia.com' ],
            [ 'ten' => 'Vivo',    'slug' => 'vivo',    'logo' => 'https://cdn.iconscout.com/icon/free/png-256/free-vivo-282151.png?f=webp',
                'moTa' => 'Hãng điện thoại Trung Quốc, chú trọng công nghệ âm thanh cao cấp và camera selfie ấn tượng.',
                'website' => 'https://www.vivo.com' ],
        ];

        $created = [];
        foreach ($data as $item) {
            $b = new ThuongHieu();
            $b->setTen($item['ten'])
                ->setDuongDan($item['slug'])
                ->setLogo($item['logo'])
                ->setMoTa($item['moTa'])
                ->setWebsite($item['website'])
                ->setKichHoat(true);
            // giữ nguyên ngày tạo/cập nhật hiện tại
            $this->em->persist($b);
            $created[$item['slug']] = $b;
        }
        $this->em->flush();

        echo "✓ Đã tạo " . count($created) . " thương hiệu\n";
        return $created;
    }

    private function createCategories()
    {
        // Danh mục cha: Điện thoại thông minh
        $smartphone = new DanhMuc();
        $smartphone->setTen('Điện thoại thông minh')
            ->setDuongDan('dien-thoai-thong-minh')
            ->setMoTa('Thiết bị hiện đại dùng hệ điều hành (Android, iOS), hỗ trợ cảm ứng, cài ứng dụng, truy cập internet, giải trí, làm việc...')
            ->setHinhAnh('https://bizweb.dktcdn.net/thumb/grande/100/401/951/products/dacdiemnoibat9d0be39b0e1548fab.png?v=1731293035543')
            ->setThuTu(1)
            ->setKichHoat(true);

        $this->em->persist($smartphone);

        // Danh mục con
        $childCategories = [
            [
                'ten' => 'Điện thoại phổ thông',
                'duongDan' => 'dien-thoai-pho-thong',
                'moTa' => 'Chỉ hỗ trợ gọi, nhắn tin, pin lâu, ít tính năng thông minh...',
                'hinhAnh' => 'https://cdn.tgdd.vn/Products/Images/42/326477/nokia-3210-4g-yellow-thumb-600x600.jpg',
                'thuTu' => 2,
            ],
            [
                'ten' => 'Điện thoại gập',
                'duongDan' => 'dien-thoai-gap',
                'moTa' => 'Gập theo chiều dọc như vỏ sò, có bàn phím vật lý hoặc màn hình cảm ứng gập.',
                'hinhAnh' => 'https://minhtuanmobile.com/uploads/products/240716032322-sm-f741bzyexxv.png',
                'thuTu' => 3,
            ],
            [
                'ten' => 'Điện thoại màn hình gập',
                'duongDan' => 'dien-thoai-man-hinh-gap',
                'moTa' => 'Màn hình cảm ứng gập lại như cuốn sổ, thành tablet mini khi mở.',
                'hinhAnh' => 'https://cdn.hoanghamobile.vn/i/previewV2/Uploads/2025/07/09/thumb-fold7-xanh-navy.png',
                'thuTu' => 4,
            ],
            [
                'ten' => 'Điện thoại trượt',
                'duongDan' => 'dien-thoai-truot',
                'moTa' => 'Cơ chế trượt để lộ bàn phím hoặc camera.',
                'hinhAnh' => 'https://cf.shopee.vn/file/893fafe439cab867d0dc578d70f0c3e0',
                'thuTu' => 5,
            ],
            [
                'ten' => 'Điện thoại siêu bền',
                'duongDan' => 'dien-thoai-sieu-ben',
                'moTa' => 'Chống sốc, chống nước, dùng trong môi trường khắc nghiệt.',
                'hinhAnh' => 'https://cdn.tgdd.vn/Files/2015/10/02/712815/a9-1.jpg',
                'thuTu' => 6,
            ],
            [
                'ten' => 'Điện thoại chơi game',
                'duongDan' => 'dien-thoai-choi-game',
                'moTa' => 'Tối ưu hiệu năng, tản nhiệt, màn hình tần số cao, pin lớn.',
                'hinhAnh' => 'https://gamek.mediacdn.vn/133514250583805952/2022/9/19/image-1663519828494-16635198289381427856236-1663559485814-1663559485944716624654.png',
                'thuTu' => 7,
            ],
            [
                'ten' => 'Điện thoại chụp ảnh',
                'duongDan' => 'dien-thoai-chup-anh',
                'moTa' => 'Tập trung vào camera chất lượng cao, nhiều tính năng nhiếp ảnh.',
                'hinhAnh' => 'https://tieudung.kinhtedothi.vn/upload_images/images/2024/08/11/1(9).jpg',
                'thuTu' => 8,
            ],
            [
                'ten' => 'Điện thoại giá rẻ',
                'duongDan' => 'dien-thoai-gia-re',
                'moTa' => 'Giá cả phải chăng, cấu hình cơ bản, hướng tới người dùng phổ thông.',
                'hinhAnh' => 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/d/i/dien-thoai-meizu-mblu-21_4__2.png',
                'thuTu' => 9,
            ],
        ];

        foreach ($childCategories as $catData) {
            $cat = new DanhMuc();
            $cat->setTen($catData['ten'])
                ->setDuongDan($catData['duongDan'])
                ->setMoTa($catData['moTa'])
                ->setHinhAnh($catData['hinhAnh'])
                ->setThuTu($catData['thuTu'])
                ->setKichHoat(true)
                ->setDanhMucCha($smartphone);

            $this->em->persist($cat);
        }

        $this->em->flush();

        echo "✓ Đã tạo 9 danh mục sản phẩm\n";

        return [
            'smartphone' => $smartphone,
            'children'   => $childCategories
        ];
    }

    private function createIphone13To16Series($apple, $cat)
    {
        $products = [
            // --- iPhone 13 ---
            [
                'ma'=>'IP13-BASE', 'ten'=>'iPhone 13', 'duong_dan'=>'iphone-13',
                'mo_ta_ngan'=>'Chip A15 Bionic, camera kép, pin bền.',
                'mo_ta'=>'iPhone 13 nâng cấp hiệu năng với A15 Bionic, màn hình Super Retina XDR sáng hơn, camera Cinematic.',
                'gia_co_ban'=>18990000,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.1 inch','do_phan_giai'=>'2532 x 1170','loai_man_hinh'=>'Super Retina XDR OLED',
                    'he_dieu_hanh'=>'iOS 15','bo_xu_ly'=>'A15 Bionic','ram'=>'4GB',
                    'camera_sau'=>'12MP wide + 12MP ultra-wide','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'3240 mAh','loai_sac'=>'Lightning, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.0','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Đen','bo_nho'=>'128GB','gia'=>18990000,'ton_kho'=>30,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/223602/iphone-13-1-2-750x500.jpg'],
                    ['mau_sac'=>'Trắng','bo_nho'=>'256GB','gia'=>21990000,'ton_kho'=>20,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/223602/iphone-13-1-4-750x500.jpg'],
                ]
            ],
            [
                'ma'=>'IP13-PMAX','ten'=>'iPhone 13 Pro Max','duong_dan'=>'iphone-13-pro-max',
                'mo_ta_ngan'=>'Màn ProMotion 120Hz, 3 camera Pro.',
                'mo_ta'=>'iPhone 13 Pro Max với ProMotion 120Hz, camera tele, macro, quay ProRes.',
                'gia_co_ban'=>25990000,'noi_bat'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch','do_phan_giai'=>'2778 x 1284','loai_man_hinh'=>'Super Retina XDR OLED 120Hz',
                    'he_dieu_hanh'=>'iOS 15','bo_xu_ly'=>'A15 Bionic','ram'=>'6GB',
                    'camera_sau'=>'12MP wide + 12MP ultra-wide + 12MP tele','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'4352 mAh','loai_sac'=>'Lightning, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.0','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh','bo_nho'=>'128GB','gia'=>25990000,'ton_kho'=>15,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/230529/iphone-13-pro-max-xanh-1-750x500.jpg'],
                    ['mau_sac'=>'Xám','bo_nho'=>'256GB','gia'=>28990000,'ton_kho'=>12,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/230529/iphone-13-pro-max-1-3-750x500.jpg'],
                    ['mau_sac'=>'Vàng','bo_nho'=>'512GB','gia'=>32990000,'ton_kho'=>8,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/230529/iphone-13-pro-max-1-2-750x500.jpg'],
                ]
            ],

            // --- iPhone 14 ---
            [
                'ma'=>'IP14-BASE','ten'=>'iPhone 14','duong_dan'=>'iphone-14',
                'mo_ta_ngan'=>'A15 cải tiến, SOS vệ tinh.',
                'mo_ta'=>'iPhone 14 giữ thiết kế quen thuộc, cải thiện camera và an toàn với SOS vệ tinh.',
                'gia_co_ban'=>20990000,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.1 inch','do_phan_giai'=>'2532 x 1170','loai_man_hinh'=>'Super Retina XDR OLED',
                    'he_dieu_hanh'=>'iOS 16','bo_xu_ly'=>'A15 Bionic','ram'=>'6GB',
                    'camera_sau'=>'12MP wide + 12MP ultra-wide','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'3279 mAh','loai_sac'=>'Lightning, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Đen','bo_nho'=>'128GB','gia'=>20990000,'ton_kho'=>28,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/240259/iphone-14-den-glr-1-750x500.jpg'],
                    ['mau_sac'=>'Xanh','bo_nho'=>'256GB','gia'=>23990000,'ton_kho'=>18,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/240259/iphone-14-xanh-1-750x500.jpg'],
                ]
            ],
            [
                'ma'=>'IP14-PMAX','ten'=>'iPhone 14 Pro Max','duong_dan'=>'iphone-14-pro-max',
                'mo_ta_ngan'=>'Dynamic Island, 48MP.',
                'mo_ta'=>'iPhone 14 Pro Max: Dynamic Island, camera 48MP, Always-On, A16 Bionic.',
                'gia_co_ban'=>30990000,'noi_bat'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch','do_phan_giai'=>'2796 x 1290','loai_man_hinh'=>'LTPO Super Retina XDR 120Hz',
                    'he_dieu_hanh'=>'iOS 16','bo_xu_ly'=>'A16 Bionic','ram'=>'6GB',
                    'camera_sau'=>'48MP wide + 12MP ultra-wide + 12MP tele','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'4323 mAh','loai_sac'=>'Lightning, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Tím','bo_nho'=>'256GB','gia'=>33990000,'ton_kho'=>10,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/251192/iphone-14-pro-max-purple-1-750x500.jpg'],
                    ['mau_sac'=>'Bạc','bo_nho'=>'512GB','gia'=>37990000,'ton_kho'=>6,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/251192/iphone-14-pro-max-bac-1-750x500.jpg'],
                ]
            ],

            // --- iPhone 15 ---
            [
                'ma'=>'IP15-BASE','ten'=>'iPhone 15','duong_dan'=>'iphone-15',
                'mo_ta_ngan'=>'Dynamic Island, USB-C, 48MP.',
                'mo_ta'=>'iPhone 15 mang USB-C, camera 48MP, màu pastel, A16 Bionic.',
                'gia_co_ban'=>21990000,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.1 inch','do_phan_giai'=>'2556 x 1179','loai_man_hinh'=>'Super Retina XDR OLED',
                    'he_dieu_hanh'=>'iOS 17','bo_xu_ly'=>'A16 Bionic','ram'=>'6GB',
                    'camera_sau'=>'48MP wide + 12MP ultra-wide','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'3349 mAh','loai_sac'=>'USB-C, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Hồng','bo_nho'=>'128GB','gia'=>21990000,'ton_kho'=>40,'hinh'=>'https://cdnv2.tgdd.vn/mwg-static/tgdd/Products/Images/42/303716/iphone-15-pink-2-638629454338180247-750x500.jpg'],
                    ['mau_sac'=>'Xanh','bo_nho'=>'256GB','gia'=>24990000,'ton_kho'=>25,'hinh'=>'https://cdnv2.tgdd.vn/mwg-static/tgdd/Products/Images/42/303716/iphone-15-blue-2-638629450090113519-750x500.jpg'],
                ]
            ],
            [
                'ma'=>'IP15-PMAX','ten'=>'iPhone 15 Pro Max','duong_dan'=>'iphone-15-pro-max',
                'mo_ta_ngan'=>'A17 Pro, titan, tele tiềm vọng.',
                'mo_ta'=>'Khung titan nhẹ, A17 Pro mạnh mẽ, zoom quang 5x, USB-C.',
                'gia_co_ban'=>33990000,'noi_bat'=>true,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch','do_phan_giai'=>'2796 x 1290','loai_man_hinh'=>'LTPO Super Retina XDR 120Hz',
                    'he_dieu_hanh'=>'iOS 17','bo_xu_ly'=>'A17 Pro','ram'=>'8GB',
                    'camera_sau'=>'48MP + 12MP ultra-wide + 12MP periscope','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'4422 mAh','loai_sac'=>'USB-C, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 6E','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Nhũ Vàng','bo_nho'=>'256GB','gia'=>36990000,'ton_kho'=>14,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/305658/iphone-15-pro-max-gold-thumbnew-600x600.jpg'],
                    ['mau_sac'=>'Đen','bo_nho'=>'512GB','gia'=>40990000,'ton_kho'=>8,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/305658/s16/iphone-15-pro-max-blue-1-2-650x650.png'],
                ]
            ],

            // --- iPhone 16 ---
            [
                'ma'=>'IP16-BASE','ten'=>'iPhone 16','duong_dan'=>'iphone-16',
                'mo_ta_ngan'=>'A18, Camera Control, Apple Intelligence.',
                'mo_ta'=>'iPhone 16 với chip A18, Camera Control, Apple Intelligence thế hệ mới.',
                'gia_co_ban'=>22990000,'noi_bat'=>true,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.1 inch','do_phan_giai'=>'2556 x 1179','loai_man_hinh'=>'Super Retina XDR OLED',
                    'he_dieu_hanh'=>'iOS 18','bo_xu_ly'=>'A18','ram'=>'8GB',
                    'camera_sau'=>'48MP + 12MP ultra-wide','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'3561 mAh','loai_sac'=>'USB-C, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 6E','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh Lưu Ly','bo_nho'=>'128GB','gia'=>22990000,'ton_kho'=>45,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/329135/iphone-16-blue-600x600.png'],
                    ['mau_sac'=>'Hồng','bo_nho'=>'256GB','gia'=>25990000,'ton_kho'=>30,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/329136/iphone-16-pink-600x600.png'],
                ]
            ],
            [
                'ma'=>'IP16-PMAX','ten'=>'iPhone 16 Pro Max','duong_dan'=>'iphone-16-pro-max',
                'mo_ta_ngan'=>'A18 Pro, camera Pro, titan.',
                'mo_ta'=>'iPhone 16 Pro Max với A18 Pro, khung titan, nâng cấp camera và AI on-device.',
                'gia_co_ban'=>36990000,'noi_bat'=>true,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.9 inch','do_phan_giai'=>'2868 x 1320','loai_man_hinh'=>'LTPO Super Retina XDR 120Hz',
                    'he_dieu_hanh'=>'iOS 18','bo_xu_ly'=>'A18 Pro','ram'=>'8GB',
                    'camera_sau'=>'48MP wide + 48MP ultra-wide + 12MP periscope','camera_truoc'=>'12MP TrueDepth',
                    'dung_luong_pin'=>'4700 mAh','loai_sac'=>'USB-C, MagSafe',
                    'ket_noi'=>['5G','Wi-Fi 7','Bluetooth 5.4','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Nhũ Vàng','bo_nho'=>'256GB','gia'=>39990000,'ton_kho'=>16,'hinh'=>'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/i/p/iphone-16-pro.png'],
                    ['mau_sac'=>'Trắng','bo_nho'=>'512GB','gia'=>43990000,'ton_kho'=>10,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/329151/iphone-16-pro-max-titan-trang-thumbtgdd-600x600.png'],
                ]
            ],
        ];

        foreach ($products as $p) $this->createProduct($p, $apple, $cat);
        echo "✓ Đã tạo iPhone 13→16 series\n";
    }

    private function createSamsungGalaxySeries($samsung, $foldableCat, $cameraCat, $smartphoneCat, $budgetCat)
    {
        $products = [
            // Z series (foldable)
            [
                'ma'=>'SM-ZF6','ten'=>'Galaxy Z Fold6','duong_dan'=>'galaxy-z-fold6',
                'mo_ta_ngan'=>'Gập ngang, Snapdragon 8 Gen 3 for Galaxy.',
                'mo_ta'=>'Màn hình gập 7.6", đa nhiệm mạnh, bền bỉ hơn, hiệu năng cao.',
                'gia_co_ban'=>42990000,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'7.6 inch (chính), 6.3 inch (phụ)','do_phan_giai'=>'QXGA+ / FHD+',
                    'loai_man_hinh'=>'Dynamic AMOLED 2X 120Hz','he_dieu_hanh'=>'Android 14 (One UI)',
                    'bo_xu_ly'=>'Snapdragon 8 Gen 3 for Galaxy','ram'=>'12GB',
                    'camera_sau'=>'50MP + 10MP tele + 12MP ultra-wide','camera_truoc'=>'10MP (ngoài) + 4MP (trong)',
                    'dung_luong_pin'=>'4400 mAh','loai_sac'=>'USB-C, 25W',
                    'ket_noi'=>['5G','Wi-Fi 7','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP48','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh Navy','bo_nho'=>'256GB','gia'=>42990000,'ton_kho'=>12,'hinh'=>'https://smartviets.com/upload/GALAXY%20FOLD/FOLD%206/Xanh-Navy-600x600.png'],
                    ['mau_sac'=>'Hồng','bo_nho'=>'512GB','gia'=>45990000,'ton_kho'=>8,'hinh'=>'https://smartviets.com/upload/GALAXY%20FOLD/FOLD%206/Hong-Rose-600x600.png'],
                ],
            ],
            [
                'ma'=>'SM-ZFL6','ten'=>'Galaxy Z Flip6','duong_dan'=>'galaxy-z-flip6',
                'mo_ta_ngan'=>'Gập dọc thời trang, cover lớn.',
                'mo_ta'=>'Thiết kế nhỏ gọn, màn phụ lớn tiện dụng, hiệu năng cao cho nhu cầu hằng ngày.',
                'gia_co_ban'=>27990000,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch (chính), 3.4 inch (phụ)','do_phan_giai'=>'FHD+',
                    'loai_man_hinh'=>'Dynamic AMOLED 2X 120Hz','he_dieu_hanh'=>'Android 14 (One UI)',
                    'bo_xu_ly'=>'Snapdragon 8 Gen 3 for Galaxy','ram'=>'12GB',
                    'camera_sau'=>'50MP + 12MP ultra-wide','camera_truoc'=>'10MP',
                    'dung_luong_pin'=>'4000 mAh','loai_sac'=>'USB-C, 25W',
                    'ket_noi'=>['5G','Wi-Fi 7','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP48','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh','bo_nho'=>'256GB','gia'=>27990000,'ton_kho'=>20,'hinh'=>'https://smartviets.com/upload/GALAXY%20FLIP/FLIP%206/Xanh-Mint-600x600.png'],
                    ['mau_sac'=>'Tím','bo_nho'=>'512GB','gia'=>30990000,'ton_kho'=>10,'hinh'=>'https://smartviets.com/upload/GALAXY%20FLIP/FLIP%206/Xanh-Maya-600x600.png'],
                ],
            ],

            // S series (camera/flagship)
            [
                'ma'=>'SM-S24U','ten'=>'Galaxy S24 Ultra','duong_dan'=>'galaxy-s24-ultra',
                'mo_ta_ngan'=>'200MP, Note DNA, AI Galaxy.',
                'mo_ta'=>'S24 Ultra với S Pen, camera 200MP, Snapdragon 8 Gen 3, tính năng AI Galaxy.',
                'gia_co_ban'=>29990000,'noi_bat'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.8 inch','do_phan_giai'=>'QHD+',
                    'loai_man_hinh'=>'Dynamic AMOLED 2X 120Hz','he_dieu_hanh'=>'Android 14 (One UI)',
                    'bo_xu_ly'=>'Snapdragon 8 Gen 3 for Galaxy','ram'=>'12GB',
                    'camera_sau'=>'200MP + 50MP periscope + 10MP tele + 12MP ultra-wide','camera_truoc'=>'12MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 45W',
                    'ket_noi'=>['5G','Wi-Fi 7','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xám','bo_nho'=>'256GB','gia'=>29990000,'ton_kho'=>18,'hinh'=>'https://clickbuy.com.vn/uploads/pro/2_52714.jpg'],
                    ['mau_sac'=>'Cam','bo_nho'=>'512GB','gia'=>32990000,'ton_kho'=>12,'hinh'=>'https://clickbuy.com.vn/uploads/pro/2_52709.jpg'],
                ],
            ],
            [
                'ma'=>'SM-S24','ten'=>'Galaxy S24','duong_dan'=>'galaxy-s24',
                'mo_ta_ngan'=>'Nhỏ gọn, flagship.',
                'mo_ta'=>'Thiết kế gọn gàng, hiệu năng mạnh, màn đẹp, AI tiện ích.',
                'gia_co_ban'=>18990000,'noi_bat'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.2 inch','do_phan_giai'=>'FHD+',
                    'loai_man_hinh'=>'Dynamic AMOLED 2X 120Hz','he_dieu_hanh'=>'Android 14 (One UI)',
                    'bo_xu_ly'=>'Snapdragon 8 Gen 3 / Exynos 2400 (tuỳ thị trường)','ram'=>'8GB',
                    'camera_sau'=>'50MP + 10MP tele + 12MP ultra-wide','camera_truoc'=>'12MP',
                    'dung_luong_pin'=>'4000 mAh','loai_sac'=>'USB-C, 25W',
                    'ket_noi'=>['5G','Wi-Fi 6E','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Đen','bo_nho'=>'128GB','gia'=>18990000,'ton_kho'=>25,'hinh'=>'https://cdn2.cellphones.com.vn/358x/media/catalog/product/g/a/galaxy-s24-plus-den_2.png'],
                    ['mau_sac'=>'Vàng','bo_nho'=>'256GB','gia'=>20990000,'ton_kho'=>18,'hinh'=>'https://cdn2.cellphones.com.vn/358x/media/catalog/product/g/a/galaxy-s24-plus-vang_2.png'],
                ],
            ],

            // A series (tầm trung)
            [
                'ma'=>'SM-A55','ten'=>'Galaxy A55 5G','duong_dan'=>'galaxy-a55-5g',
                'mo_ta_ngan'=>'Exynos 1480, khung kim loại.',
                'mo_ta'=>'A55 5G nổi bật với thiết kế cứng cáp, camera đẹp, màn 120Hz.',
                'gia_co_ban'=>9990000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.6 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'Super AMOLED 120Hz',
                    'he_dieu_hanh'=>'Android 14 (One UI)','bo_xu_ly'=>'Exynos 1480','ram'=>'8GB',
                    'camera_sau'=>'50MP + 12MP ultra-wide + 5MP macro','camera_truoc'=>'32MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 25W',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP67','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh Navy','bo_nho'=>'128GB','gia'=>9990000,'ton_kho'=>40,'hinh'=>'https://images.samsung.com/is/image/samsung/p6pim/in/sm-a556ezkzins/gallery/in-galaxy-a55-5g-sm-a556-498926-sm-a556ezkzins-thumb-540255184'],
                    ['mau_sac'=>'Xanh Nhạt','bo_nho'=>'256GB','gia'=>10990000,'ton_kho'=>25,'hinh'=>'https://images.samsung.com/is/image/samsung/p6pim/fi/sm-a556blbaeub/gallery/fi-galaxy-a55-5g-sm-a556-sm-a556blbaeub-540011063?$624_624_PNG$'],
                ],
            ],
            [
                'ma'=>'SM-A35','ten'=>'Galaxy A35 5G','duong_dan'=>'galaxy-a35-5g',
                'mo_ta_ngan'=>'Màn 120Hz, camera OIS.',
                'mo_ta'=>'Cân bằng, phù hợp số đông, pin trâu, OIS chống rung.',
                'gia_co_ban'=>7990000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.6 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'Super AMOLED 120Hz',
                    'he_dieu_hanh'=>'Android 14 (One UI)','bo_xu_ly'=>'Exynos 1380','ram'=>'6GB',
                    'camera_sau'=>'50MP + 8MP ultra-wide + 5MP macro','camera_truoc'=>'13MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 25W',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP67','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh Nhạt','bo_nho'=>'128GB','gia'=>7990000,'ton_kho'=>45,'hinh'=>'https://smartviets.com/upload/A/A35%20xanh.png'],
                    ['mau_sac'=>'Đen','bo_nho'=>'256GB','gia'=>8990000,'ton_kho'=>22,'hinh'=>'https://smartviets.com/upload/A/A35%20den.png'],
                ],
            ],

            // M series (pin lớn/online)
            [
                'ma'=>'SM-M55','ten'=>'Galaxy M55 5G','duong_dan'=>'galaxy-m55-5g',
                'mo_ta_ngan'=>'Pin trâu, tầm trung.',
                'mo_ta'=>'Dành cho người ưu tiên pin và hiệu năng ổn định trong tầm giá.',
                'gia_co_ban'=>7490000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'Super AMOLED+ 120Hz',
                    'he_dieu_hanh'=>'Android 14 (One UI)','bo_xu_ly'=>'Snapdragon 7 Gen 1','ram'=>'8GB',
                    'camera_sau'=>'50MP + 8MP ultra-wide + 2MP','camera_truoc'=>'32MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 25W',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.2','NFC'],
                    'chong_nuoc'=>'—','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh lá','bo_nho'=>'128GB','gia'=>7490000,'ton_kho'=>35,'hinh'=>'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/d/i/dien-thoai-samsung-galaxy-m55.png'],
                    ['mau_sac'=>'Đen','bo_nho'=>'256GB','gia'=>8290000,'ton_kho'=>18,'hinh'=>'https://cdn2.cellphones.com.vn/358x/media/catalog/product/m/5/m55-den.jpg'],
                ],
            ],
        ];

        foreach ($products as $p) {
            // chọn danh mục phù hợp
            $cat = $smartphoneCat;
            if (str_starts_with($p['ma'], 'SM-Z')) $cat = $foldableCat;
            if ($p['ma'] === 'SM-S24U')          $cat = $cameraCat;
            if ($p['ma'] === 'SM-M55')           $cat = $budgetCat;

            $this->createProduct($p, $samsung, $cat);
        }
        echo "✓ Đã tạo Samsung Galaxy Z/S/A/M\n";
    }

    private function createHuaweiNovaSeries($huawei, $cat)
    {
        $products = [
            [
                'ma'=>'HW-N12I','ten'=>'HUAWEI nova 12i','duong_dan'=>'huawei-nova-12i',
                'mo_ta_ngan'=>'Thiết kế trẻ, camera 108MP.',
                'mo_ta'=>'nova 12i hướng tới người trẻ, chụp ảnh đẹp, pin tốt.',
                'gia_co_ban'=>6990000,'noi_bat'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'LCD 90Hz',
                    'he_dieu_hanh'=>'EMUI / HarmonyOS','bo_xu_ly'=>'Snapdragon 680','ram'=>'8GB',
                    'camera_sau'=>'108MP + 2MP depth','camera_truoc'=>'8MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 40W',
                    'ket_noi'=>['4G','Wi-Fi 5','Bluetooth 5.0','NFC'],
                    'chong_nuoc'=>'—','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh lá','bo_nho'=>'128GB','gia'=>6990000,'ton_kho'=>30,'hinh'=>'https://consumer.huawei.com/content/dam/huawei-cbg-site/common/mkt/pdp/admin-image/phones/nova-12i/list/green.png'],
                    ['mau_sac'=>'Đen','bo_nho'=>'256GB','gia'=>7690000,'ton_kho'=>18,'hinh'=>'https://freddiescorneronline.com/wp-content/uploads/2024/08/IMG_3402-600x600.jpeg'],
                ],
            ],
            [
                'ma'=>'HW-N12S','ten'=>'HUAWEI nova 12s','duong_dan'=>'huawei-nova-12s',
                'mo_ta_ngan'=>'Mỏng nhẹ, chụp selfie đẹp.',
                'mo_ta'=>'Màn đẹp, camera trước chất lượng, thiết kế thời trang.',
                'gia_co_ban'=>8990000,'noi_bat'=>false,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'OLED 120Hz',
                    'he_dieu_hanh'=>'EMUI / HarmonyOS','bo_xu_ly'=>'Snapdragon 778G','ram'=>'8GB',
                    'camera_sau'=>'50MP + 8MP ultra-wide + 2MP','camera_truoc'=>'60MP',
                    'dung_luong_pin'=>'4500 mAh','loai_sac'=>'USB-C, 66W',
                    'ket_noi'=>['4G','Wi-Fi 6','Bluetooth 5.2','NFC'],
                    'chong_nuoc'=>'—','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh Biển','bo_nho'=>'256GB','gia'=>8990000,'ton_kho'=>22,'hinh'=>'https://consumer.huawei.com/content/dam/huawei-cbg-site/common/mkt/pdp/admin-image/phones/nova-12s/list/blue.png'],
                    ['mau_sac'=>'Trắng','bo_nho'=>'256GB','gia'=>8990000,'ton_kho'=>18,'hinh'=>'https://m.media-amazon.com/images/I/51sN3qjwB3L._UF1000,1000_QL80_.jpg'],
                ],
            ],
        ];

        foreach ($products as $p) $this->createProduct($p, $huawei, $cat);
        echo "✓ Đã tạo HUAWEI nova series\n";
    }

    private function createNokiaFeaturePhones($nokia, $cat)
    {
        $products = [
            [
                'ma'=>'NK-110-DS-PRO-4G','ten'=>'Nokia 110 DS Pro 4G','duong_dan'=>'nokia-110-ds-pro-4g',
                'mo_ta_ngan'=>'Máy phổ thông 4G, 2 SIM.',
                'mo_ta'=>'Thiết bị nghe gọi bền bỉ, pin tốt, hỗ trợ 4G, 2 SIM.',
                'gia_co_ban'=>890000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'1.8 inch','do_phan_giai'=>'QQVGA','loai_man_hinh'=>'TFT',
                    'he_dieu_hanh'=>'S30+','bo_xu_ly'=>'—','ram'=>'—',
                    'camera_sau'=>'—','camera_truoc'=>'—',
                    'dung_luong_pin'=>'1000 mAh','loai_sac'=>'Micro-USB',
                    'ket_noi'=>['4G VoLTE','Bluetooth 5.0','FM'],
                    'chong_nuoc'=>'—','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>false,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Tím','bo_nho'=>'—','gia'=>890000,'ton_kho'=>60,'hinh'=>'https://cdn11.dienmaycholon.vn/filewebdmclnew/DMCL21/Picture/Apro/Apro_product_33460/nokia-110-4g-pro-main-33460.png'],
                    ['mau_sac'=>'Xanh','bo_nho'=>'—','gia'=>890000,'ton_kho'=>40,'hinh'=>'https://cdn11.dienmaycholon.vn/filewebdmclnew/DMCL21/Picture//Apro/Apro_color_1625/nokia-110-4g-pr_main_478_1020.png.webp'],
                ],
            ],
            [
                'ma'=>'NK-105-DS-PRO-4G','ten'=>'Nokia 105 DS Pro 4G','duong_dan'=>'nokia-105-ds-pro-4g',
                'mo_ta_ngan'=>'Nhỏ gọn, pin trâu.',
                'mo_ta'=>'Máy nghe gọi cơ bản, 4G, bền bỉ, dễ dùng.',
                'gia_co_ban'=>690000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'1.8 inch','do_phan_giai'=>'QQVGA','loai_man_hinh'=>'TFT',
                    'he_dieu_hanh'=>'S30+','bo_xu_ly'=>'—','ram'=>'—',
                    'camera_sau'=>'—','camera_truoc'=>'—',
                    'dung_luong_pin'=>'1000 mAh','loai_sac'=>'Micro-USB',
                    'ket_noi'=>['4G VoLTE','Bluetooth 5.0','FM'],
                    'chong_nuoc'=>'—','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>false,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh','bo_nho'=>'—','gia'=>690000,'ton_kho'=>80,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/311033/nokia-105-4g-pro-xanh-1-750x500.jpg'],
                    ['mau_sac'=>'Đen','bo_nho'=>'—','gia'=>690000,'ton_kho'=>50,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/311033/nokia-105-4g-pro-1-1-750x500.jpg'],
                ],
            ],
            [
                'ma'=>'NK-5710-XA','ten'=>'Nokia 5710 XpressAudio','duong_dan'=>'nokia-5710-xpressaudio',
                'mo_ta_ngan'=>'Tích hợp tai nghe không dây.',
                'mo_ta'=>'Điện thoại phổ thông có ngăn chứa tai nghe không dây, tiện lợi cho nghe nhạc.',
                'gia_co_ban'=>1590000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'2.4 inch','do_phan_giai'=>'QVGA','loai_man_hinh'=>'TFT',
                    'he_dieu_hanh'=>'S30+','bo_xu_ly'=>'—','ram'=>'—',
                    'camera_sau'=>'VGA','camera_truoc'=>'—',
                    'dung_luong_pin'=>'1450 mAh','loai_sac'=>'Micro-USB',
                    'ket_noi'=>['4G','Bluetooth','FM','MP3'],
                    'chong_nuoc'=>'—','cam_bien_van_tay'=>false,'mo_khoa_khuon_mat'=>false,'thoi_gian_bao_hanh'=>'12 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Đen/Đỏ','bo_nho'=>'—','gia'=>1590000,'ton_kho'=>35,'hinh'=>'https://cdn2.cellphones.com.vn/358x/media/catalog/product/n/o/nokia5710xpressaudioteaser_1.png'],
                    ['mau_sac'=>'Trắng/Đỏ','bo_nho'=>'—','gia'=>1590000,'ton_kho'=>20,'hinh'=>'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/n/o/nokia-5701.jpg'],
                ],
            ],
        ];

        foreach ($products as $p) $this->createProduct($p, $nokia, $cat);
        echo "✓ Đã tạo Nokia feature phones\n";
    }

    private function createOppoVivoXiaomi($oppo, $vivo, $xiaomi, $cat, $budgetCat)
    {
        // OPPO
        $oppoProducts = [
            [
                'ma'=>'OP-R12','ten'=>'OPPO Reno12 5G','duong_dan'=>'oppo-reno12-5g',
                'mo_ta_ngan'=>'Thiết kế mỏng nhẹ, AI chân dung.',
                'mo_ta'=>'Reno12 5G chú trọng camera chân dung AI, sạc nhanh, màn 120Hz.',
                'gia_co_ban'=>10990000,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.7 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'AMOLED 120Hz',
                    'he_dieu_hanh'=>'Android 14 (ColorOS)','bo_xu_ly'=>'Dimensity 7300','ram'=>'12GB',
                    'camera_sau'=>'50MP + 8MP ultra-wide + 2MP','camera_truoc'=>'32MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 80W',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP65','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Nâu Đồng','bo_nho'=>'256GB','gia'=>10990000,'ton_kho'=>22,'hinh'=>'https://cdn2.cellphones.com.vn/358x/media/catalog/product/t/e/text_ng_n_5__7_71.png'],
                    ['mau_sac'=>'Bạc','bo_nho'=>'256GB','gia'=>10990000,'ton_kho'=>18,'hinh'=>'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_6__2_114.png'],
                ],
            ],
            [
                'ma'=>'OP-A79','ten'=>'OPPO A79 5G','duong_dan'=>'oppo-a79-5g',
                'mo_ta_ngan'=>'Bền bỉ, sạc nhanh.',
                'mo_ta'=>'A79 5G pin lớn, sạc nhanh, phù hợp người dùng phổ thông.',
                'gia_co_ban'=>6490000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.72 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'IPS 90Hz',
                    'he_dieu_hanh'=>'Android 13/14 (ColorOS)','bo_xu_ly'=>'Dimensity 6020','ram'=>'8GB',
                    'camera_sau'=>'50MP + 2MP','camera_truoc'=>'8MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 33W',
                    'ket_noi'=>['5G','Wi-Fi 5','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP54','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Đen','bo_nho'=>'128GB','gia'=>6490000,'ton_kho'=>40,'hinh'=>'https://didongviet.vn/_next/image?url=https%3A%2F%2Fcdn-v2.didongviet.vn%2Ffiles%2Fproducts%2F2024%2F5%2F25%2F1%2F1719296448084_oppo_a79_black_didongviet.png&w=384&q=75'],
                    ['mau_sac'=>'Tím','bo_nho'=>'128GB','gia'=>6490000,'ton_kho'=>30,'hinh'=>'https://didongviet.vn/_next/image?url=https%3A%2F%2Fcdn-v2.didongviet.vn%2Ffiles%2Fproducts%2F2024%2F5%2F25%2F1%2F1719296445650_oppo_a79_purple_didongviet.png&w=384&q=75'],
                ],
            ],
        ];
        foreach ($oppoProducts as $p) {
            $this->createProduct($p, $oppo, $p['ma']==='OP-A79' ? $budgetCat : $cat);
        }

        // vivo
        $vivoProducts = [
            [
                'ma'=>'VV-V30','ten'=>'vivo V30','duong_dan'=>'vivo-v30',
                'mo_ta_ngan'=>'Aura Light, ảnh chân dung đẹp.',
                'mo_ta'=>'vivo V30 nổi bật với Aura Light, camera chân dung, màn AMOLED 120Hz.',
                'gia_co_ban'=>9990000,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.78 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'AMOLED 120Hz',
                    'he_dieu_hanh'=>'Android 14 (FuntouchOS)','bo_xu_ly'=>'Snapdragon 7 Gen 3','ram'=>'12GB',
                    'camera_sau'=>'50MP + 50MP ultra-wide','camera_truoc'=>'50MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 80W',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.3','NFC'],
                    'chong_nuoc'=>'IP54','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh','bo_nho'=>'256GB','gia'=>9990000,'ton_kho'=>25,'hinh'=>'https://asia-exstatic-vivofs.vivo.com/PSee2l50xoirPK7y/1708657158367/ff3bb545e52898e898fd2d46fe828293.png'],
                    ['mau_sac'=>'Đen','bo_nho'=>'256GB','gia'=>9990000,'ton_kho'=>20,'hinh'=>'https://i.ebayimg.com/images/g/7HwAAeSwKLBoh3Ih/s-l1600.webp'],
                ],
            ],
            [
                'ma'=>'VV-Y36','ten'=>'vivo Y36','duong_dan'=>'vivo-y36',
                'mo_ta_ngan'=>'Giá tốt, ngoại hình đẹp.',
                'mo_ta'=>'Y36 phù hợp nhu cầu cơ bản: pin tốt, thiết kế bóng bẩy.',
                'gia_co_ban'=>5290000,'noi_bat'=>false,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.64 inch','do_phan_giai'=>'FHD+','loai_man_hinh'=>'IPS 90Hz',
                    'he_dieu_hanh'=>'Android 13/14 (FuntouchOS)','bo_xu_ly'=>'Snapdragon 680','ram'=>'8GB',
                    'camera_sau'=>'50MP + 2MP','camera_truoc'=>'16MP',
                    'dung_luong_pin'=>'5000 mAh','loai_sac'=>'USB-C, 44W',
                    'ket_noi'=>['4G','Wi-Fi 5','Bluetooth 5.1','NFC'],
                    'chong_nuoc'=>'IP54','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh','bo_nho'=>'128GB','gia'=>5290000,'ton_kho'=>45,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/311120/vivo-y36-xanh-thumbnew-600x600.jpg'],
                    ['mau_sac'=>'Đen','bo_nho'=>'128GB','gia'=>5290000,'ton_kho'=>35,'hinh'=>'https://cdn.tgdd.vn/Products/Images/42/311120/vivo-y36-den-1-750x500.jpg'],
                ],
            ],
        ];
        foreach ($vivoProducts as $p) {
            $this->createProduct($p, $vivo, $p['ma']==='VV-Y36' ? $budgetCat : $cat);
        }

        // Xiaomi
        $xiaomiProducts = [
            [
                'ma'=>'MI-RN13P5G','ten'=>'Redmi Note 13 Pro 5G','duong_dan'=>'redmi-note-13-pro-5g',
                'mo_ta_ngan'=>'200MP, màn 120Hz.',
                'mo_ta'=>'Dòng Note nổi tiếng với cấu hình cao, camera 200MP, giá tốt.',
                'gia_co_ban'=>7990000,'noi_bat'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.67 inch','do_phan_giai'=>'1.5K','loai_man_hinh'=>'AMOLED 120Hz',
                    'he_dieu_hanh'=>'Android 14 (HyperOS)','bo_xu_ly'=>'Snapdragon 7s Gen 2','ram'=>'8GB',
                    'camera_sau'=>'200MP + 8MP ultra-wide + 2MP macro','camera_truoc'=>'16MP',
                    'dung_luong_pin'=>'5100 mAh','loai_sac'=>'USB-C, 67W',
                    'ket_noi'=>['5G','Wi-Fi 6','Bluetooth 5.2','NFC'],
                    'chong_nuoc'=>'IP54','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh','bo_nho'=>'256GB','gia'=>7990000,'ton_kho'=>40,'hinh'=>'https://cdn2.cellphones.com.vn/358x/media/catalog/product/2/0/20241135_3.png'],
                    ['mau_sac'=>'Đen','bo_nho'=>'512GB','gia'=>8990000,'ton_kho'=>20,'hinh'=>'https://cdn2.cellphones.com.vn/358x/media/catalog/product/p/h/photo_2024-12-20_17-03-54_2.jpg'],
                ],
            ],
            [
                'ma'=>'MI-14','ten'=>'Xiaomi 14','duong_dan'=>'xiaomi-14',
                'mo_ta_ngan'=>'Flagship Leica, SD 8 Gen 3.',
                'mo_ta'=>'Xiaomi 14 hợp tác Leica, camera mạnh, hiệu năng hàng đầu.',
                'gia_co_ban'=>19990000,'noi_bat'=>true,
                'san_pham_moi'=>true,
                'thong_so'=>[
                    'kich_thuoc_man_hinh'=>'6.36 inch','do_phan_giai'=>'1.5K','loai_man_hinh'=>'LTPO AMOLED 120Hz',
                    'he_dieu_hanh'=>'Android 14 (HyperOS)','bo_xu_ly'=>'Snapdragon 8 Gen 3','ram'=>'12GB',
                    'camera_sau'=>'50MP main + 50MP tele + 50MP ultra-wide','camera_truoc'=>'32MP',
                    'dung_luong_pin'=>'4610 mAh','loai_sac'=>'USB-C, 90W',
                    'ket_noi'=>['5G','Wi-Fi 7','Bluetooth 5.4','NFC'],
                    'chong_nuoc'=>'IP68','cam_bien_van_tay'=>true,'mo_khoa_khuon_mat'=>true,'thoi_gian_bao_hanh'=>'18 tháng'
                ],
                'bien_the'=>[
                    ['mau_sac'=>'Xanh','bo_nho'=>'256GB','gia'=>19990000,'ton_kho'=>18,'hinh'=>'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/x/i/xiaomi-14_2_.png'],
                    ['mau_sac'=>'Đen','bo_nho'=>'512GB','gia'=>22990000,'ton_kho'=>10,'hinh'=>'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/x/i/xiaomi-14_2__5.png'],
                ],
            ],
        ];
        foreach ($xiaomiProducts as $p) $this->createProduct($p, $xiaomi, $cat);

        echo "✓ Đã tạo OPPO / vivo / Xiaomi\n";
    }


    private function getCategoryBySlug(string $slug): ?DanhMuc
    {
        return $this->em->getRepository(DanhMuc::class)->findOneBy(['duongDan' => $slug]);
    }
    private function createProduct($data, $brand, $category)
    {
        // Chống trùng theo slug sản phẩm
        $exists = $this->em->getRepository(SanPham::class)->findOneBy(['duongDan' => $data['duong_dan']]);
        if ($exists) {
            echo "  • Bỏ qua (đã tồn tại): {$data['ten']}\n";
            return;
        }
        // Tạo sản phẩm chính
        $product = new SanPham();
        $product->setMaSanPham($data['ma'])
            ->setTen($data['ten'])
            ->setDuongDan($data['duong_dan'])
            ->setThuongHieu($brand)
            ->setDanhMuc($category)
            ->setMoTaNgan($data['mo_ta_ngan'])
            ->setMoTa($data['mo_ta'])
            ->setGia($data['gia_co_ban'])
            ->setKichHoat(true)
            ->setNoiBat((bool)($data['noi_bat'] ?? false))
            ->setSpMoi((bool)($data['san_pham_moi'] ?? false))
            ->setTieuDeMeta($data['ten'] . ' - Chính hãng, giá tốt')
            ->setMoTaMeta($data['mo_ta_ngan']);

        if (method_exists($product, 'setSanPhamMoi')) {
            $product->setSanPhamMoi((bool)($data['san_pham_moi'] ?? false));
        }

        $this->em->persist($product);

        // Tạo thông số kỹ thuật
        $specs = new ThongSoSanPham();
        $specs->setSanPham($product)
            ->setKichThuocManHinh($data['thong_so']['kich_thuoc_man_hinh'])
            ->setDoPhanGiai($data['thong_so']['do_phan_giai'])
            ->setLoaiManHinh($data['thong_so']['loai_man_hinh'])
            ->setHeDieuHanh($data['thong_so']['he_dieu_hanh'])
            ->setBoXuLy($data['thong_so']['bo_xu_ly'])
            ->setRam($data['thong_so']['ram'])
            ->setCameraSau($data['thong_so']['camera_sau'])
            ->setCameraTruoc($data['thong_so']['camera_truoc'])
            ->setDungLuongPin($data['thong_so']['dung_luong_pin'])
            ->setLoaiSac($data['thong_so']['loai_sac'])
            ->setKetNoi($data['thong_so']['ket_noi'])
            ->setChongNuoc($data['thong_so']['chong_nuoc'])
            ->setCamBienVanTay($data['thong_so']['cam_bien_van_tay'])
            ->setMoKhoaKhuonMat($data['thong_so']['mo_khoa_khuon_mat'])
            ->setThoiGianBaoHanh($data['thong_so']['thoi_gian_bao_hanh']);

        $this->em->persist($specs);

        // Tạo biến thể sản phẩm
        foreach ($data['bien_the'] as $variantData) {
            $variant = new BienTheSanPham();
            $variant->setSanPham($product)
                ->setMaSanPham($data['ma'] . '-' . str_replace(' ', '', $variantData['mau_sac']) . '-' . $variantData['bo_nho'])
                ->setMauSac($variantData['mau_sac'])
                ->setBoNho($variantData['bo_nho'])
                ->setGia($variantData['gia'])
                ->setSoLuongTon($variantData['ton_kho'])
                ->setKichHoat(true);

            $this->em->persist($variant);

            // Tạo hình ảnh cho biến thể
            $image = new HinhAnhSanPham();
            $image->setSanPham($product)
                ->setBienThe($variant)
                ->setDuongDanHinh($variantData['hinh'])
                ->setTextThayThe($data['ten'] . ' ' . $variantData['mau_sac'])
                ->setThuTu(1)
                ->setHinhChinh(true);

            $this->em->persist($image);
        }

        $this->em->flush();
        echo "  ✓ Đã tạo sản phẩm: {$data['ten']}\n";
    }

    private function createSampleOrders()
    {
        echo "
✓ Bắt đầu tạo đơn hàng mẫu...\n";

        // Lấy tất cả biến thể sản phẩm
        $variants = $this->em->getRepository(BienTheSanPham::class)->findAll();
        if (count($variants) === 0) {
            echo "Không có biến thể để tạo đơn hàng.\n";
            return;
        }

        // Tạo 5 đơn hàng mẫu
        for ($i = 1; $i <= 5; $i++) {
            // Chọn ngẫu nhiên 1 biến thể và số lượng
            $variant = $variants[array_rand($variants)];
            $quantity = rand(1, 3);

            // Tính toán các khoản
            $unitPrice = $variant->getGia();
            $subTotal = $unitPrice * $quantity;
            $tax = round($subTotal * 0.1);
            $shipping = 30000;
            $discount = 0; // Có thể thay đổi nếu cần

            // Tạo đơn hàng
            $order = new DonHang();
            $order->setEmailKhach("khach{$i}@example.com")
                ->setTongPhu($subTotal)
                ->setTienThue($tax)
                ->setPhiVanChuyen($shipping)
                ->setTienGiamGia($discount)
                ->setTienTe('VND');
            $this->em->persist($order);

            // Tạo chi tiết đơn hàng
            $detail = new ChiTietDonHang();
            $detail->setDonHang($order)
                ->setBienTheSanPham($variant)
                ->setTenSanPham($variant->getSanPham()->getTen())
                ->setMaSanPham($variant->getMaSanPham())
                ->setChiTietBienThe([
                    'mau_sac' => $variant->getMauSac(),
                    'bo_nho' => $variant->getBoNho()
                ])
                ->setSoLuong($quantity)
                ->setGiaDonVi($unitPrice);
            $this->em->persist($detail);

            // Tạo thanh toán (COD)
            $payment = new ThanhToan();
            $payment->setDonHang($order)
                ->setPhuongThucThanhToan('COD')
                ->setCongThanhToan('Cash')
                ->setSoTien($order->getTongTien())
                ->setTienTe('VND');
            $this->em->persist($payment);

            // Lưu vào DB
            $this->em->flush();
            echo "  ✓ Đã tạo đơn hàng mẫu: {$order->getMaDonHang()}\n";
        }

        echo "✓ Hoàn thành tạo 5 đơn hàng mẫu.\n";
    }

    private function createCompletedOrders()
    {
        echo "\n✓ Bắt đầu tạo 20 đơn hàng mẫu đã hoàn thành (trải dài nhiều năm, tháng, ngày)...\n";

        // Lấy tất cả biến thể sản phẩm
        $variants = $this->em->getRepository(BienTheSanPham::class)->findAll();
        if (empty($variants)) {
            echo "Không có biến thể để tạo đơn hàng.\n";
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            // Chọn biến thể và số lượng
            $variant   = $variants[array_rand($variants)];
            $quantity  = rand(1, 3);
            $unitPrice = $variant->getGia();
            $subTotal  = $unitPrice * $quantity;
            $tax       = round($subTotal * 0.1);
            $shipping  = 0;
            $discount  = rand(0, 50000);

            // Sinh ngày giao ngẫu nhiên: năm (0-5), tháng (0-11), ngày (0-364) trước
            $yearsAgo  = rand(0, 1);
            $monthsAgo = rand(0, 11);
            $daysAgo   = rand(0, 364);
            $dateGiao  = (new \DateTime())->modify("-{$yearsAgo} years -{$monthsAgo} months -{$daysAgo} days");
            $dateNhan  = (clone $dateGiao)->modify('+' . rand(1, 7) . ' days');
            $dateTao   =  (clone $dateGiao)->modify('+' . rand(1, 7) . ' days');

            // Tạo đơn hàng hoàn thành
            $order = new DonHang();
            $order->setEmailKhach("khach{$i}@example.com")
                ->setTongPhu($subTotal)
                ->setTienThue($tax)
                ->setPhiVanChuyen($shipping)
                ->setTienGiamGia($discount)
                ->setTienTe('VND')
                ->setTrangThai('hoan_thanh')
                ->setNgayGiao($dateGiao)
                ->setNgayTao($dateNhan)
                ->setNgayNhan($dateTao);
            $this->em->persist($order);

            // Tạo chi tiết đơn hàng
            $detail = new ChiTietDonHang();
            $detail->setDonHang($order)
                ->setBienTheSanPham($variant)
                ->setTenSanPham($variant->getSanPham()->getTen())
                ->setMaSanPham($variant->getMaSanPham())
                ->setChiTietBienThe([
                    'mau_sac' => $variant->getMauSac(),
                    'bo_nho'   => $variant->getBoNho(),
                ])
                ->setGiaDonVi($unitPrice)
                ->setSoLuong($quantity);
            $this->em->persist($detail);

            // Tạo thanh toán hoàn thành
            $payment = new ThanhToan();
            $payment->setDonHang($order)
                ->setPhuongThucThanhToan('COD')
                ->setCongThanhToan('Cash')
                ->setSoTien($order->getTongTien())
                ->setTienTe('VND')
                ->setTrangThai('hoan_thanh');
            $this->em->persist($payment);

            // Lưu vào DB
            $this->em->flush();

            echo "  ✓ Đơn #{$i}: {$order->getMaDonHang()} (Giao: {$dateGiao->format('Y-m-d')}, Nhận: {$dateNhan->format('Y-m-d')})\n";
        }

        echo "✓ Hoàn tất tạo 20 đơn hàng mẫu đã hoàn thành.\n";
    }
}

// Chạy script seed
$seeder = new SeedDataVietnam();
$seeder->run();
