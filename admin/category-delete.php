<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


$id = $_GET["id"] ?? null;


if (!$id) {
    header("Location: categories.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| KATEGORİ BİLGİSİNİ AL
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT image FROM categories WHERE id=?"
);

$sorgu->execute([$id]);

$kategori = $sorgu->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| KATEGORİ GÖRSELİNİ SİL
|--------------------------------------------------------------------------
*/

if ($kategori && !empty($kategori["image"])) {

    $dosya =
        "../uploads/categories/" .
        $kategori["image"];


    if (file_exists($dosya)) {
        unlink($dosya);
    }
}


/*
|--------------------------------------------------------------------------
| KATEGORİYİ VERİTABANINDAN SİL
|--------------------------------------------------------------------------
*/

$sil = $pdo->prepare(
    "DELETE FROM categories WHERE id=?"
);

$sil->execute([$id]);


/*
|--------------------------------------------------------------------------
| KATEGORİ SAYFASINA DÖN
|--------------------------------------------------------------------------
*/

header("Location: categories.php");

exit;

?>