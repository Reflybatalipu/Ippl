<?php
namespace App\Models;

use DomainException;
use InvalidArgumentException;

/** Pewarisan: KetuaTingkat adalah Mahasiswa dengan hak tambahan mengelola tugas tingkat. */
class KetuaTingkat extends Mahasiswa
{
    public function tambahTugas(Tugas $tugas): void
    {
        $this->pastikanTingkatSendiri($tugas->getIdTingkat());
        $this->pastikanValid($tugas);
        $tugas->simpan();
    }

    public function ubahTugas(Tugas $tugas): void
    {
        $lama = Tugas::cari((int) $tugas->getIdTugas());
        if ($lama === null) {
            throw new DomainException('Tugas tidak ditemukan.');
        }
        $this->pastikanTingkatSendiri($lama->getIdTingkat());
        $this->pastikanTingkatSendiri($tugas->getIdTingkat());
        $this->pastikanValid($tugas);
        $tugas->simpan();
    }

    public function hapusTugas(int $idTugas): void
    {
        $tugas = Tugas::cari($idTugas);
        if ($tugas === null) {
            throw new DomainException('Tugas tidak ditemukan.');
        }
        $this->pastikanTingkatSendiri($tugas->getIdTingkat());
        Tugas::hapus($idTugas);
    }

    private function pastikanTingkatSendiri(int $idTingkat): void
    {
        if ($idTingkat !== $this->getIdTingkat()) {
            throw new DomainException('Anda hanya dapat mengelola tugas pada tingkat sendiri.');
        }
    }

    private function pastikanValid(Tugas $tugas): void
    {
        if (!$tugas->validasi()) {
            throw new InvalidArgumentException(implode(' ', $tugas->getKesalahan()));
        }
    }
}
