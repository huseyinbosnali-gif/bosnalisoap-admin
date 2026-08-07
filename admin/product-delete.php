<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


$id = $_GET["id"];


// Önce ürün bilgisini alıyoruz
$sorgu = $pdo->prepare(
    "SELECT image FROM products WHERE id=?"
);

$sorgu->execute([$id]);

$urun = $sorgu->fetch(PDO::FETCH_ASSOC);


// Resim varsa klasörden siliyoruz
if($urun && $urun["image"]){

    $dosya = "../uploads/" . $urun["image"];

    if(file_exists($dosya)){
        unlink($dosya);
    }

}


// Veritabanından siliyoruz

$sil = $pdo->prepare(
    "DELETE FROM products WHERE id=?"
);

$sil->execute([$id]);



header("Location: products.php");

exit;

?>