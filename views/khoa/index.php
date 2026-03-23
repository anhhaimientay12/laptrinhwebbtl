<?php /* views/khoa/index.php */ ?>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-building-columns" style="color:var(--clr-accent)"></i> Danh sách Khoa</span>
    <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
    <a href="<?= BASE_URL ?>/khoa/create" class="btn btn-primary btn-sm">
      <i class="fas fa-plus"></i> Thêm Khoa
    </a>
    <?php endif; ?>
  </div>

  <div class="card-body" style="padding-bottom:0">
    <form method="GET" class="search-bar">
      <div class="search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" placeholder="Tìm kiếm theo tên, mã khoa..."
               value="<?= htmlspecialchars($search) ?>">
      </div>
      <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tìm</button>
      <?php if ($search): ?>
        <a href="<?= BASE_URL ?>/khoa" class="btn btn-secondary"><i class="fas fa-times"></i></a>
      <?php endif; ?>
    </form>
  </div>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Mã Khoa</th>
          <th>Tên Khoa</th>
          <th>Mô tả</th>
          <th>Ngày tạo</th>
          <th style="text-align:right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result['data'] as $k): ?>
        <tr>
          <td><span class="mono"><?= htmlspecialchars($k['ma_khoa']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($k['ten_khoa']) ?></td>
          <td style="color:var(--clr-muted);max-width:300px">
            <?= htmlspecialchars(mb_strimwidth($k['mo_ta'] ?? '—', 0, 80, '...')) ?>
          </td>
          <td style="color:var(--clr-muted);font-size:12px">
            <?= $k['created_at'] ? date('d/m/Y', strtotime($k['created_at'])) : '—' ?>
          </td>
          <td>
            <div style="display:flex;gap:6px;justify-content:flex-end">
              <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
              <a href="<?= BASE_URL ?>/khoa/edit/<?= urlencode($k['ma_khoa']) ?>" class="btn btn-secondary btn-sm btn-icon" title="Sửa">
                <i class="fas fa-pencil"></i>
              </a>
              <a href="<?= BASE_URL ?>/khoa/delete/<?= urlencode($k['ma_khoa']) ?>"
                 class="btn btn-danger btn-sm btn-icon"
                 onclick="return confirm('Xóa khoa <?= htmlspecialchars(addslashes($k['ten_khoa'])) ?>?')"
                 title="Xóa">
                <i class="fas fa-trash"></i>
              </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($result['data'])): ?>
        <tr>
          <td colspan="5" style="text-align:center;padding:40px;color:var(--clr-muted)">
            <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:10px"></i>
            Không tìm thấy dữ liệu
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($result['last_page'] > 1): ?>
  <div class="pagination">
    <span class="page-info">Hiển thị <?= count($result['data']) ?> / <?= $result['total'] ?> bản ghi</span>
    <?php for ($p = 1; $p <= $result['last_page']; $p++): ?>
      <a href="?page=<?= $p ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
         class="page-link <?= $p === $result['current_page'] ? 'active' : '' ?>"><?= $p ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>
