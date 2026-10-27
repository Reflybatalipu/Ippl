<?php
namespace App\Core;

use App\Models\KetuaTingkat;
use App\Models\Mahasiswa;
use DomainException;
use InvalidArgumentException;

abstract class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function pengguna(): ?Mahasiswa
    {
        $id = $_SESSION['id_mahasiswa'] ?? null;
        return $id ? Mahasiswa::cariId((int) $id) : null;
    }

    protected function wajibLogin(): Mahasiswa
    {
        $pengguna = $this->pengguna();
        if ($pengguna === null) {
            $this->redirect('/login');
        }
        return $pengguna;
    }

    protected function wajibKetua(): KetuaTingkat
    {
        $pengguna = $this->wajibLogin();
        if (!$pengguna instanceof KetuaTingkat) {
            http_response_code(403);
            exit('Akses ditolak: hanya untuk Ketua Tingkat.');
        }
        return $pengguna;
    }

    protected function verifikasiCsrf(): void
    {
        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(419);
            exit('Token keamanan tidak valid.');
        }
    }

    protected function flash(string $tipe, string $pesan): void
    {
        $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    /** Menjalankan aksi Model; kesalahan validasi/otorisasi ditampilkan sebagai pesan. */
    protected function proses(callable $aksi, string $pesanSukses, string $tujuan, ?string $tujuanGagal = null): void
    {
        try {
            $aksi();
        } catch (InvalidArgumentException | DomainException $e) {
            $this->flash('galat', $e->getMessage());
            $this->redirect($tujuanGagal ?? $tujuan);
        }
        $this->flash('sukses', $pesanSukses);
        $this->redirect($tujuan);
    }

    protected function render(string $view, array $data = []): void
    {
        $pengguna = $this->pengguna();
        extract($data);
        ob_start();
        require __DIR__ . "/../views/$view.php";
        $isi = ob_get_clean();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require __DIR__ . '/../views/layout.php';
    }
}
