


CREATE DATABASE IF NOT EXISTS CuaHangMayTinhPHP CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE CuaHangMayTinhPHP;


CREATE TABLE danh_mucs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_danh_muc VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO danh_mucs (ten_danh_muc) VALUES
('Laptop'),
('Chuột máy tính'),
('Bàn phím'),
('Tai nghe'),
('Màn hình'),
('Ổ cứng di động'),
('USB / Thẻ nhớ'),
('Webcam'),
('Loa vi tính'),
('Phụ kiện khác');

-- ============================================
-- BẢNG SẢN PHẨM (may_tinhs)
-- ============================================
CREATE TABLE may_tinhs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_sp VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    gia VARCHAR(50),
    danh_muc_id INT,
    so_luong_kho INT,
    thuong_hieu VARCHAR(100),
    vi_xu_ly VARCHAR(100),
    ram VARCHAR(50),
    luu_tru VARCHAR(50),
    kich_thuoc_man_hinh VARCHAR(50),
    mau_sac VARCHAR(50),
    dung_luong_pin VARCHAR(50),
    duong_dan VARCHAR(100),
    FOREIGN KEY (danh_muc_id) REFERENCES danh_mucs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO may_tinhs (ten_sp, mo_ta, gia, danh_muc_id, so_luong_kho, thuong_hieu, vi_xu_ly, ram, luu_tru, kich_thuoc_man_hinh, mau_sac, dung_luong_pin, duong_dan) VALUES
