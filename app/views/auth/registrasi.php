<h2>Registrasi</h2>
<form method="post" action="<?= url('/registrasi') ?>">
  <?= csrf_field() ?>
  <label>Nama</label><input name="nama" required>
  <label>Email</label><input type="email" name="email" required>
  <label>Kata sandi (minimal 8 karakter)</label><input type="password" name="sandi" minlength="8" required>
  <label>Tingkat</label>
  <select name="id_tingkat" required>
    <?php foreach ($daftarTingkat as $t): ?>
      <option value="<?= (int) $t->getIdTingkat() ?>"><?= e($t->getNamaTingkat()) ?></option>
    <?php endforeach; ?>
  </select>
  <button>Daftar</button>
</form>
