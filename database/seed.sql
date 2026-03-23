USE edumanager;

-- Dữ liệu Khoa
INSERT INTO khoa (ma_khoa, ten_khoa, mo_ta) VALUES
('CNTT',  'Công nghệ Thông tin',    'Khoa đào tạo kỹ sư CNTT'),
('KTKT',  'Kỹ thuật Kinh tế',       'Khoa kỹ thuật kinh tế'),
('QTKD',  'Quản trị Kinh doanh',    'Khoa quản trị kinh doanh'),
('NGON',  'Ngôn ngữ Anh',           'Khoa ngôn ngữ Anh'),
('TOAN',  'Toán - Tin',             'Khoa toán ứng dụng và tin học');

-- Tài khoản (password: Admin@123 => bcrypt)
INSERT INTO users (username, password, email, ho_ten, role) VALUES
('admin',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@edu.vn',    'Quản trị viên',    'admin'),
('gv001',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gv001@edu.vn',    'Nguyễn Văn An',    'giang_vien'),
('gv002',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gv002@edu.vn',    'Trần Thị Bình',    'giang_vien'),
('sv2021001','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sv001@edu.vn',    'Lê Văn Cường',     'sinh_vien'),
('sv2021002','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sv002@edu.vn',    'Phạm Thị Dung',    'sinh_vien');

-- Giảng viên
INSERT INTO giang_vien (ma_gv, user_id, ho_ten, email, ma_khoa, hoc_vi) VALUES
('GV001', 2, 'Nguyễn Văn An',  'gv001@edu.vn', 'CNTT',  'Tien si'),
('GV002', 3, 'Trần Thị Bình',  'gv002@edu.vn', 'QTKD',  'Thac si');

-- Sinh viên
INSERT INTO sinh_vien (ma_sv, user_id, ho_ten, email, ngay_sinh, gioi_tinh, ma_khoa, lop, nganh, nam_nhap_hoc) VALUES
('SV21001', 4, 'Lê Văn Cường',  'sv001@edu.vn', '2003-05-10', 'Nam', 'CNTT', 'CNTT2021A', 'Kỹ thuật phần mềm', 2021),
('SV21002', 5, 'Phạm Thị Dung', 'sv002@edu.vn', '2003-08-22', 'Nu',  'CNTT', 'CNTT2021A', 'Kỹ thuật phần mềm', 2021);

-- Môn học
INSERT INTO mon_hoc (ma_mon, ten_mon, tin_chi, ma_khoa) VALUES
('COMP101', 'Lập trình cơ bản',        3, 'CNTT'),
('COMP201', 'Cấu trúc dữ liệu',        3, 'CNTT'),
('COMP301', 'Lập trình Web',           3, 'CNTT'),
('COMP401', 'Cơ sở dữ liệu',          3, 'CNTT'),
('MATH101', 'Giải tích',              3, 'TOAN'),
('MATH201', 'Đại số tuyến tính',      3, 'TOAN'),
('ENGL101', 'Tiếng Anh cơ bản',       3, 'NGON'),
('BUSI101', 'Quản trị học',           3, 'QTKD');

-- Lớp học phần
INSERT INTO lop_hoc_phan (ma_lop, ma_mon, ma_gv, hoc_ky, nam_hoc, si_so_max, phong_hoc, lich_hoc) VALUES
('COMP101-01', 'COMP101', 'GV001', 'HK1', '2024-2025', 40, 'P201', 'T2-T4 (7h30-9h30)'),
('COMP301-01', 'COMP301', 'GV001', 'HK1', '2024-2025', 35, 'P301', 'T3-T5 (9h45-11h45)'),
('MATH101-01', 'MATH101', 'GV002', 'HK1', '2024-2025', 50, 'P101', 'T2-T6 (13h-15h)');
