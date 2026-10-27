CREATE DATABASE IF NOT EXISTS sistem_tugas CHARACTER SET utf8mb4;
USE sistem_tugas;

CREATE TABLE tingkat (
  id_tingkat INT AUTO_INCREMENT PRIMARY KEY,
  nama_tingkat VARCHAR(100) NOT NULL,
  tahun_angkatan INT NOT NULL
);

CREATE TABLE mahasiswa (
  id_mahasiswa INT AUTO_INCREMENT PRIMARY KEY,
  id_tingkat INT NOT NULL,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  kata_sandi VARCHAR(255) NOT NULL,
  peran ENUM('mahasiswa','ketua_tingkat') NOT NULL DEFAULT 'mahasiswa',
  FOREIGN KEY (id_tingkat) REFERENCES tingkat(id_tingkat)
);

CREATE TABLE tugas (
  id_tugas INT AUTO_INCREMENT PRIMARY KEY,
  id_tingkat INT NOT NULL,
  id_pembuat INT NOT NULL,
  judul VARCHAR(150) NOT NULL,
  mata_kuliah VARCHAR(100),
  deskripsi TEXT,
  tenggat DATETIME NOT NULL,
  FOREIGN KEY (id_tingkat) REFERENCES tingkat(id_tingkat),
  FOREIGN KEY (id_pembuat) REFERENCES mahasiswa(id_mahasiswa)
);

CREATE TABLE tugas_mahasiswa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_tugas INT NOT NULL,
  id_mahasiswa INT NOT NULL,
  status ENUM('belum','proses','selesai') NOT NULL DEFAULT 'belum',
  UNIQUE (id_tugas, id_mahasiswa),
  FOREIGN KEY (id_tugas) REFERENCES tugas(id_tugas) ON DELETE CASCADE,
  FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE
);

CREATE TABLE agenda (
  id_agenda INT AUTO_INCREMENT PRIMARY KEY,
  id_mahasiswa INT NOT NULL,
  nama_kegiatan VARCHAR(150) NOT NULL,
  organisasi VARCHAR(100),
  waktu_mulai DATETIME NOT NULL,
  deskripsi TEXT,
  FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE
);

-- Data awal
INSERT INTO tingkat (nama_tingkat, tahun_angkatan) VALUES ('Angkatan 2024 Kelas A', 2024);

-- Ketua Tingkat ditetapkan manual setelah akunnya terdaftar:
-- UPDATE mahasiswa SET peran = 'ketua_tingkat' WHERE email = 'ketua@contoh.ac.id';
