<?php
use App\Models\KetuaTingkat;
use App\Models\TugasMahasiswa;

$ketua = $pengguna instanceof KetuaTingkat;
?>
<h2>Tugas Tingkat</h2>
<?php if ($ketua): ?><p><a href="<?= url('/tugas/form') ?>">+ Tambah tugas</a></p><?php endif; ?>
<table>
  <tr><th>Judul</th><th>Mata kuliah</th><th>Tenggat</th><th>Status saya</th><?php if ($ketua): ?><th>Kelola</th><?php endif; ?></tr>
  <?php foreach ($tugas as $t): ?>
    <tr>
      <td><?= e($t['judul']) ?></td>
      <td><?= e($t['mata_kuliah']) ?></td>
      <td><?= tgl($t['tenggat']) ?></td>
      <td>
        <form class="inline" method="post" action="<?= url('/tugas/status') ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="id_tugas" value="<?= (int) $t['id_tugas'] ?>">
          <select name="status">
            <?php foreach (TugasMahasiswa::STATUS as $s): ?>
              <option value="<?= $s ?>" <?= $s === $t['status'] ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
          <button>Simpan</button>
        </form>
      </td>
      <?php if ($ketua): ?>
        <td>
          <a href="<?= url('/tugas/form?id=' . (int) $t['id_tugas']) ?>">Ubah</a>
          <form class="inline" method="post" action="<?= url('/tugas/hapus') ?>" onsubmit="return confirm('Hapus tugas ini?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $t['id_tugas'] ?>">
            <button>Hapus</button>
          </form>
        </td>
      <?php endif; ?>
    </tr>
  <?php endforeach; ?>
  <?php if (!$tugas): ?><tr><td colspan="5">Belum ada tugas.</td></tr><?php endif; ?>
</table>
