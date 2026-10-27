<?php
function e(?string $teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function url(string $path = '/'): string
{
    return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . $path;
}

function tgl(string $waktu): string
{
    return date('d/m/Y H:i', strtotime($waktu));
}

function csrf_field(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">';
}
