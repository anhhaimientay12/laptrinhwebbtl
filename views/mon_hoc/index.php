<?php /* views/mon_hoc/index.php */ ?>
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-book-open" style="color:var(--clr-amber)"></i> Danh sách Môn học</span>
    <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
    <a href="<?= BASE_URL ?>/mon-hoc/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Thêm Môn học</a>
    <?php endif; ?>
  </div>
  <div class="card-body" style="padding-bottom:0">
    <form method="GET" class="search-bar">
      <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Tìm theo tên, mã môn..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <select name="khoa" style="width:auto;min-width:160px">
        <option value="">-- Tất cả khoa --</option>
        <?php foreach ($danhSachKhoa as $k): ?>
          <option value="<?= $k['ma_khoa'] ?>" <?= $maKhoa === $k['ma_khoa'] ? 'selected' : '' ?>><?= htmlspecialchars($k['ten_khoa']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Lọc</button>
      <?php if ($search || $maKhoa): ?><a href="<?= BASE_URL ?>/mon-hoc" class="btn btn-secondary"><i class="fas fa-times"></i></a><?php endif; ?>
    </form>
  </div>
  <div class="table-wrapper">
    <table>
      <thead><tr><th>Mã Môn</th><th>Tên Môn học</th><th>Tín chỉ</th><th>Khoa</th><th>Tiên quyết</th><th style="text-align:right">Thao tác</th></tr></thead>
      <tbody>
        <?php foreach ($result['data'] as $m): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($m['ma_mon']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($m['ten_mon']) ?></td>
          <td><span class="badge badge-blue"><?= $m['tin_chi'] ?> TC</span></td>
          <td><?= htmlspecialchars($m['ten_khoa'] ?? '—') ?></td>
          <td style="color:var(--clr-muted);font-size:12px"><?= htmlspecialchars($m['ten_tien_quyet'] ?? '—') ?></td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
              <a href="<?= BASE_URL ?>/mon-hoc/edit/<?= urlencode($m['ma_mon']) ?>" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-pencil"></i></a>
              <a href="<?= BASE_URL ?>/mon-hoc/delete/<?= urlencode($m['ma_mon']) ?>" class="btn btn-danger btn-sm btn-icon"
                 onclick="return confirm('Xóa môn học này?')"><i class="fas fa-trash"></i></a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($result['data'])): ?><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--clr-muted)">Không có dữ liệu</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php if ($result['last_page'] > 1): ?>
  <div class="pagination">
    <span class="page-info"><?= count($result['data']) ?> / <?= $result['total'] ?> bản ghi</span>
    <?php for ($p = 1; $p <= $result['last_page']; $p++): ?>
      <a href="?page=<?= $p ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $maKhoa ? '&khoa='.urlencode($maKhoa) : '' ?>" class="page-link <?= $p === $result['current_page'] ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>
