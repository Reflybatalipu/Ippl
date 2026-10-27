<?php
namespace App\Models;
use DateTimeImmutable;
class Tugas extends Model
{
    private array $kesalahan = [];
    public function __construct(
        private ?int $idTugas,
        private int $idTingkat,
        private int $idPembuat,
        private string $judul,
        private ?string $mataKuliah,
        private ?string $deskripsi,
        private DateTimeImmutable $tenggat
    ) {}
    public function getIdTugas(): ?int { return $this->idTugas; }
    public function getIdTingkat(): int { return $this->idTingkat; }
    public function getKesalahan(): array { return $this->kesalahan; }

    public function validasi(): bool
    {
        $this->kesalahan = [];
        if (trim($this->judul) === '') {
            $this->kesalahan[] = 'Judul tugas wajib diisi.';
        }
        // Aturan tenggat hanya berlaku untuk tugas baru.
        if ($this->idTugas === null && $this->sudahLewat()) {
            $this->kesalahan[] = 'Tenggat tidak boleh di masa lalu.';
        }
        return $this->kesalahan === [];
    }
    public function sudahLewat(): bool
    {
        return $this->tenggat < new DateTimeImmutable();
    }
    public function simpan(): void
    {
        $waktu = $this->tenggat->format('Y-m-d H:i:s');
        if ($this->idTugas === null) {
            $st = self::db()->prepare('INSERT INTO tugas (id_tingkat, id_pembuat, judul, mata_kuliah, deskripsi, tenggat) VALUES (?,?,?,?,?,?)');
            $st->execute([$this->idTingkat, $this->idPembuat, $this->judul, $this->mataKuliah, $this->deskripsi, $waktu]);
            $this->idTugas = (int) self::db()->lastInsertId();
            return;
        }
        $st = self::db()->prepare('UPDATE tugas SET judul = ?, mata_kuliah = ?, deskripsi = ?, tenggat = ? WHERE id_tugas = ?');
        $st->execute([$this->judul, $this->mataKuliah, $this->deskripsi, $waktu, $this->idTugas]);
    }
    public static function hapus(int $idTugas): void
    {
        // Baris tugas_mahasiswa ikut terhapus lewat ON DELETE CASCADE.
        self::db()->prepare('DELETE FROM tugas WHERE id_tugas = ?')->execute([$idTugas]);
    }
    public static function cari(int $idTugas): ?self
    {
        $st = self::db()->prepare('SELECT * FROM tugas WHERE id_tugas = ?');
        $st->execute([$idTugas]);
        $r = $st->fetch();
        return $r ? self::dariBaris($r) : null;
    }
    public static function daftarTingkat(int $idTingkat): array
    {
        $st = self::db()->prepare('SELECT * FROM tugas WHERE id_tingkat = ? ORDER BY tenggat');
        $st->execute([$idTingkat]);
        return array_map([self::class, 'dariBaris'], $st->fetchAll());
    }
    /** Daftar tugas tingkat beserta status milik satu mahasiswa (default "belum"). */
    public static function untukMahasiswa(int $idTingkat, int $idMahasiswa): array
    {
        $st = self::db()->prepare(
            "SELECT t.*, COALESCE(tm.status, 'belum') AS status
             FROM tugas t
             LEFT JOIN tugas_mahasiswa tm ON tm.id_tugas = t.id_tugas AND tm.id_mahasiswa = ?
             WHERE t.id_tingkat = ? ORDER BY t.tenggat"
        );
        $st->execute([$idMahasiswa, $idTingkat]);
        return $st->fetchAll();
    }
    private static function dariBaris(array $r): self
    {
        return new self(
            (int) $r['id_tugas'], (int) $r['id_tingkat'], (int) $r['id_pembuat'],
            $r['judul'], $r['mata_kuliah'], $r['deskripsi'], new DateTimeImmutable($r['tenggat'])
        );
    }
}
