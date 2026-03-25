-- =============================================
-- EduManager - Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS edumanager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE edumanager;

-- Bảng Khoa
CREATE TABLE khoa (
    ma_khoa     VARCHAR(10)  PRIMARY KEY,
    ten_khoa    VARCHAR(100) NOT NULL,
    mo_ta       TEXT,
    truong_khoa VARCHAR(10),
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bảng Người dùng (Admin, Giảng viên, Sinh viên)
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50)  UNIQUE NOT NULL,
    password    VARCHAR(255) NOT NULL,
    email       VARCHAR(100) UNIQUE NOT NULL,
    ho_ten      VARCHAR(100) NOT NULL,
    role        ENUM('admin','giang_vien','sinh_vien') DEFAULT 'sinh_vien',
    avatar      VARCHAR(255),
    is_active   TINYINT(1)   DEFAULT 1,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bảng Giảng viên
CREATE TABLE giang_vien (
    ma_gv       VARCHAR(10)  PRIMARY KEY,
    user_id     INT          UNIQUE,
    ho_ten      VARCHAR(100) NOT NULL,
    email       VARCHAR(100) UNIQUE NOT NULL,
    ma_khoa     VARCHAR(10),
    hoc_vi      ENUM('Cu nhan','Thac si','Tien si','Pho giao su','Giao su') DEFAULT 'Thac si',
    dien_thoai  VARCHAR(15),
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_khoa)  REFERENCES khoa(ma_khoa) ON DELETE SET NULL,
    FOREIGN KEY (user_id)  REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Bảng Sinh viên
CREATE TABLE sinh_vien (
    ma_sv        VARCHAR(10)  PRIMARY KEY,
    user_id      INT          UNIQUE,
    ho_ten       VARCHAR(100) NOT NULL,
    email        VARCHAR(100) UNIQUE NOT NULL,
    ngay_sinh    DATE,
    gioi_tinh    ENUM('Nam','Nu','Khac') DEFAULT 'Nam',
    dia_chi      TEXT,
    ma_khoa      VARCHAR(10),
    lop          VARCHAR(20),
    nganh        VARCHAR(100),
    nam_nhap_hoc YEAR,
    trang_thai   ENUM('dang_hoc','tam_nghi','da_tot_nghiep') DEFAULT 'dang_hoc',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_khoa)  REFERENCES khoa(ma_khoa) ON DELETE SET NULL,
    FOREIGN KEY (user_id)  REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Bảng Môn học
CREATE TABLE mon_hoc (
    ma_mon         VARCHAR(10)  PRIMARY KEY,
    ten_mon        VARCHAR(150) NOT NULL,
    tin_chi        TINYINT      NOT NULL DEFAULT 3,
    ma_khoa        VARCHAR(10),
    mon_tien_quyet VARCHAR(10),
    mo_ta          TEXT,
    created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_khoa)        REFERENCES khoa(ma_khoa) ON DELETE SET NULL,
    FOREIGN KEY (mon_tien_quyet) REFERENCES mon_hoc(ma_mon) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Bảng Lớp học phần
CREATE TABLE lop_hoc_phan (
    ma_lop     VARCHAR(20)  PRIMARY KEY,
    ma_mon     VARCHAR(10),
    ma_gv      VARCHAR(10),
    hoc_ky     VARCHAR(10)  NOT NULL,
    nam_hoc    VARCHAR(9)   NOT NULL,
    si_so_max  INT          DEFAULT 40,
    si_so_hien INT          DEFAULT 0,
    phong_hoc  VARCHAR(20),
    lich_hoc   VARCHAR(100),
    trang_thai ENUM('mo','dong','ket_thuc') DEFAULT 'mo',
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_mon) REFERENCES mon_hoc(ma_mon) ON DELETE CASCADE,
    FOREIGN KEY (ma_gv)  REFERENCES giang_vien(ma_gv) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Bảng Đăng ký môn học
CREATE TABLE dang_ky (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    ma_sv      VARCHAR(10) NOT NULL,
    ma_lop     VARCHAR(20) NOT NULL,
    trang_thai ENUM('dang_ky','da_huy') DEFAULT 'dang_ky',
    ngay_dk    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sv_lop (ma_sv, ma_lop),
    FOREIGN KEY (ma_sv)  REFERENCES sinh_vien(ma_sv) ON DELETE CASCADE,
    FOREIGN KEY (ma_lop) REFERENCES lop_hoc_phan(ma_lop) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Bảng Điểm số
CREATE TABLE diem_so (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    ma_sv      VARCHAR(10)  NOT NULL,
    ma_lop     VARCHAR(20)  NOT NULL,
    diem_cc    DECIMAL(4,2) DEFAULT 0 COMMENT 'Chuyên cần 10%',
    diem_gk    DECIMAL(4,2) DEFAULT 0 COMMENT 'Giữa kỳ 30%',
    diem_ck    DECIMAL(4,2) DEFAULT 0 COMMENT 'Cuối kỳ 60%',
    diem_tong  DECIMAL(4,2) DEFAULT 0,
    xep_loai   VARCHAR(5),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_diem (ma_sv, ma_lop),
    FOREIGN KEY (ma_sv)  REFERENCES sinh_vien(ma_sv) ON DELETE CASCADE,
    FOREIGN KEY (ma_lop) REFERENCES lop_hoc_phan(ma_lop) ON DELETE CASCADE
) ENGINE=InnoDB;
-- Bảng Lịch học (chi tiết)
CREATE TABLE lich_hoc (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    ma_lop         VARCHAR(20)  NOT NULL,
    thu            TINYINT      NOT NULL COMMENT '2=Thứ 2, 3=Thứ 3,..., 8=Chủ nhật',
    tiet_bat_dau   TINYINT      NOT NULL COMMENT 'Tiết bắt đầu (1-12)',
    so_tiet        TINYINT      NOT NULL DEFAULT 2 COMMENT 'Số tiết học',
    phong_hoc      VARCHAR(20)  NOT NULL,
    tuan_bat_dau   INT          DEFAULT 1 COMMENT 'Tuần bắt đầu (1-15)',
    tuan_ket_thuc  INT          DEFAULT 15 COMMENT 'Tuần kết thúc',
    created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_lop) REFERENCES lop_hoc_phan(ma_lop) ON DELETE CASCADE,
    INDEX idx_lich (thu, tiet_bat_dau, phong_hoc)
) ENGINE=InnoDB;

-- Bảng cấu hình học kỳ (quản lý thời gian đăng ký, tuần học)
CREATE TABLE hoc_ky_config (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    hoc_ky        VARCHAR(10)  NOT NULL,
    nam_hoc       VARCHAR(9)   NOT NULL,
    ngay_bat_dau  DATE         NOT NULL COMMENT 'Ngày bắt đầu học kỳ',
    ngay_ket_thuc DATE         NOT NULL COMMENT 'Ngày kết thúc học kỳ',
    tuan_hoc      INT          DEFAULT 15 COMMENT 'Số tuần học',
    trang_thai    ENUM('chuan_bi','dang_dien_ra','ket_thuc') DEFAULT 'chuan_bi',
    UNIQUE KEY uq_hocky (hoc_ky, nam_hoc)
) ENGINE=InnoDB;
