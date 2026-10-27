<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\KetuaTingkat;
use App\Models\Tugas;
use DateTimeImmutable;
use InvalidArgumentException;

class TugasController extends Controller
{
    public function index(): void
    {
        $this->render('tugas/index', ['tugas' => $this->wajibLogin()->lihatTugas()]);
    }

    public function form(): void
    {
        $ketua = $this->wajibKetua();
        $tugas = null;
        foreach ($ketua->lihatTugas() as $t) {
            if ((int) $t['id_tugas'] === (int) ($_GET['id'] ?? 0)) {
                $tugas = $t;
            }
        }
        $this->render('tugas/form', ['tugas' => $tugas]);
    }

    public function simpan(): void
    {
        $ketua = $this->wajibKetua();
        $this->verifikasiCsrf();
        $this->proses(fn () => $ketua->tambahTugas($this->bangunTugas($ketua, null)), 'Tugas ditambahkan.', '/tugas', '/tugas/form');
    }

    public function ubah(): void
    {
        $ketua = $this->wajibKetua();
        $this->verifikasiCsrf();
        $id = (int) ($_POST['id'] ?? 0);
        $this->proses(fn () => $ketua->ubahTugas($this->bangunTugas($ketua, $id)), 'Tugas diperbarui.', '/tugas', '/tugas/form?id=' . $id);
    }

    public function hapus(): void
    {
        $ketua = $this->wajibKetua();
        $this->verifikasiCsrf();
        $this->proses(fn () => $ketua->hapusTugas((int) ($_POST['id'] ?? 0)), 'Tugas dihapus.', '/tugas');
    }

    public function ubahStatus(): void
    {
        $pengguna = $this->wajibLogin();
        $this->verifikasiCsrf();
        $this->proses(
            fn () => $pengguna->ubahStatus((int) ($_POST['id_tugas'] ?? 0), $_POST['status'] ?? ''),
            'Status diperbarui.',
            '/tugas'
        );
    }

    private function bangunTugas(KetuaTingkat $ketua, ?int $id): Tugas
    {
        $waktu = trim($_POST['tenggat'] ?? '');
        if ($waktu === '') {
            throw new InvalidArgumentException('Tenggat wajib diisi.');
        }
        try {
            $tenggat = new DateTimeImmutable($waktu);
        } catch (\Exception) {
            throw new InvalidArgumentException('Format tenggat tidak valid.');
        }
        return new Tugas(
            $id, $ketua->getIdTingkat(), (int) $ketua->getIdMahasiswa(),
            trim($_POST['judul'] ?? ''),
            trim($_POST['mata_kuliah'] ?? '') ?: null,
            trim($_POST['deskripsi'] ?? '') ?: null,
            $tenggat
        );
    }
}
