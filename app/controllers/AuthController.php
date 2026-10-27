<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;
use App\Models\Tingkat;

class AuthController extends Controller
{
    public function formLogin(): void
    {
        $this->render('auth/login');
    }

    public function login(): void
    {
        $this->verifikasiCsrf();
        $mhs = Mahasiswa::cariEmail(trim($_POST['email'] ?? ''));
        if ($mhs === null || !$mhs->login($_POST['sandi'] ?? '')) {
            $this->flash('galat', 'Email atau kata sandi salah.');
            $this->redirect('/login');
        }
        session_regenerate_id(true);
        $_SESSION['id_mahasiswa'] = $mhs->getIdMahasiswa();
        $this->redirect('/');
    }

    public function formRegistrasi(): void
    {
        $this->render('auth/registrasi', ['daftarTingkat' => Tingkat::semua()]);
    }

    public function registrasi(): void
    {
        $this->verifikasiCsrf();
        $this->proses(
            fn () => Mahasiswa::registrasi(
                $_POST['nama'] ?? '', trim($_POST['email'] ?? ''), $_POST['sandi'] ?? '', (int) ($_POST['id_tingkat'] ?? 0)
            ),
            'Registrasi berhasil. Silakan masuk.',
            '/login',
            '/registrasi'
        );
    }

    public function logout(): void
    {
        $this->verifikasiCsrf();
        session_destroy();
        $this->redirect('/login');
    }
}
