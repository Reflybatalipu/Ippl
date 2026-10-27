<h2><?= $tugas ? 'Ubah' : 'Tambah' ?> Tugas</h2>
<form method="post" action="<?= url($tugas ? '/tugas/ubah' : '/tugas/simpan') ?>">
  <?= csrf_field() ?>
  <?php if ($tugas): ?><input type="hidden" name="id" value="<?= (int) $tugas['id_tugas'] ?>"><?php endif; ?>
  <label>Judul</label><input name="judul" value="<?= e($tugas['judul'] ?? '') ?>" required>
  <label>Mata kuliah</label><input name="mata_kuliah" value="<?= e($tugas['mata_kuliah'] ?? '') ?>">
  <label>Deskripsi</label><textarea name="deskripsi" rows="3"><?= e($tugas['deskripsi'] ?? '') ?></textarea>
  <label>Tenggat</label>
  <input type="datetime-local" name="tenggat" value="<?= $tugas ? date('Y-m-d\TH:i', strtotime($tugas['tenggat'])) : '' ?>" required>
  <button>Simpan</button> <a href="<?= url('/tugas') ?>">Batal</a>
</form>
