


CREATE DATABASE IF NOT EXISTS CuaHangMayTinhPHP CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE CuaHangMayTinhPHP;

CREATE TABLE danh_mucs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_danh_muc VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO danh_mucs (ten_danh_muc) VALUES
('Điện thoại thông minh'),
('Điện thoại phổ thông'),
('Máy tính bảng'),
('Phụ kiện điện thoại'),
('Ốp lưng & Miếng dán'),
('Tai nghe & Loa Bluetooth'),
('Sạc dự phòng & Cáp sạc'),
('Thẻ nhớ & USB'),
('Đồng hồ thông minh'),
('Thiết bị khác');

-- ============================================
-- BẢNG SẢN PHẨM (dien_thoais)
-- ============================================
CREATE TABLE dien_thoais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_sp VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    gia VARCHAR(50),
    danh_muc_id INT,
    so_luong_kho INT,
    thuong_hieu VARCHAR(100),
    he_dieu_hanh VARCHAR(50),
    man_hinh VARCHAR(50),
    camera VARCHAR(100),
    bo_nho_ram VARCHAR(50),
    bo_nho_trong VARCHAR(50),
    pin VARCHAR(50),
    mau_sac VARCHAR(50),
    duong_dan VARCHAR(100),
    FOREIGN KEY (danh_muc_id) REFERENCES danh_mucs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DỮ LIỆU MẪU
-- ============================================
INSERT INTO dien_thoais 
(ten_sp, mo_ta, gia, danh_muc_id, so_luong_kho, thuong_hieu, he_dieu_hanh, man_hinh, camera, bo_nho_ram, bo_nho_trong, pin, mau_sac, duong_dan)
VALUES
('iPhone 15 Pro Max', 'Siêu phẩm của Apple với chip A17 Pro, camera 48MP, pin lâu', '33,990,000', 1, 20, 'Apple', 'iOS 17', '6.7"', '48MP + 12MP + 12MP', '8GB', '256GB', '4422mAh', 'Titan Xám', 'iphone15promax.jpg'),
('iPhone 14', 'Thiết kế sang trọng, hiệu năng ổn định, camera sắc nét', '21,490,000', 1, 25, 'Apple', 'iOS 16', '6.1"', '12MP + 12MP', '6GB', '128GB', '3279mAh', 'Xanh dương', 'iphone14.jpg'),
('Samsung Galaxy S24 Ultra', 'Flagship Android mạnh mẽ nhất, camera zoom 100x', '31,990,000', 1, 15, 'Samsung', 'Android 14', '6.8"', '200MP + 12MP + 10MP + 10MP', '12GB', '512GB', '5000mAh', 'Đen', 's24ultra.jpg'),
('Samsung Galaxy A55', 'Điện thoại tầm trung mạnh mẽ, pin bền, camera đẹp', '9,990,000', 1, 40, 'Samsung', 'Android 14', '6.6"', '50MP + 12MP + 5MP', '8GB', '256GB', '5000mAh', 'Xanh nhạt', 'galaxya55.jpg'),
('Xiaomi 14 Ultra', 'Flagship Leica camera, hiệu năng mạnh mẽ', '24,990,000', 1, 10, 'Xiaomi', 'Android 14', '6.7"', '50MP x 4', '16GB', '512GB', '5000mAh', 'Trắng', 'xiaomi14ultra.jpg'),
('OPPO Reno12 Pro', 'Điện thoại thời trang, selfie siêu đẹp', '12,490,000', 1, 25, 'OPPO', 'Android 14', '6.7"', '50MP + 32MP', '12GB', '256GB', '4600mAh', 'Tím', 'reno12pro.jpg'),
('vivo V30', 'Điện thoại mỏng nhẹ, camera Zeiss chất lượng cao', '10,990,000', 1, 30, 'vivo', 'Android 14', '6.78"', '50MP + 50MP', '8GB', '256GB', '5000mAh', 'Xanh ngọc', 'vivov30.jpg'),
('realme 12+', 'Giá rẻ, pin khỏe, hiệu năng tốt', '7,490,000', 1, 50, 'realme', 'Android 14', '6.7"', '50MP + 8MP', '8GB', '256GB', '5000mAh', 'Xanh lá', 'realme12plus.jpg'),
('Nokia 105 4G', 'Điện thoại phổ thông pin trâu, có FM radio', '790,000', 2, 100, 'Nokia', 'KaiOS', '1.8"', 'Không', '64MB', '128MB', '1000mAh', 'Đen', 'nokia105.jpg'),
('iPad Pro M2 11"', 'Máy tính bảng hiệu năng cao, hỗ trợ bút Apple Pencil 2', '23,990,000', 3, 10, 'Apple', 'iPadOS 17', '11"', '12MP + 10MP', '8GB', '256GB', '7538mAh', 'Bạc', 'ipadprom2.jpg'),
('AirPods Pro 2', 'Tai nghe chống ồn cao cấp, hỗ trợ Spatial Audio', '5,990,000', 6, 30, 'Apple', '-', '-', '-', '-', '-', '5h', 'Trắng', 'airpodspro2.jpg'),
('Samsung Galaxy Buds 2 Pro', 'Tai nghe không dây cao cấp, âm thanh vòm 360', '4,490,000', 6, 25, 'Samsung', '-', '-', '-', '-', '-', '5h', 'Tím nhạt', 'buds2pro.jpg'),
('Anker PowerCore 10000mAh', 'Sạc dự phòng nhỏ gọn, hỗ trợ sạc nhanh 18W', '890,000', 7, 40, 'Anker', '-', '-', '-', '-', '-', '10000mAh', 'Đen', 'anker10000.jpg'),
('Baseus 3in1 Type-C Cable', 'Cáp sạc nhanh đa năng, hỗ trợ iPhone và Android', '290,000', 7, 60, 'Baseus', '-', '-', '-', '-', '-', '-', 'Bạc', 'baseus3in1.jpg'),
('Samsung EVO Plus 128GB', 'Thẻ nhớ tốc độ cao chuẩn U3, quay video 4K', '490,000', 8, 80, 'Samsung', '-', '-', '-', '-', '-', '-', 'Đỏ', 'samsungevo128.jpg'),
('Xiaomi Smart Band 8', 'Vòng đeo tay thông minh, đo nhịp tim, oxy, bước chân', '1,290,000', 9, 35, 'Xiaomi', '-', '1.6"', '-', '-', '128MB', '200mAh', 'Đen', 'band8.jpg'),
('Apple Watch Series 9', 'Đồng hồ cao cấp, theo dõi sức khỏe, chống nước', '10,490,000', 9, 15, 'Apple', 'watchOS 10', '1.9"', '-', '-', '64GB', '308mAh', 'Hồng', 'watchs9.jpg');

