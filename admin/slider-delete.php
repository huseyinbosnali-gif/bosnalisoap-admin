<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


$id = $_GET["id"] ?? null;


if (!$id) {
    header("Location: sliders.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| SLIDER BİLGİSİNİ AL
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT image FROM sliders WHERE id=?"
);

$sorgu->execute([$id]);

$slider = $sorgu->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| SLIDER GÖRSELİNİ SİL
|--------------------------------------------------------------------------
*/

if ($slider && !empty($slider["image"])) {

    $dosya =
        "../uploads/sliders/" .
        $slider["image"];


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
    "DELETE FROM sliders WHERE id=?"
);

$sil->execute([$id]);


/*
|--------------------------------------------------------------------------
| SLIDER SAYFASINA DÖN
|--------------------------------------------------------------------------
*/

header("Location: sliders.php");

exit;

?>