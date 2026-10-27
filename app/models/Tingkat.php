<?php
namespace App\Models;

class Tingkat extends Model
{
    public function __construct(
        private ?int $idTingkat,
        private string $namaTingkat,
        private int $tahunAngkatan
    ) {}

    public function getIdTingkat(): ?int { return $this->idTingkat; }
    public function getNamaTingkat(): string { return $this->namaTingkat; }

    public static function semua(): array
    {
        $baris = self::db()->query('SELECT * FROM tingkat ORDER BY tahun_angkatan DESC')->fetchAll();
        return array_map(fn ($r) => new self((int) $r['id_tingkat'], $r['nama_tingkat'], (int) $r['tahun_angkatan']), $baris);
    }

    public static function cari(int $id): ?self
    {
        $st = self::db()->prepare('SELECT * FROM tingkat WHERE id_tingkat = ?');
        $st->execute([$id]);
        $r = $st->fetch();
        return $r ? new self((int) $r['id_tingkat'], $r['nama_tingkat'], (int) $r['tahun_angkatan']) : null;
    }

    public function getMahasiswa(): array
    {
        $st = self::db()->prepare('SELECT * FROM mahasiswa WHERE id_tingkat = ? ORDER BY nama');
        $st->execute([$this->idTingkat]);
        return array_map([Mahasiswa::class, 'dariBaris'], $st->fetchAll());
    }

    public function getTugas(): array
    {
        return Tugas::daftarTingkat((int) $this->idTingkat);
    }
}
