<?php /* views/diem_so/by_lop.php */ ?>

<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
  <a href="<?= BASE_URL ?>/diem-so" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
  <div style="display:flex;gap:10px">
    <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i> In</button>
  </div>
</div>

<!-- Thông tin lớp -->
<div class="card" style="margin-bottom:20px">
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px">
      <?php
      $info = [
        ['Mã lớp',      $lop['ma_lop'],     'mono'],
        ['Môn học',     $lop['ten_mon'],     ''],
        ['Tín chỉ',     ($lop['tin_chi'] ?? '—') . ' TC', ''],
        ['Giảng viên',  $lop['ten_gv'] ?? '—', ''],
        ['Học kỳ',      'HK' . $lop['hoc_ky'] . ' — ' . $lop['nam_hoc'], ''],
        ['Sĩ số',       count($diem) . ' SV', ''],
      ];
      foreach ($info as [$label, $value, $cls]):
      ?>
      <div>
        <div style="font-size:11px;font-weight:600;color:var(--clr-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px"><?= $label ?></div>
        <div class="<?= $cls ?>" style="font-size:14px;font-weight:500;color:var(--clr-text)"><?= htmlspecialchars((string)$value) ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Bảng điểm -->
<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fas fa-table-list" style="color:var(--clr-amber)"></i> Bảng điểm — <?= htmlspecialchars($maLop) ?></span>
    <span style="font-size:12px;color:var(--clr-muted)">CC×10% + GK×30% + CK×60%</span>
  </div>

  <form method="POST" action="<?= BASE_URL ?>/diem-so/nhap/<?= urlencode($maLop) ?>">
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Mã SV</th>
          <th>Họ tên</th>
          <th>Lớp</th>
          <th style="text-align:center;color:var(--clr-accent)">Chuyên cần<br><span style="font-weight:400;font-size:10px;color:var(--clr-muted)">(0–10 / 10%)</span></th>
          <th style="text-align:center;color:var(--clr-accent)">Giữa kỳ<br><span style="font-weight:400;font-size:10px;color:var(--clr-muted)">(0–10 / 30%)</span></th>
          <th style="text-align:center;color:var(--clr-accent)">Cuối kỳ<br><span style="font-weight:400;font-size:10px;color:var(--clr-muted)">(0–10 / 60%)</span></th>
          <th style="text-align:center">Tổng kết</th>
          <th style="text-align:center">Xếp loại</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($diem as $i => $d): ?>
        <?php
          $tong    = $d['diem_tong'] ?? null;
          $xepLoai = $d['xep_loai'] ?? null;
          $loaiBadge = match(true) {
            $xepLoai === 'A+' || $xepLoai === 'A' => 'green',
            $xepLoai === 'B+' || $xepLoai === 'B' => 'blue',
            $xepLoai === 'C+' || $xepLoai === 'C' => 'amber',
            $xepLoai === 'D+' || $xepLoai === 'D' => 'purple',
            $xepLoai === 'F'                       => 'red',
            default                                => 'gray',
          };
        ?>
        <tr>
          <td style="color:var(--clr-muted);font-size:12px"><?= $i + 1 ?></td>
          <td><span class="mono"><?= htmlspecialchars($d['ma_sv']) ?></span></td>
          <td style="font-weight:500"><?= htmlspecialchars($d['ho_ten']) ?></td>
          <td style="color:var(--clr-muted);font-size:12px"><?= htmlspecialchars($d['lop'] ?? '—') ?></td>

          <td style="text-align:center">
            <input type="number" name="diem[<?= htmlspecialchars($d['ma_sv']) ?>][diem_cc]"
                   value="<?= $d['diem_cc'] ?? '' ?>"
                   min="0" max="10" step="0.25"
                   placeholder="—"
                   style="width:70px;text-align:center;padding:6px 8px;font-size:13px"
                   oninput="calcRow(this)">
          </td>
          <td style="text-align:center">
            <input type="number" name="diem[<?= htmlspecialchars($d['ma_sv']) ?>][diem_gk]"
                   value="<?= $d['diem_gk'] ?? '' ?>"
                   min="0" max="10" step="0.25"
                   placeholder="—"
                   style="width:70px;text-align:center;padding:6px 8px;font-size:13px"
                   oninput="calcRow(this)">
          </td>
          <td style="text-align:center">
            <input type="number" name="diem[<?= htmlspecialchars($d['ma_sv']) ?>][diem_ck]"
                   value="<?= $d['diem_ck'] ?? '' ?>"
                   min="0" max="10" step="0.25"
                   placeholder="—"
                   style="width:70px;text-align:center;padding:6px 8px;font-size:13px"
                   oninput="calcRow(this)">
          </td>

          <td style="text-align:center">
            <span class="tong-preview" style="font-family:var(--mono);font-size:14px;font-weight:600;color:var(--clr-text)">
              <?= $tong !== null ? number_format((float)$tong, 2) : '—' ?>
            </span>
          </td>
          <td style="text-align:center">
            <span class="loai-preview badge badge-<?= $loaiBadge ?>">
              <?= htmlspecialchars($xepLoai ?? '—') ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($diem)): ?>
        <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--clr-muted)">
          <i class="fas fa-users-slash" style="font-size:28px;display:block;margin-bottom:10px"></i>
          Chưa có sinh viên đăng ký lớp học phần này
        </td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (!empty($diem)): ?>
  <div style="padding:16px 22px;border-top:1px solid var(--clr-border);display:flex;align-items:center;gap:12px">
    <button type="submit" class="btn btn-success"><i class="fas fa-floppy-disk"></i> Lưu điểm</button>
    <span style="font-size:12px;color:var(--clr-muted)">Điểm sẽ được tính tự động sau khi lưu</span>
  </div>
  <?php endif; ?>
  </form>
</div>

<style>
@media print {
  .sidebar, .topbar, .btn, form button { display: none !important; }
  .main-wrapper { margin-left: 0 !important; }
  input { border: none !important; background: transparent !important; }
}
</style>

<script>
function xepLoai(d) {
  if (d >= 9)   return ['A+', 'green'];
  if (d >= 8.5) return ['A',  'green'];
  if (d >= 8)   return ['B+', 'blue'];
  if (d >= 7)   return ['B',  'blue'];
  if (d >= 6.5) return ['C+', 'amber'];
  if (d >= 5.5) return ['C',  'amber'];
  if (d >= 5)   return ['D+', 'purple'];
  if (d >= 4)   return ['D',  'purple'];
  return ['F', 'red'];
}

function calcRow(input) {
  const row   = input.closest('tr');
  const ins   = row.querySelectorAll('input[type=number]');
  const cc    = parseFloat(ins[0].value) || 0;
  const gk    = parseFloat(ins[1].value) || 0;
  const ck    = parseFloat(ins[2].value) || 0;
  const tong  = Math.round((cc * 0.1 + gk * 0.3 + ck * 0.6) * 100) / 100;
  const [loai, color] = xepLoai(tong);

  row.querySelector('.tong-preview').textContent = tong.toFixed(2);

  const badge = row.querySelector('.loai-preview');
  badge.textContent = loai;
  badge.className = 'loai-preview badge badge-' + color;
}

// Recalc all on load
document.querySelectorAll('tbody tr').forEach(row => {
  const ins = row.querySelectorAll('input[type=number]');
  if (ins.length) calcRow(ins[0]);
});
</script>
