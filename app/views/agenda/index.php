<h2>Agenda Organisasi</h2>
<form method="post" action="<?= url($edit ? '/agenda/ubah' : '/agenda/simpan') ?>">
  <?= csrf_field() ?>
  <?php if ($edit): ?><input type="hidden" name="id" value="<?= (int) $edit['id_agenda'] ?>"><?php endif; ?>
  <label>Nama kegiatan</label><input name="nama_kegiatan" value="<?= e($edit['nama_kegiatan'] ?? '') ?>" required>
  <label>Organisasi</label><input name="organisasi" value="<?= e($edit['organisasi'] ?? '') ?>">
  <label>Waktu mulai</label>
  <input type="datetime-local" name="waktu_mulai" value="<?= $edit ? date('Y-m-d\TH:i', strtotime($edit['waktu_mulai'])) : '' ?>" required>
  <label>Deskripsi</label><textarea name="deskripsi" rows="2"><?= e($edit['deskripsi'] ?? '') ?></textarea>
  <button><?= $edit ? 'Perbarui' : 'Tambah' ?></button>
  <?php if ($edit): ?><a href="<?= url('/agenda') ?>">Batal</a><?php endif; ?>
</form>
<table>
  <tr><th>Waktu</th><th>Kegiatan</th><th>Organisasi</th><th>Kelola</th></tr>
  <?php foreach ($agenda as $a): ?>
    <tr>
      <td><?= tgl($a['waktu_mulai']) ?></td>
      <td><?= e($a['nama_kegiatan']) ?></td>
      <td><?= e($a['organisasi']) ?></td>
      <td>
        <a href="<?= url('/agenda?edit=' . (int) $a['id_agenda']) ?>">Ubah</a>
        <form class="inline" method="post" action="<?= url('/agenda/hapus') ?>" onsubmit="return confirm('Hapus agenda ini?')">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int) $a['id_agenda'] ?>">
          <button>Hapus</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$agenda): ?><tr><td colspan="4">Belum ada agenda.</td></tr><?php endif; ?>
</table>
