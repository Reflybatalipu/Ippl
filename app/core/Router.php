<?php
namespace App\Core;

class Router
{
    private array $rute = [];

    public function get(string $path, array $handler): void
    {
        $this->rute['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->rute['POST'][$path] = $handler;
    }

    public function jalankan(string $metode, string $uri): void
    {
        $basis = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        $path = substr((string) parse_url($uri, PHP_URL_PATH), strlen($basis));
        $path = rtrim($path, '/') ?: '/';

        $handler = $this->rute[$metode][$path] ?? null;
        if ($handler === null) {
            http_response_code(404);
            exit('Halaman tidak ditemukan.');
        }
        [$kelas, $aksi] = $handler;
        (new $kelas())->$aksi();
    }
}
