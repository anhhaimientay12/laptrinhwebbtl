# 🎓 EduManager — Hệ Thống Quản Lý Học Tập

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4-D22128?style=for-the-badge&logo=apache&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Active-16a34a?style=for-the-badge)

<br/>

> **Nền tảng quản lý học tập toàn diện** dành cho trường đại học & cao đẳng.  
> Xây dựng bằng PHP thuần, MySQL, Bootstrap — không cần framework phức tạp.

[🚀 Demo](#) · [📖 Tài liệu](#) · [🐛 Báo lỗi](#) · [💡 Góp ý tính năng](#)

</div>

---

## 📌 Mục Lục

- [Tổng Quan](#-tổng-quan)
- [Tính Năng](#-tính-năng)
- [Công Nghệ Sử Dụng](#-công-nghệ-sử-dụng)
- [Mô Hình Dữ Liệu (ERD)](#-mô-hình-dữ-liệu-erd)
- [Lược Đồ Use Case](#-lược-đồ-use-case)
- [Lược Đồ Lớp (Class Diagram)](#-lược-đồ-lớp-class-diagram)
- [Lược Đồ Tuần Tự](#-lược-đồ-tuần-tự--đăng-ký-môn-học)
- [Cài Đặt](#-cài-đặt)
- [Cấu Trúc Thư Mục](#-cấu-trúc-thư-mục)
- [Routing](#-routing)
- [Đóng Góp](#-đóng-góp)
- [Giấy Phép](#-giấy-phép)

---

## 🌟 Tổng Quan

**EduManager** là ứng dụng web quản lý học tập viết bằng PHP thuần (không dùng framework), kết nối MySQL thông qua PDO. Hệ thống hỗ trợ 3 vai trò chính:

| Vai trò | Mô tả |
|---|---|
| 🔑 **Admin** | Toàn quyền: quản lý Khoa, Sinh viên, Giảng viên, Môn học, Học kỳ |
| 👨‍🏫 **Giảng viên** | Nhập điểm, xem danh sách lớp, quản lý lớp học phần |
| 👨‍🎓 **Sinh viên** | Đăng ký môn học, xem điểm, tra cứu thời khóa biểu |

---

## ✨ Tính Năng

### 🏛️ Quản Lý Khoa
- CRUD Khoa (Tên, Mã khoa, Mô tả)
- Phân công Trưởng khoa, xem danh sách sinh viên & giảng viên theo khoa

### 📚 Quản Lý Môn Học
- Tạo / sửa / xóa môn học
- Thiết lập số tín chỉ, môn học tiên quyết
- Gán môn học cho Khoa

### 👥 Quản Lý Sinh Viên
- Quản lý hồ sơ sinh viên (MSSV, họ tên, ngày sinh, khoa, ngành)
- Phân lớp, theo dõi trạng thái học tập
- Tra cứu theo khoa / ngành / năm nhập học
- Import danh sách từ file CSV

### 📝 Đăng Ký Môn Học
- Mở / đóng đợt đăng ký theo học kỳ
- Giới hạn sĩ số lớp học phần
- Hủy / thêm môn trong thời gian quy định

### 📊 Quản Lý Điểm
- Nhập điểm thành phần (CC, GK, CK)
- Tự động tính điểm tổng kết, GPA, xếp loại học lực
- Xuất bảng điểm PDF / Excel

### 🗓️ Thời Khóa Biểu
- Phân lịch học theo phòng, ca, giảng viên
- Phát hiện xung đột lịch tự động

### 📈 Báo Cáo & Thống Kê
- Dashboard tổng quan theo học kỳ
- Biểu đồ tỷ lệ đậu/rớt, phân bố điểm
- Xuất báo cáo Excel / PDF

---

## 🛠️ Công Nghệ Sử Dụng

```
Backend  : PHP 8.1+ (PDO, OOP, MVC pattern)
Database : MySQL 8.0
Frontend : HTML5, CSS3, Bootstrap 5.3, jQuery 3.7
Server   : Apache 2.4 / Nginx (với .htaccess)
Thư viện : TCPDF, PhpSpreadsheet
```

---

## 🗃️ Mô Hình Dữ Liệu (ERD)

```
┌──────────────┐       ┌───────────────────┐       ┌─────────────┐
│    KHOA      │       │   SINH_VIEN       │       │  MON_HOC    │
├──────────────┤       ├───────────────────┤       ├─────────────┤
│ PK ma_khoa   │◄──┐   │ PK ma_sv          │   ┌──►│ PK ma_mon   │
│    ten_khoa  │   │   │    ho_ten         │   │   │    ten_mon  │
│    mo_ta     │   └───│ FK ma_khoa        │   │   │    tin_chi  │
│    truong_k  │       │    email          │   │   │ FK ma_khoa  │
└──────────────┘       │    ngay_sinh      │   │   │    mo_ta    │
       ▲               └───────────────────┘   │   └─────────────┘
       │                      │                │          │
       │               (đăng ký môn)           │   (lớp học phần)
       │                      ▼                │          ▼
       │             ┌────────────────┐        │  ┌──────────────────┐
       │             │   DANG_KY      │        │  │  LOP_HOC_PHAN    │
       │             ├────────────────┤        │  ├──────────────────┤
       │             │ PK id          │        └──│ PK ma_lop        │
       │             │ FK ma_sv       │           │ FK ma_mon        │
       │             │ FK ma_lop      │           │ FK ma_gv         │
       │             │    trang_thai  │           │    hoc_ky        │
       │             └────────────────┘           │    si_so_max     │
       │                    │                     └──────────────────┘
       │             (điểm số)                           │
       │                    ▼                            │
       │          ┌─────────────────┐       ┌───────────────────┐
       │          │   DIEM_SO       │       │   GIANG_VIEN      │
       │          ├─────────────────┤       ├───────────────────┤
       └──────────│ FK ma_sv        │       │ PK ma_gv          │
                  │ FK ma_lop       │       │    ho_ten         │
                  │    diem_cc      │       │ FK ma_khoa        │◄─┘
                  │    diem_gk      │       │    email          │
                  │    diem_ck      │       │    hoc_vi         │
                  │    diem_tong    │       └───────────────────┘
                  └─────────────────┘
```

### Chi tiết bảng chính

<details>
<summary><b>📋 Bảng KHOA</b></summary>

```sql
CREATE TABLE khoa (
    ma_khoa     VARCHAR(10)  PRIMARY KEY,
    ten_khoa    VARCHAR(100) NOT NULL,
    mo_ta       TEXT,
    truong_khoa VARCHAR(10),
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);
```
</details>

<details>
<summary><b>📋 Bảng SINH_VIEN</b></summary>

```sql
CREATE TABLE sinh_vien (
    ma_sv        VARCHAR(10)  PRIMARY KEY,
    ho_ten       VARCHAR(100) NOT NULL,
    email        VARCHAR(100) UNIQUE NOT NULL,
    ngay_sinh    DATE,
    gioi_tinh    ENUM('Nam','Nu','Khac'),
    ma_khoa      VARCHAR(10),
    lop          VARCHAR(20),
    nganh        VARCHAR(100),
    nam_nhap_hoc YEAR,
    trang_thai   ENUM('dang_hoc','tam_nghi','da_tot_nghiep') DEFAULT 'dang_hoc',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_khoa) REFERENCES khoa(ma_khoa)
);
```
</details>

<details>
<summary><b>📋 Bảng MON_HOC</b></summary>

```sql
CREATE TABLE mon_hoc (
    ma_mon         VARCHAR(10)  PRIMARY KEY,
    ten_mon        VARCHAR(150) NOT NULL,
    tin_chi        TINYINT      NOT NULL DEFAULT 3,
    ma_khoa        VARCHAR(10),
    mon_tien_quyet VARCHAR(10),
    mo_ta          TEXT,
    FOREIGN KEY (ma_khoa) REFERENCES khoa(ma_khoa),
    FOREIGN KEY (mon_tien_quyet) REFERENCES mon_hoc(ma_mon)
);
```
</details>

<details>
<summary><b>📋 Bảng LOP_HOC_PHAN</b></summary>

```sql
CREATE TABLE lop_hoc_phan (
    ma_lop     VARCHAR(20) PRIMARY KEY,
    ma_mon     VARCHAR(10),
    ma_gv      VARCHAR(10),
    hoc_ky     VARCHAR(10) NOT NULL,
    nam_hoc    VARCHAR(9)  NOT NULL,
    si_so_max  INT         DEFAULT 40,
    phong_hoc  VARCHAR(20),
    lich_hoc   VARCHAR(100),
    trang_thai ENUM('mo','dong','ket_thuc') DEFAULT 'mo',
    FOREIGN KEY (ma_mon) REFERENCES mon_hoc(ma_mon),
    FOREIGN KEY (ma_gv)  REFERENCES giang_vien(ma_gv)
);
```
</details>

<details>
<summary><b>📋 Bảng DIEM_SO</b></summary>

```sql
CREATE TABLE diem_so (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    ma_sv      VARCHAR(10) NOT NULL,
    ma_lop     VARCHAR(20) NOT NULL,
    diem_cc    DECIMAL(4,2) DEFAULT 0,
    diem_gk    DECIMAL(4,2) DEFAULT 0,
    diem_ck    DECIMAL(4,2) DEFAULT 0,
    diem_tong  DECIMAL(4,2) GENERATED ALWAYS AS
               (diem_cc*0.1 + diem_gk*0.3 + diem_ck*0.6) STORED,
    xep_loai   VARCHAR(5),
    UNIQUE KEY (ma_sv, ma_lop),
    FOREIGN KEY (ma_sv)  REFERENCES sinh_vien(ma_sv),
    FOREIGN KEY (ma_lop) REFERENCES lop_hoc_phan(ma_lop)
);
```
</details>

---

## 🎭 Lược Đồ Use Case

```
╔══════════════════════════════════════════════════════════════════════╗
║                  HỆ THỐNG QUẢN LÝ HỌC TẬP - EDUMANAGER              ║
╠══════════════════════════════════════════════════════════════════════╣
║                                                                      ║
║  ┌─────────┐    ● Quản lý Khoa             ┌───────────┐            ║
║  │         │──► ● Quản lý Sinh viên        │           │            ║
║  │  ADMIN  │──► ● Quản lý Giảng viên   ───►│ <<extend>>│            ║
║  │         │──► ● Quản lý Môn học          │  Phân     │            ║
║  └─────────┘──► ● Mở/đóng đợt đăng ký     │  quyền    │            ║
║       │         ● Xem báo cáo tổng hợp    └───────────┘            ║
║       │         ● Quản lý tài khoản                                  ║
║       │                                                              ║
║  ┌──────────┐   ● Nhập điểm sinh viên                               ║
║  │  GIẢNG   │──► ● Quản lý lớp học phần                             ║
║  │  VIÊN   │──► ● Xem lịch dạy                                      ║
║  └──────────┘   ● Thông báo lớp học                                 ║
║                                                                      ║
║  ┌──────────┐   ● Đăng ký môn học                                   ║
║  │  SINH    │──► ● Hủy đăng ký môn học                              ║
║  │  VIÊN   │──► ● Xem điểm số / GPA                                 ║
║  └──────────┘──► ● Xem thời khóa biểu                               ║
║                  ● Tra cứu môn học                                   ║
║                  ● Xem thông báo                                     ║
╚══════════════════════════════════════════════════════════════════════╝
```

---

## 🏗️ Lược Đồ Lớp (Class Diagram)

```
         ┌──────────────────────┐
         │      Database        │
         ├──────────────────────┤
         │ - $instance: static  │
         │ - $pdo: PDO          │
         ├──────────────────────┤
         │ + getInstance(): PDO │
         │ + query(sql, params) │
         └──────────────────────┘
                   ▲
                   │ uses
    ┌──────────────┴────────────────┐
    │         BaseModel             │
    ├───────────────────────────────┤
    │ # $table: string              │
    │ # $db: Database               │
    ├───────────────────────────────┤
    │ + getAll(): array             │
    │ + getById(id): array|null     │
    │ + insert(data): bool          │
    │ + update(id, data): bool      │
    │ + delete(id): bool            │
    └───────────────────────────────┘
                   △
      ┌────────────┼────────────┬─────────────┐
      │            │            │             │
┌─────┴──────┐ ┌───┴────────┐ ┌┴──────────┐ ┌┴────────────┐
│ KhoaModel  │ │SinhVienModel│ │MonHocModel│ │DiemSoModel  │
├────────────┤ ├────────────┤ ├───────────┤ ├─────────────┤
│+getAll()   │ │+getAll()   │ │+getAll()  │ │+nhapDiem()  │
│+getById()  │ │+getByKhoa()│ │+byKhoa()  │ │+tinhGPA()   │
│+insert()   │ │+search()   │ │+tieuQuyet()│ │+xepLoai()  │
│+update()   │ │+importCSV()│ │+insert()  │ │+exportPDF() │
│+delete()   │ │+insert()   │ │+update()  │ │+exportExcel()│
│+getGV()    │ │+update()   │ │+delete()  │ └─────────────┘
└────────────┘ └────────────┘ └───────────┘

    ┌──────────────────────────────┐
    │       BaseController         │
    ├──────────────────────────────┤
    │ # $model                     │
    │ # $view: string              │
    ├──────────────────────────────┤
    │ + render(view, data): void   │
    │ + redirect(url): void        │
    │ + isPost(): bool             │
    │ + sanitize(input): string    │
    └──────────────────────────────┘
                   △
      ┌────────────┼────────────┬──────────────┐
      │            │            │              │
┌─────┴──────┐ ┌───┴──────┐ ┌──┴───────┐ ┌───┴───────────┐
│KhoaCtrl    │ │SinhVienCtrl│ │MonHocCtrl│ │DangKyCtrl     │
├────────────┤ ├────────────┤ ├──────────┤ ├───────────────┤
│+index()    │ │+index()    │ │+index()  │ │+index()       │
│+create()   │ │+create()   │ │+create() │ │+store()       │
│+store()    │ │+store()    │ │+store()  │ │+huy()         │
│+edit()     │ │+edit()     │ │+edit()   │ │+checkSiSo()   │
│+update()   │ │+update()   │ │+update() │ └───────────────┘
│+delete()   │ │+delete()   │ │+delete() │
└────────────┘ │+search()   │ └──────────┘
               │+import()   │
               └────────────┘
```

---

## 🔄 Lược Đồ Tuần Tự — Đăng Ký Môn Học

```
  Sinh Viên      Browser         PHP Router       DangKyCtrl      Database
      │              │                │                │               │
      │── GET /dkmh─►│                │                │               │
      │              │─── Request ───►│                │               │
      │              │                │─── route() ───►│               │
      │              │                │                │── getMon() ──►│
      │              │                │                │◄── data ──────│
      │◄─── Hiển thị danh sách môn ───│◄── render() ───│               │
      │                               │                │               │
      │── POST /dkmh (chọn môn) ─────►│                │               │
      │              │                │─── route() ───►│               │
      │              │                │                │── checkSiSo()►│
      │              │                │                │◄── còn chỗ ───│
      │              │                │                │── checkTrung()►│
      │              │                │                │◄── không trùng│
      │              │                │                │── insert() ──►│
      │              │                │                │◄── success ───│
      │◄─── "Đăng ký thành công" ─────│◄── redirect ───│               │
```

---

## 🚀 Cài Đặt

### Yêu Cầu Hệ Thống

```
PHP    >= 8.1 (PDO, PDO_MySQL, mbstring, gd)
MySQL  >= 8.0
Apache >= 2.4 (hoặc Nginx)
```

### Các Bước Cài Đặt

**1. Clone repository**
```bash
git clone https://github.com/username/edumanager.git
cd edumanager
```

**2. Import database**
```bash
mysql -u root -p < database/schema.sql
mysql -u root -p edumanager < database/seed.sql
```

**3. Cấu hình kết nối**
```bash
cp config/config.example.php config/config.php
```

Chỉnh sửa `config/config.php`:
```php
<?php
define('DB_HOST',     'localhost');
define('DB_NAME',     'edumanager');
define('DB_USER',     'root');
define('DB_PASSWORD', 'your_password');
define('BASE_URL',    'http://localhost/edumanager');
define('APP_NAME',    'EduManager');
```

**4. Phân quyền thư mục**
```bash
chmod 775 uploads/
chmod 775 exports/
```

**5. Kích hoạt mod_rewrite** (Apache)
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**6. Truy cập ứng dụng**
```
URL: http://localhost/edumanager

Tài khoản mặc định:
  Admin     : admin@school.edu    / admin123
  Giảng viên: gv001@school.edu   / gv123
  Sinh viên : sv001@school.edu   / sv123
```

---

## 📁 Cấu Trúc Thư Mục

```
edumanager/
├── config/
│   ├── config.php              # Cấu hình DB & hằng số
│   └── config.example.php
├── core/
│   ├── Database.php            # PDO Singleton
│   ├── Controller.php          # Base Controller
│   ├── Model.php               # Base Model
│   └── Router.php              # URL Routing
├── controllers/
│   ├── AuthController.php
│   ├── KhoaController.php
│   ├── SinhVienController.php
│   ├── GiangVienController.php
│   ├── MonHocController.php
│   ├── LopHocPhanController.php
│   ├── DangKyController.php
│   └── DiemSoController.php
├── models/
│   ├── KhoaModel.php
│   ├── SinhVienModel.php
│   ├── GiangVienModel.php
│   ├── MonHocModel.php
│   ├── LopHocPhanModel.php
│   ├── DangKyModel.php
│   └── DiemSoModel.php
├── views/
│   ├── layout/
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── sidebar.php
│   ├── khoa/
│   ├── sinh_vien/
│   ├── giang_vien/
│   ├── mon_hoc/
│   ├── lop_hoc_phan/
│   ├── dang_ky/
│   └── diem_so/
├── public/
│   ├── css/
│   ├── js/
│   └── img/
├── database/
│   ├── schema.sql
│   └── seed.sql
├── uploads/
├── exports/
├── .htaccess
└── index.php                   # Front Controller
```

---

## 🔀 Routing

| Method | URL | Chức năng |
|--------|-----|-----------|
| GET | `/` | Dashboard |
| GET | `/khoa` | Danh sách khoa |
| POST | `/khoa/create` | Thêm khoa mới |
| GET | `/khoa/{id}/edit` | Form sửa khoa |
| POST | `/khoa/{id}/update` | Cập nhật khoa |
| DELETE | `/khoa/{id}/delete` | Xóa khoa |
| GET | `/sinh-vien` | Danh sách sinh viên |
| GET | `/sinh-vien/search?q=...` | Tìm kiếm |
| POST | `/sinh-vien/import` | Import CSV |
| GET | `/mon-hoc` | Danh sách môn học |
| GET | `/dang-ky` | Form đăng ký môn |
| POST | `/dang-ky/store` | Thực hiện đăng ký |
| POST | `/dang-ky/huy` | Hủy đăng ký |
| GET | `/diem-so/{ma_lop}` | Bảng điểm lớp |
| POST | `/diem-so/nhap` | Nhập điểm |
| GET | `/diem-so/export/{id}` | Xuất Excel/PDF |

---

## 🤝 Đóng Góp

1. Fork repository
2. Tạo branch mới: `git checkout -b feature/ten-tinh-nang`
3. Commit: `git commit -m "feat: thêm chức năng X"`
4. Push: `git push origin feature/ten-tinh-nang`
5. Tạo Pull Request


<div align="center">
Made by D24TXCN01-B
</div>
