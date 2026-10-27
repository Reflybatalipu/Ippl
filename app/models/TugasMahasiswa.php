<?php
namespace App\Models;

use InvalidArgumentException;

/** Status tugas yang bersifat individual untuk tiap mahasiswa. */
class TugasMahasiswa extends Model
{
    public const STATUS = ['belum', 'proses', 'selesai'];

    public function __construct(
        private ?int $id,
        private int $idTugas,
        private int $idMahasiswa,
        private string $status = 'belum'
    ) {}

    public function ubahStatus(string $status): void
    {
        if (!in_array($status, self::STATUS, true)) {
            throw new InvalidArgumentException('Status tidak valid.');
        }
        $this->status = $status;
    }

    public function simpan(): void
    {
        $st = self::db()->prepare(
            'INSERT INTO tugas_mahasiswa (id_tugas, id_mahasiswa, status) VALUES (?,?,?)
             ON DUPLICATE KEY UPDATE status = VALUES(status)'
        );
        $st->execute([$this->idTugas, $this->idMahasiswa, $this->status]);
    }
}
