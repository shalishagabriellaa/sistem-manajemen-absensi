<?php
$target = $_SERVER['DOCUMENT_ROOT'] . "/storage/app/public";
$link   = $_SERVER['DOCUMENT_ROOT'] . "/public/storage";
if (is_link($link) || file_exists($link)) {
    echo "Link sudah ada.";
    return;
}
if (symlink($target, $link)) {
    echo "OK.";
} else {
    echo "Fail.";
}
?>

<!-- SYMLINK HOSTINGER
AKTIFKAN SYMLINK DI KONFIGURASI PHP
(hapus dari disable) -->
