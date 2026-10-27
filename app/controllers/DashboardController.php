<?php
namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index(): void
    {
        $pengguna = $this->wajibLogin();
        $item = [];
        foreach ($pengguna->lihatTugas() as $t) {
            $item[] = ['jenis' => 'Tugas', 'judul' => $t['judul'], 'ket' => $t['mata_kuliah'], 'waktu' => $t['tenggat'], 'status' => $t['status']];
        }
        foreach ($pengguna->lihatAgenda() as $a) {
            $item[] = ['jenis' => 'Agenda', 'judul' => $a['nama_kegiatan'], 'ket' => $a['organisasi'], 'waktu' => $a['waktu_mulai'], 'status' => null];
        }
        usort($item, fn ($x, $y) => strcmp($x['waktu'], $y['waktu']));
        $this->render('dashboard/index', ['item' => $item]);
    }
}