-- ============================================
-- BẢNG KHÁCH HÀNG
-- ============================================
CREATE TABLE khach_hangs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_khach_hang VARCHAR(255),
    email VARCHAR(255),
    mat_khau VARCHAR(255),
    so_dien_thoai VARCHAR(50),
    dia_chi VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- BẢNG ĐƠN HÀNG
-- ============================================
CREATE TABLE don_hangs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khach_hang_id INT,
    ngay_dat_hang DATETIME DEFAULT CURRENT_TIMESTAMP,
    tong_tien VARCHAR(50),
    trang_thai VARCHAR(50),
    phuong_thuc_thanh_toan VARCHAR(50),
    dia_chi_giao_hang VARCHAR(255),
    FOREIGN KEY (khach_hang_id) REFERENCES khach_hangs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- BẢNG CHI TIẾT ĐƠN HÀNG
-- ============================================
CREATE TABLE chi_tiet_don_hangs (
    don_hang_id INT,
    dien_thoai_id INT,
    so_luong INT,
    gia VARCHAR(50),
    PRIMARY KEY (don_hang_id, dien_thoai_id),
    FOREIGN KEY (don_hang_id) REFERENCES don_hangs(id),
    FOREIGN KEY (dien_thoai_id) REFERENCES dien_thoais(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- BẢNG ĐÁNH GIÁ
-- ============================================
CREATE TABLE danh_gias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khach_hang_id INT,
    dien_thoai_id INT,
    diem_danh_gia INT CHECK (diem_danh_gia BETWEEN 1 AND 5),
    noi_dung_danh_gia TEXT,
    ngay_danh_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (khach_hang_id) REFERENCES khach_hangs(id),
    FOREIGN KEY (dien_thoai_id) REFERENCES dien_thoais(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- BẢNG LIÊN HỆ
-- ============================================
CREATE TABLE lien_hes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khach_hang_id INT,
    noi_dung TEXT,
    FOREIGN KEY (khach_hang_id) REFERENCES khach_hangs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

