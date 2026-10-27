<?php
namespace App\Models;

use DomainException;
use InvalidArgumentException;

class Mahasiswa extends Model
{
    public function __construct(
        private ?int $idMahasiswa,
        private int $idTingkat,
        private string $nama,
        private string $email,
        private string $kataSandi,
        private string $peran = 'mahasiswa'
    ) {}

    public function getIdMahasiswa(): ?int { return $this->idMahasiswa; }
    public function getIdTingkat(): int { return $this->idTingkat; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
    public function getPeran(): string { return $this->peran; }

    /** Polimorfisme: baris basis data menjadi Mahasiswa atau KetuaTingkat sesuai kolom peran. */
    public static function dariBaris(array $r): self
    {
        $kelas = $r['peran'] === 'ketua_tingkat' ? KetuaTingkat::class : self::class;
        return new $kelas((int) $r['id_mahasiswa'], (int) $r['id_tingkat'], $r['nama'], $r['email'], $r['kata_sandi'], $r['peran']);
    }

    public static function cariEmail(string $email): ?self
    {
        $st = self::db()->prepare('SELECT * FROM mahasiswa WHERE email = ?');
        $st->execute([$email]);
        $r = $st->fetch();
        return $r ? self::dariBaris($r) : null;
    }

    public static function cariId(int $id): ?self
    {
        $st = self::db()->prepare('SELECT * FROM mahasiswa WHERE id_mahasiswa = ?');
        $st->execute([$id]);
        $r = $st->fetch();
        return $r ? self::dariBaris($r) : null;
    }

    public static function registrasi(string $nama, string $email, string $sandi, int $idTingkat): self
    {
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama wajib diisi.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }
        if (self::cariEmail($email) !== null) {
            throw new InvalidArgumentException('Email sudah terdaftar.');
        }
        if (strlen($sandi) < 8) {
            throw new InvalidArgumentException('Kata sandi minimal 8 karakter.');
        }
        if (Tingkat::cari($idTingkat) === null) {
            throw new InvalidArgumentException('Tingkat tidak ditemukan.');
        }
        // Peran selalu "mahasiswa"; Ketua Tingkat hanya ditetapkan lewat basis data.
        $mhs = new self(null, $idTingkat, trim($nama), $email, password_hash($sandi, PASSWORD_DEFAULT));
        $st = self::db()->prepare('INSERT INTO mahasiswa (id_tingkat, nama, email, kata_sandi, peran) VALUES (?,?,?,?,?)');
        $st->execute([$mhs->idTingkat, $mhs->nama, $mhs->email, $mhs->kataSandi, $mhs->peran]);
        $mhs->idMahasiswa = (int) self::db()->lastInsertId();
        return $mhs;
    }

    public function login(string $sandi): bool
    {
        return password_verify($sandi, $this->kataSandi);
    }

    public function lihatTugas(): array
    {
        return Tugas::untukMahasiswa($this->idTingkat, (int) $this->idMahasiswa);
    }

    public function lihatAgenda(): array
    {
        return Agenda::milik((int) $this->idMahasiswa);
    }

    public function ubahStatus(int $idTugas, string $status): void
    {
        $tugas = Tugas::cari($idTugas);
        if ($tugas === null || $tugas->getIdTingkat() !== $this->idTingkat) {
            throw new DomainException('Tugas tidak ditemukan pada tingkat Anda.');
        }
        $tm = new TugasMahasiswa(null, $idTugas, (int) $this->idMahasiswa);
        $tm->ubahStatus($status);
        $tm->simpan();
    }

    public function tambahAgenda(Agenda $agenda): void
    {
        if ($agenda->getIdMahasiswa() !== $this->idMahasiswa) {
            throw new DomainException('Agenda hanya dapat dibuat untuk akun sendiri.');
        }
        if (!$agenda->validasi()) {
            throw new InvalidArgumentException(implode(' ', $agenda->getKesalahan()));
        }
        $agenda->simpan();
    }
}
