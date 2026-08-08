<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$id = $_GET["id"];

// Ürünün dosya bilgilerini alıyoruz
$sorgu = $pdo->prepare(
    "SELECT image, pdf_file, msds_file FROM products WHERE id=?"
);

$sorgu->execute([$id]);

$urun = $sorgu->fetch(PDO::FETCH_ASSOC);


// Ürün bulunduysa dosyaları siliyoruz
if ($urun) {

    // Ürün resmi
    if (!empty($urun["image"])) {

        $dosya = "../uploads/" . $urun["image"];

        if (file_exists($dosya)) {
            unlink($dosya);
        }
    }


    // PDF
    if (!empty($urun["pdf_file"])) {

        $dosya = "../uploads/pdf/" . $urun["pdf_file"];

        if (file_exists($dosya)) {
            unlink($dosya);
        }
    }


    // MSDS
    if (!empty($urun["msds_file"])) {

        $dosya = "../uploads/msds/" . $urun["msds_file"];

        if (file_exists($dosya)) {
            unlink($dosya);
        }
    }
}


// Veritabanından ürünü siliyoruz
$sil = $pdo->prepare(
    "DELETE FROM products WHERE id=?"
);

$sil->execute([$id]);


// Ürünler sayfasına dön
header("Location: products.php");
exit;

?>