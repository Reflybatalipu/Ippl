<?php
// Memuat class otomatis berdasarkan namespace -> folder.
spl_autoload_register(function (string $kelas): void {
    $peta = [
        'App\\Core\\'        => 'core/',
        'App\\Models\\'      => 'models/',
        'App\\Controllers\\' => 'controllers/',
    ];
    foreach ($peta as $prefix => $folder) {
        if (str_starts_with($kelas, $prefix)) {
            $berkas = __DIR__ . '/' . $folder . substr($kelas, strlen($prefix)) . '.php';
            if (is_file($berkas)) {
                require $berkas;
            }
        }
    }
});
