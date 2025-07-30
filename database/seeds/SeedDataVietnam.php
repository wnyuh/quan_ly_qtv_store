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

        // 2. Tạo danh mục
        $categories = $this->createCategories();

        // 3. Tạo sản phẩm iPhone với dữ liệu thực tế
        $this->createiPhoneProducts($apple, $categories['smartphone']);
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



    private function createiPhoneProducts($apple, $iphoneCategory)
    {
        $products = [
            [
                'ma' => 'IPHONE-X-128',
                'ten' => 'iPhone X 128GB',
                'duong_dan' => 'iphone-x-128gb',
                'mo_ta_ngan' => 'iPhone X với màn hình Super Retina 5.8 inch, Face ID và camera kép 12MP',
                'mo_ta' => 'iPhone X mang đến trải nghiệm hoàn toàn mới với thiết kế toàn màn hình, công nghệ Face ID tiên tiến và hiệu năng mạnh mẽ từ chip A11 Bionic. Màn hình Super Retina OLED 5.8 inch cho chất lượng hiển thị tuyệt vời.',
                'gia_co_ban' => 15990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '5.8 inch',
                    'do_phan_giai' => '2436 x 1125 pixels',
                    'loai_man_hinh' => 'Super Retina OLED',
                    'he_dieu_hanh' => 'iOS 11',
                    'bo_xu_ly' => 'Apple A11 Bionic',
                    'ram' => '3GB',
                    'camera_sau' => '12MP f/1.8 + 12MP f/2.4 (telephoto)',
                    'camera_truoc' => '7MP TrueDepth',
                    'dung_luong_pin' => '2716 mAh',
                    'loai_sac' => 'Lightning, Wireless charging',
                    'ket_noi' => ['4G LTE', 'WiFi 802.11ac', 'Bluetooth 5.0', 'NFC'],
                    'chong_nuoc' => 'IP67',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Bạc', 'bo_nho' => '128GB', 'gia' => 15990000, 'ton_kho' => 15, 'hinh' => 'IphoneXbac128.png'],
                    ['mau_sac' => 'Vàng', 'bo_nho' => '256GB', 'gia' => 18990000, 'ton_kho' => 10, 'hinh' => 'iphoneXS_vang.png']
                ]
            ],
            [
                'ma' => 'IPHONE-XS-MAX-512',
                'ten' => 'iPhone XS Max 512GB',
                'duong_dan' => 'iphone-xs-max-512gb',
                'mo_ta_ngan' => 'iPhone XS Max với màn hình 6.5 inch lớn nhất, chip A12 Bionic mạnh mẽ',
                'mo_ta' => 'iPhone XS Max là phiên bản màn hình lớn nhất của dòng iPhone XS với màn hình Super Retina 6.5 inch tuyệt đẹp. Được trang bị chip A12 Bionic với Neural Engine thế hệ mới, mang lại hiệu năng vượt trội và khả năng machine learning tiên tiến.',
                'gia_co_ban' => 32990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.5 inch',
                    'do_phan_giai' => '2688 x 1242 pixels',
                    'loai_man_hinh' => 'Super Retina OLED',
                    'he_dieu_hanh' => 'iOS 12',
                    'bo_xu_ly' => 'Apple A12 Bionic',
                    'ram' => '4GB',
                    'camera_sau' => '12MP f/1.8 + 12MP f/2.4 (telephoto)',
                    'camera_truoc' => '7MP TrueDepth',
                    'dung_luong_pin' => '3174 mAh',
                    'loai_sac' => 'Lightning, Wireless charging',
                    'ket_noi' => ['4G LTE', 'WiFi 802.11ac', 'Bluetooth 5.0', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Xám', 'bo_nho' => '512GB', 'gia' => 32990000, 'ton_kho' => 8, 'hinh' => 'iphone-xs-max-512gb-xam.png']
                ]
            ],
            [
                'ma' => 'IPHONE-11-128',
                'ten' => 'iPhone 11 128GB',
                'duong_dan' => 'iphone-11-128gb',
                'mo_ta_ngan' => 'iPhone 11 với camera kép 12MP, chip A13 Bionic và pin lâu hơn',
                'mo_ta' => 'iPhone 11 mang đến những tính năng tuyệt vời với giá cả hợp lý. Camera kép 12MP với chế độ Night mode, chip A13 Bionic nhanh nhất từ trước đến nay và thời lượng pin cải thiện đáng kể so với iPhone XR.',
                'gia_co_ban' => 16990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.1 inch',
                    'do_phan_giai' => '1792 x 828 pixels',
                    'loai_man_hinh' => 'Liquid Retina IPS LCD',
                    'he_dieu_hanh' => 'iOS 13',
                    'bo_xu_ly' => 'Apple A13 Bionic',
                    'ram' => '4GB',
                    'camera_sau' => '12MP f/1.8 + 12MP f/2.4 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '3110 mAh',
                    'loai_sac' => 'Lightning, Wireless charging',
                    'ket_noi' => ['4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.0', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Trắng', 'bo_nho' => '128GB', 'gia' => 16990000, 'ton_kho' => 20, 'hinh' => 'iphone-11_trang.png']
                ]
            ],
            [
                'ma' => 'IPHONE-11-PRO-256',
                'ten' => 'iPhone 11 Pro 256GB',
                'duong_dan' => 'iphone-11-pro-256gb',
                'mo_ta_ngan' => 'iPhone 11 Pro với camera ba ống kính chuyên nghiệp và màn hình Super Retina XDR',
                'mo_ta' => 'iPhone 11 Pro là đỉnh cao của công nghệ di động với hệ thống camera ba ống kính chuyên nghiệp, màn hình Super Retina XDR HDR10 và chip A13 Bionic. Thiết kế premium với khung thép không gỉ và mặt lưng kính.',
                'gia_co_ban' => 26990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '5.8 inch',
                    'do_phan_giai' => '2436 x 1125 pixels',
                    'loai_man_hinh' => 'Super Retina XDR OLED',
                    'he_dieu_hanh' => 'iOS 13',
                    'bo_xu_ly' => 'Apple A13 Bionic',
                    'ram' => '4GB',
                    'camera_sau' => '12MP f/1.8 + 12MP f/2.0 (telephoto) + 12MP f/2.4 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '3046 mAh',
                    'loai_sac' => 'Lightning, Wireless charging',
                    'ket_noi' => ['4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.0', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Vàng', 'bo_nho' => '256GB', 'gia' => 26990000, 'ton_kho' => 12, 'hinh' => 'iphone-11-pro-gold-200-750x500.png']
                ]
            ],
            [
                'ma' => 'IPHONE-11-PRO-MAX-256',
                'ten' => 'iPhone 11 Pro Max 256GB',
                'duong_dan' => 'iphone-11-pro-max-256gb',
                'mo_ta_ngan' => 'iPhone 11 Pro Max với màn hình lớn 6.5 inch và pin bền bỉ nhất',
                'mo_ta' => 'iPhone 11 Pro Max là chiếc iPhone lớn nhất và mạnh nhất với màn hình Super Retina XDR 6.5 inch, hệ thống camera ba ống kính Pro và thời lượng pin vượt trội. Lý tưởng cho những ai yêu thích màn hình lớn và chụp ảnh chuyên nghiệp.',
                'gia_co_ban' => 29990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.5 inch',
                    'do_phan_giai' => '2688 x 1242 pixels',
                    'loai_man_hinh' => 'Super Retina XDR OLED',
                    'he_dieu_hanh' => 'iOS 13',
                    'bo_xu_ly' => 'Apple A13 Bionic',
                    'ram' => '4GB',
                    'camera_sau' => '12MP f/1.8 + 12MP f/2.0 (telephoto) + 12MP f/2.4 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '3969 mAh',
                    'loai_sac' => 'Lightning, Wireless charging',
                    'ket_noi' => ['4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.0', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Vàng', 'bo_nho' => '256GB', 'gia' => 29990000, 'ton_kho' => 10, 'hinh' => 'iphone-11-pro-max-256gb-gold-200-750x500.png']
                ]
            ],
            [
                'ma' => 'IPHONE-12-128',
                'ten' => 'iPhone 12 128GB',
                'duong_dan' => 'iphone-12-128gb',
                'mo_ta_ngan' => 'iPhone 12 với 5G, chip A14 Bionic và thiết kế vuông vức mới',
                'mo_ta' => 'iPhone 12 đánh dấu bước tiến mới với kết nối 5G siêu nhanh, chip A14 Bionic 5nm tiên tiến và thiết kế vuông vức sang trọng. Màn hình Super Retina XDR và camera Night mode cải tiến mang lại trải nghiệm tuyệt vời.',
                'gia_co_ban' => 21990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.1 inch',
                    'do_phan_giai' => '2532 x 1170 pixels',
                    'loai_man_hinh' => 'Super Retina XDR OLED',
                    'he_dieu_hanh' => 'iOS 14',
                    'bo_xu_ly' => 'Apple A14 Bionic',
                    'ram' => '4GB',
                    'camera_sau' => '12MP f/1.6 + 12MP f/2.4 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '2815 mAh',
                    'loai_sac' => 'Lightning, MagSafe, Wireless charging',
                    'ket_noi' => ['5G', '4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.0', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Tím', 'bo_nho' => '128GB', 'gia' => 21990000, 'ton_kho' => 18, 'hinh' => 'iphone-12-tim.jpg']
                ]
            ],
            [
                'ma' => 'IPHONE-13-128',
                'ten' => 'iPhone 13 128GB',
                'duong_dan' => 'iphone-13-128gb',
                'mo_ta_ngan' => 'iPhone 13 với chip A15 Bionic, camera cải tiến và pin lâu hơn',
                'mo_ta' => 'iPhone 13 nâng cấp mạnh mẽ với chip A15 Bionic, hệ thống camera kép cải tiến với chế độ Cinematic mode và thời lượng pin tăng đáng kể. Màn hình Super Retina XDR sáng hơn 28% so với thế hệ trước.',
                'gia_co_ban' => 22990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.1 inch',
                    'do_phan_giai' => '2532 x 1170 pixels',
                    'loai_man_hinh' => 'Super Retina XDR OLED',
                    'he_dieu_hanh' => 'iOS 15',
                    'bo_xu_ly' => 'Apple A15 Bionic',
                    'ram' => '4GB',
                    'camera_sau' => '12MP f/1.6 + 12MP f/2.4 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '3240 mAh',
                    'loai_sac' => 'Lightning, MagSafe, Wireless charging',
                    'ket_noi' => ['5G', '4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.0', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Trắng', 'bo_nho' => '128GB', 'gia' => 22990000, 'ton_kho' => 25, 'hinh' => 'iphone-13-trang.jpg']
                ]
            ],
            [
                'ma' => 'IPHONE-14-128',
                'ten' => 'iPhone 14 128GB',
                'duong_dan' => 'iphone-14-128gb',
                'mo_ta_ngan' => 'iPhone 14 với camera chính 48MP, chip A15 Bionic cải tiến',
                'mo_ta' => 'iPhone 14 mang đến những cải tiến đáng kể với camera chính 48MP cho chất lượng ảnh sắc nét, tính năng an toàn Emergency SOS via satellite và thiết kế bền bỉ. Chip A15 Bionic được tối ưu hóa mang lại hiệu năng mượt mà.',
                'gia_co_ban' => 24990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.1 inch',
                    'do_phan_giai' => '2556 x 1179 pixels',
                    'loai_man_hinh' => 'Super Retina XDR OLED',
                    'he_dieu_hanh' => 'iOS 16',
                    'bo_xu_ly' => 'Apple A15 Bionic',
                    'ram' => '6GB',
                    'camera_sau' => '48MP f/1.5 + 12MP f/2.4 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '3279 mAh',
                    'loai_sac' => 'Lightning, MagSafe, Wireless charging',
                    'ket_noi' => ['5G', '4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.3', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Đen', 'bo_nho' => '128GB', 'gia' => 24990000, 'ton_kho' => 30, 'hinh' => 'iphone-14-den-glr-1-750x500.jpg']
                ]
            ],
            [
                'ma' => 'IPHONE-15-128',
                'ten' => 'iPhone 15 128GB',
                'duong_dan' => 'iphone-15-128gb',
                'mo_ta_ngan' => 'iPhone 15 với Dynamic Island, camera 48MP và cổng USB-C',
                'mo_ta' => 'iPhone 15 đem đến những đột phá mới với Dynamic Island độc đáo, camera chính 48MP cải tiến và lần đầu tiên sử dụng cổng USB-C. Chip A16 Bionic mạnh mẽ và thiết kế màu sắc trẻ trung, hiện đại.',
                'gia_co_ban' => 22990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.1 inch',
                    'do_phan_giai' => '2556 x 1179 pixels',
                    'loai_man_hinh' => 'Super Retina XDR OLED với Dynamic Island',
                    'he_dieu_hanh' => 'iOS 17',
                    'bo_xu_ly' => 'Apple A16 Bionic',
                    'ram' => '6GB',
                    'camera_sau' => '48MP f/1.6 + 12MP f/2.4 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '3349 mAh',
                    'loai_sac' => 'USB-C, MagSafe, Wireless charging',
                    'ket_noi' => ['5G', '4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.3', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Hồng', 'bo_nho' => '128GB', 'gia' => 22990000, 'ton_kho' => 35, 'hinh' => 'iphone-15-hong.jpg'],
                    ['mau_sac' => 'Hồng', 'bo_nho' => '256GB', 'gia' => 25990000, 'ton_kho' => 20, 'hinh' => 'iphone-15-hong.jpg']
                ]
            ],
            [
                'ma' => 'IPHONE-16-128',
                'ten' => 'iPhone 16 128GB',
                'duong_dan' => 'iphone-16-128gb',
                'mo_ta_ngan' => 'iPhone 16 mới nhất với chip A18, Camera Control và Apple Intelligence',
                'mo_ta' => 'iPhone 16 là thế hệ iPhone mới nhất với chip A18 tiên tiến, tính năng Camera Control cách mạng và Apple Intelligence. Thiết kế mới với màu sắc thời trang và khả năng chụp ảnh, quay video chuyên nghiệp.',
                'gia_co_ban' => 22990000,
                'thong_so' => [
                    'kich_thuoc_man_hinh' => '6.1 inch',
                    'do_phan_giai' => '2556 x 1179 pixels',
                    'loai_man_hinh' => 'Super Retina XDR OLED với Dynamic Island',
                    'he_dieu_hanh' => 'iOS 18',
                    'bo_xu_ly' => 'Apple A18',
                    'ram' => '8GB',
                    'camera_sau' => '48MP f/1.6 + 12MP f/2.2 (ultra-wide)',
                    'camera_truoc' => '12MP TrueDepth',
                    'dung_luong_pin' => '3561 mAh',
                    'loai_sac' => 'USB-C, MagSafe, Wireless charging',
                    'ket_noi' => ['5G', '4G LTE', 'WiFi 802.11ax', 'Bluetooth 5.3', 'NFC'],
                    'chong_nuoc' => 'IP68',
                    'cam_bien_van_tay' => false,
                    'mo_khoa_khuon_mat' => true,
                    'thoi_gian_bao_hanh' => '12 tháng'
                ],
                'bien_the' => [
                    ['mau_sac' => 'Xanh Lưu Ly', 'bo_nho' => '128GB', 'gia' => 22990000, 'ton_kho' => 40, 'hinh' => 'iphone-16-xanhluoly.jpg'],
                    ['mau_sac' => 'Xanh Lưu Ly', 'bo_nho' => '256GB', 'gia' => 25990000, 'ton_kho' => 25, 'hinh' => 'iphone-16-xanhluoly.jpg']
                ]
            ]
        ];

        foreach ($products as $productData) {
            $this->createProduct($productData, $apple, $iphoneCategory);
        }

        echo "✓ Đã tạo " . count($products) . " sản phẩm iPhone\n";
    }

    private function createProduct($data, $brand, $category)
    {
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
            ->setNoiBat(in_array($data['ma'], ['IPHONE-15-128', 'IPHONE-16-128']))
            ->setTieuDeMeta($data['ten'] . ' - Chính hãng, giá tốt')
            ->setMoTaMeta($data['mo_ta_ngan']);

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
            $shipping  = 30000;
            $discount  = rand(0, 50000);

            // Sinh ngày giao ngẫu nhiên: năm (0-5), tháng (0-11), ngày (0-364) trước
            $yearsAgo  = rand(0, 5);
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
