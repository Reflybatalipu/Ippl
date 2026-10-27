<h2>Tugas dan Agenda</h2>
<table>
  <tr><th>Waktu</th><th>Jenis</th><th>Judul</th><th>Keterangan</th><th>Status</th></tr>
  <?php foreach ($item as $i): ?>
    <?php $lewat = $i['jenis'] === 'Tugas' && $i['status'] !== 'selesai' && strtotime($i['waktu']) < time(); ?>
    <tr class="<?= $lewat ? 'lewat' : '' ?>">
      <td><?= tgl($i['waktu']) ?></td>
      <td><?= e($i['jenis']) ?></td>
      <td><?= e($i['judul']) ?></td>
      <td><?= e($i['ket']) ?></td>
      <td><?= e($i['status'] ? ucfirst($i['status']) . ($lewat ? ' (lewat tenggat)' : '') : '-') ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$item): ?><tr><td colspan="5">Belum ada tugas atau agenda.</td></tr><?php endif; ?>
</table>