('Dell XPS 13', 'Laptop cao cấp, mỏng nhẹ, pin lâu', '32,990,000', 1, 15, 'Dell', 'Intel Core i7', '16GB', '512GB SSD', '13.4"', 'Bạc', '12h', 'dellxps13.jpg'),
('Dell Inspiron 15', 'Hiệu năng ổn định, pin tốt', '18,490,000', 1, 20, 'Dell', 'Intel Core i5', '8GB', '512GB SSD', '15.6"', 'Bạc', '10h', 'inspiron15.jpg'),
('Asus TUF Gaming F15', 'Laptop gaming hiệu năng cao', '25,990,000', 1, 10, 'Asus', 'Intel Core i7', '16GB', '1TB SSD', '15.6"', 'Đen', '8h', 'asustuf15.jpg'),
('Asus Zenbook 14', 'Siêu mỏng nhẹ, pin 15 tiếng', '22,990,000', 1, 12, 'Asus', 'Intel Core i5', '16GB', '512GB SSD', '14"', 'Xanh', '15h', 'zenbook14.jpg'),
('HP Envy 13', 'Màn hình cảm ứng, pin tốt', '19,990,000', 1, 10, 'HP', 'Intel Core i5', '8GB', '512GB SSD', '13.3"', 'Vàng', '12h', 'hpenvy13.jpg'),
('HP Victus 16', 'Laptop gaming hiệu năng cao', '26,990,000', 1, 8, 'HP', 'Ryzen 7', '16GB', '1TB SSD', '16.1"', 'Đen', '9h', 'hpvictus16.jpg'),
('Lenovo IdeaPad 5', 'Laptop học tập văn phòng', '15,490,000', 1, 25, 'Lenovo', 'Ryzen 5', '8GB', '512GB SSD', '14"', 'Xám', '10h', 'ideapad5.jpg'),
('Lenovo Legion 5', 'Gaming mạnh mẽ, tản nhiệt tốt', '28,990,000', 1, 7, 'Lenovo', 'Ryzen 7', '16GB', '1TB SSD', '15.6"', 'Đen', '8h', 'legion5.jpg'),
('MacBook Air M2', 'Chip M2 mới, pin 18 tiếng', '33,990,000', 1, 20, 'Apple', 'Apple M2', '8GB', '512GB SSD', '13.6"', 'Bạc', '18h', 'macbookairm2.jpg'),
('MacBook Pro 14 M3', 'Hiệu năng cực mạnh, thiết kế sang', '47,990,000', 1, 5, 'Apple', 'Apple M3', '16GB', '1TB SSD', '14"', 'Xám', '20h', 'macbookpro14.jpg'),
('Logitech MX Master 3', 'Chuột không dây cao cấp', '2,390,000', 2, 30, 'Logitech', '', '', '', '', 'Xám', '', 'logitechmx3.jpg'),
('Razer DeathAdder V3', 'Chuột gaming siêu nhạy', '1,790,000', 2, 40, 'Razer', '', '', '', '', 'Đen', '', 'razerda3.jpg'),
('Corsair K70 RGB', 'Bàn phím cơ RGB cao cấp', '2,990,000', 3, 25, 'Corsair', '', '', '', '', 'Đen', '', 'corsairk70.jpg'),
('Keychron K6', 'Bàn phím cơ không dây nhỏ gọn', '2,190,000', 3, 25, 'Keychron', '', '', '', '', 'Xám', '', 'keychronk6.jpg'),
('Sony WH-1000XM5', 'Tai nghe chống ồn cao cấp', '7,490,000', 4, 18, 'Sony', '', '', '', '', 'Đen', '', 'sonywh1000xm5.jpg'),
('AirPods Pro 2', 'Tai nghe không dây chính hãng', '5,990,000', 4, 22, 'Apple', '', '', '', '', 'Trắng', '', 'airpodspro2.jpg'),
('LG UltraGear 27"', 'Màn hình gaming 2K 165Hz', '6,890,000', 5, 12, 'LG', '', '', '', '27"', 'Đen', '', 'lgultragear27.jpg'),
('Samsung Odyssey G5', 'Màn hình cong 2K 144Hz', '7,290,000', 5, 10, 'Samsung', '', '', '', '27"', 'Đen', '', 'odysseyg5.jpg'),
('Seagate Backup Plus 2TB', 'Ổ cứng di động tốc độ cao', '2,090,000', 6, 40, 'Seagate', '', '', '', '', 'Đen', '', 'seagate2tb.jpg'),
('WD My Passport 4TB', 'Ổ cứng di động dung lượng lớn', '3,290,000', 6, 30, 'WD', '', '', '', '', 'Xanh', '', 'wd4tb.jpg'),
('Sandisk Ultra 64GB', 'USB 3.0 tốc độ cao', '290,000', 7, 100, 'Sandisk', '', '', '', '', 'Bạc', '', 'sandisk64gb.jpg'),
('Kingston 128GB', 'USB kim loại siêu bền', '390,000', 7, 80, 'Kingston', '', '', '', '', 'Bạc', '', 'kingston128.jpg'),
('Logitech C920', 'Webcam Full HD 1080p', '1,990,000', 8, 15, 'Logitech', '', '', '', '', 'Đen', '', 'c920.jpg'),
('Razer Kiyo', 'Webcam có đèn LED', '2,490,000', 8, 10, 'Razer', '', '', '', '', 'Đen', '', 'razerkiyo.jpg'),
('Microlab M108', 'Loa vi tính mini', '590,000', 9, 50, 'Microlab', '', '', '', '', 'Đen', '', 'm108.jpg'),
('Logitech Z407', 'Loa Bluetooth công suất lớn', '2,190,000', 9, 20, 'Logitech', '', '', '', '', 'Đen', '', 'z407.jpg'),
('Cooler Master Stand', 'Giá đỡ laptop kim loại', '690,000', 10, 25, 'Cooler Master', '', '', '', '', 'Xám', '', 'stand.jpg'),
('Anker Hub 7in1', 'Bộ chia cổng USB Type-C', '1,290,000', 10, 30, 'Anker', '', '', '', '', 'Bạc', '', 'ankerhub.jpg');

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
    may_tinh_id INT,
    so_luong INT,
    gia VARCHAR(50),
    PRIMARY KEY (don_hang_id, may_tinh_id),
    FOREIGN KEY (don_hang_id) REFERENCES don_hangs(id),
    FOREIGN KEY (may_tinh_id) REFERENCES may_tinhs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE danh_gias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khach_hang_id INT,
    may_tinh_id INT,
    diem_danh_gia INT CHECK (diem_danh_gia BETWEEN 1 AND 5),
    noi_dung_danh_gia TEXT,
    ngay_danh_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (khach_hang_id) REFERENCES khach_hangs(id),
    FOREIGN KEY (may_tinh_id) REFERENCES may_tinhs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE lien_hes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    khach_hang_id INT,
    noi_dung TEXT,
    FOREIGN KEY (khach_hang_id) REFERENCES khach_hangs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



