<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Agenda;
use App\Models\Mahasiswa;
use DateTimeImmutable;
use InvalidArgumentException;

class AgendaController extends Controller
{
    public function index(): void
    {
        $pengguna = $this->wajibLogin();
        $daftar = $pengguna->lihatAgenda();
        $edit = null;
        foreach ($daftar as $a) {
            if ((int) $a['id_agenda'] === (int) ($_GET['edit'] ?? 0)) {
                $edit = $a;
            }
        }
        $this->render('agenda/index', ['agenda' => $daftar, 'edit' => $edit]);
    }

    public function simpan(): void
    {
        $pengguna = $this->wajibLogin();
        $this->verifikasiCsrf();
        $this->proses(fn () => $pengguna->tambahAgenda($this->bangunAgenda($pengguna, null)), 'Agenda ditambahkan.', '/agenda');
    }

    public function ubah(): void
    {
        $pengguna = $this->wajibLogin();
        $this->verifikasiCsrf();
        $id = (int) ($_POST['id'] ?? 0);
        $this->proses(function () use ($pengguna, $id) {
            $agenda = $this->bangunAgenda($pengguna, $id);
            if (!$agenda->validasi()) {
                throw new InvalidArgumentException(implode(' ', $agenda->getKesalahan()));
            }
            $agenda->simpan();
        }, 'Agenda diperbarui.', '/agenda', '/agenda?edit=' . $id);
    }

    public function hapus(): void
    {
        $pengguna = $this->wajibLogin();
        $this->verifikasiCsrf();
        $this->proses(fn () => Agenda::hapus((int) ($_POST['id'] ?? 0), (int) $pengguna->getIdMahasiswa()), 'Agenda dihapus.', '/agenda');
    }

    private function bangunAgenda(Mahasiswa $pemilik, ?int $id): Agenda
    {
        $waktu = trim($_POST['waktu_mulai'] ?? '');
        if ($waktu === '') {
            throw new InvalidArgumentException('Waktu mulai wajib diisi.');
        }
        try {
            $mulai = new DateTimeImmutable($waktu);
        } catch (\Exception) {
            throw new InvalidArgumentException('Format waktu tidak valid.');
        }
        return new Agenda(
            $id, (int) $pemilik->getIdMahasiswa(),
            trim($_POST['nama_kegiatan'] ?? ''),
            trim($_POST['organisasi'] ?? '') ?: null,
            $mulai,
            trim($_POST['deskripsi'] ?? '') ?: null
        );
    }
}
