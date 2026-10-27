<?php
namespace App\Models;

use DateTimeImmutable;

class Agenda extends Model
{
    private array $kesalahan = [];

    public function __construct(
        private ?int $idAgenda,
        private int $idMahasiswa,
        private string $namaKegiatan,
        private ?string $organisasi,
        private DateTimeImmutable $waktuMulai,
        private ?string $deskripsi
    ) {}

    public function getIdMahasiswa(): int { return $this->idMahasiswa; }
    public function getKesalahan(): array { return $this->kesalahan; }

    public function validasi(): bool
    {
        $this->kesalahan = [];
        if (trim($this->namaKegiatan) === '') {
            $this->kesalahan[] = 'Nama kegiatan wajib diisi.';
        }
        return $this->kesalahan === [];
    }

    public function simpan(): void
    {
        $waktu = $this->waktuMulai->format('Y-m-d H:i:s');
        if ($this->idAgenda === null) {
            $st = self::db()->prepare('INSERT INTO agenda (id_mahasiswa, nama_kegiatan, organisasi, waktu_mulai, deskripsi) VALUES (?,?,?,?,?)');
            $st->execute([$this->idMahasiswa, $this->namaKegiatan, $this->organisasi, $waktu, $this->deskripsi]);
            $this->idAgenda = (int) self::db()->lastInsertId();
            return;
        }
        $st = self::db()->prepare('UPDATE agenda SET nama_kegiatan = ?, organisasi = ?, waktu_mulai = ?, deskripsi = ? WHERE id_agenda = ? AND id_mahasiswa = ?');
        $st->execute([$this->namaKegiatan, $this->organisasi, $waktu, $this->deskripsi, $this->idAgenda, $this->idMahasiswa]);
    }

    /** Agenda hanya dapat dihapus oleh pemiliknya. */
    public static function hapus(int $idAgenda, int $idMahasiswa): void
    {
        self::db()->prepare('DELETE FROM agenda WHERE id_agenda = ? AND id_mahasiswa = ?')->execute([$idAgenda, $idMahasiswa]);
    }

    public static function milik(int $idMahasiswa): array
    {
        $st = self::db()->prepare('SELECT * FROM agenda WHERE id_mahasiswa = ? ORDER BY waktu_mulai');
        $st->execute([$idMahasiswa]);
        return $st->fetchAll();
    }
}
