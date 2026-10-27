<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manajemen Tugas Mahasiswa</title>
<style>
body{font-family:system-ui,sans-serif;max-width:900px;margin:0 auto;padding:16px;color:#222}
nav{display:flex;gap:16px;align-items:center;padding:8px 0;border-bottom:1px solid #ddd;margin-bottom:16px}
nav .kanan{margin-left:auto}
table{width:100%;border-collapse:collapse}th,td{padding:8px;border-bottom:1px solid #eee;text-align:left}
input,select,textarea{padding:6px;width:100%;box-sizing:border-box;margin:4px 0 12px}
button{padding:6px 12px;cursor:pointer}
.inline{display:inline}.inline select,.inline button{width:auto;margin:0}
.sukses{background:#e6f4ea;padding:8px;margin-bottom:12px}
.galat{background:#fdecea;padding:8px;margin-bottom:12px}
.lewat{color:#b00020}
</style>
</head>
<body>
<nav>
<?php if ($pengguna): ?>
  <a href="<?= url('/') ?>">Dashboard</a>
  <a href="<?= url('/tugas') ?>">Tugas</a>
  <a href="<?= url('/agenda') ?>">Agenda</a>
  <span class="kanan"><?= e($pengguna->getNama()) ?> (<?= e($pengguna->getPeran()) ?>)
    <form class="inline" method="post" action="<?= url('/logout') ?>"><?= csrf_field() ?><button>Keluar</button></form>
  </span>
<?php else: ?>
  <a href="<?= url('/login') ?>">Masuk</a>
  <a href="<?= url('/registrasi') ?>">Daftar</a>
<?php endif; ?>
</nav>
<?php if ($flash): ?><div class="<?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div><?php endif; ?>
<?= $isi ?>
</body>
</html>
