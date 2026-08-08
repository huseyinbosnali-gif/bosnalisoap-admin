<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: catalogs.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| KATALOG BİLGİSİNİ AL
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT pdf_file FROM catalogs WHERE id=?"
);

$sorgu->execute([$id]);

$katalog = $sorgu->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| PDF DOSYASINI SİL
|--------------------------------------------------------------------------
*/

if ($katalog && !empty($katalog["pdf_file"])) {

    $dosya =
        "../uploads/catalogs/" .
        $katalog["pdf_file"];

    if (file_exists($dosya)) {
        unlink($dosya);
    }
}


/*
|--------------------------------------------------------------------------
| VERİTABANINDAN SİL
|--------------------------------------------------------------------------
*/

$sil = $pdo->prepare(
    "DELETE FROM catalogs WHERE id=?"
);

$sil->execute([$id]);


header("Location: catalogs.php");
exit;

?>