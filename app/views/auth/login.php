<h2>Masuk</h2>
<form method="post" action="<?= url('/login') ?>">
  <?= csrf_field() ?>
  <label>Email</label><input type="email" name="email" required>
  <label>Kata sandi</label><input type="password" name="sandi" required>
  <button>Masuk</button>
</form>
