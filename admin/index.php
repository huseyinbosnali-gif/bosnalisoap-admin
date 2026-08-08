<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$admin = $_SESSION["admin"];

/*
|--------------------------------------------------------------------------
| Kullanıcı bilgileri
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT * FROM users WHERE username=?"
);

$sorgu->execute([$admin]);

$kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Toplam ürün sayısı
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->query(
    "SELECT COUNT(*) FROM products"
);

$toplam_urun = $sorgu->fetchColumn();


/*
|--------------------------------------------------------------------------
| Son eklenen ürünler
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->query(
    "SELECT id, name, category, image, created_at
     FROM products
     ORDER BY id DESC
     LIMIT 5"
);

$son_urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);
$sorgu = $pdo->query(
    "SELECT id, name, image
     FROM categories
     ORDER BY id ASC"
);

$kategoriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| AKTİF SLIDERLARI VERİTABANINDAN AL
|--------------------------------------------------------------------------
*/

$sliderSorgu = $pdo->query(
    "SELECT *
     FROM sliders
     WHERE is_active = 1
     ORDER BY sort_order ASC, id ASC"
);

$sliderlar = $sliderSorgu->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Son yedekleme zamanı
|--------------------------------------------------------------------------
|
| Yedek klasörleri proje klasörünün yanında bulunuyor.
|
*/

$proje_klasoru = dirname(__DIR__);

$yedekler = glob(
    $proje_klasoru . "_yedek_*",
    GLOB_ONLYDIR
);

$son_yedekleme = null;

if (!empty($yedekler)) {

    $en_son_yedek = null;
    $en_son_zaman = 0;

    foreach ($yedekler as $yedek) {

        $zaman = filemtime($yedek);

        if ($zaman > $en_son_zaman) {

            $en_son_zaman = $zaman;
            $en_son_yedek = $yedek;
        }
    }

    if ($en_son_yedek) {
        $son_yedekleme = date(
            "d.m.Y H:i",
            filemtime($en_son_yedek)
        );
    }
}

?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


    <!-- BAŞLIK -->

    <h1>
        Yönetim Paneli
    </h1>


    <p style="color:#666;margin-bottom:25px;">
        Hoş geldiniz,
        <b><?= htmlspecialchars($admin) ?></b>
    </p>



    <!-- İSTATİSTİK KARTLARI -->

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:20px;
        margin-bottom:25px;
    ">


        <!-- ÜRÜN SAYISI -->

        <div class="card">

            <div style="font-size:32px;">
                📦
            </div>

            <h2 style="margin:10px 0 5px;">
                <?= $toplam_urun ?>
            </h2>

            <p>
                Toplam Ürün
            </p>

        </div>



        <!-- SON GİRİŞ -->

        <div class="card">

            <div style="font-size:32px;">
                🕒
            </div>

            <h3>
                Son Giriş
            </h3>

            <p>

                <?php if (!empty($kullanici["last_login"])): ?>

                    <?= htmlspecialchars($kullanici["last_login"]) ?>

                <?php else: ?>

                    Henüz kayıt yok

                <?php endif; ?>

            </p>

        </div>



        <!-- YEDEKLEME -->

        <div class="card">

            <div style="font-size:32px;">
                💾
            </div>

            <h3>
                Son Yedekleme
            </h3>

            <p>

                <?php if ($son_yedekleme): ?>

                    <?= $son_yedekleme ?>

                <?php else: ?>

                    Henüz yedek yok

                <?php endif; ?>

            </p>

        </div>



        <!-- SİSTEM -->

        <div class="card">

            <div style="font-size:32px;">
                🟢
            </div>

            <h3>
                Sistem Durumu
            </h3>

            <p>
                Sistem aktif
            </p>

        </div>


    </div>



    <!-- HIZLI İŞLEMLER -->

    <div class="card">

        <h2>
            Hızlı İşlemler
        </h2>


        <div style="
            display:flex;
            flex-wrap:wrap;
            gap:10px;
            margin-top:15px;
        ">


            <a
                href="products.php"
                class="button"
            >
                📦 Ürün Yönetimi
            </a>


            <a
                href="product-add.php"
                class="button"
            >
                ➕ Yeni Ürün Ekle
            </a>


            <a
                href="../index.php"
                target="_blank"
                class="button"
            >
                👁️ Siteyi Görüntüle
            </a>


        </div>

    </div>



    <!-- SON EKLENEN ÜRÜNLER -->

    <div class="card">

        <h2>
            Son Eklenen Ürünler
        </h2>


        <?php if (!empty($son_urunler)): ?>


            <div style="
                overflow-x:auto;
                margin-top:15px;
            ">


                <table
                    style="
                        width:100%;
                        border-collapse:collapse;
                    "
                >


                    <thead>

                        <tr>

                            <th style="text-align:left;padding:10px;">
                                Resim
                            </th>

                            <th style="text-align:left;padding:10px;">
                                Ürün
                            </th>

                            <th style="text-align:left;padding:10px;">
                                Kategori
                            </th>

                            <th style="text-align:left;padding:10px;">
                                Tarih
                            </th>

                            <th style="text-align:left;padding:10px;">
                                İşlem
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach ($son_urunler as $urun): ?>


                        <tr>


                            <td style="padding:10px;">

                                <?php if (!empty($urun["image"])): ?>

                                    <img
                                        src="../uploads/<?= htmlspecialchars($urun["image"]) ?>"
                                        style="
                                            width:55px;
                                            height:55px;
                                            object-fit:cover;
                                            border-radius:6px;
                                        "
                                    >

                                <?php else: ?>

                                    Resim Yok

                                <?php endif; ?>

                            </td>



                            <td style="padding:10px;">

                                <b>
                                    <?= htmlspecialchars($urun["name"]) ?>
                                </b>

                            </td>



                            <td style="padding:10px;">

                                <?= htmlspecialchars($urun["category"]) ?>

                            </td>



                            <td style="padding:10px;">

                                <?= htmlspecialchars($urun["created_at"]) ?>

                            </td>



                            <td style="padding:10px;">

                                <a
                                    href="product-edit.php?id=<?= $urun["id"] ?>"
                                >
                                    ✏ Düzenle
                                </a>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                    </tbody>

                </table>

            </div>


        <?php else: ?>


            <p>
                Henüz ürün eklenmemiş.
            </p>


        <?php endif; ?>


    </div>



    <!-- CANLI ÖNİZLEME -->

    <div class="card">

        <h2>
            🌐 Canlı Site Önizleme
        </h2>

        <p style="color:#666;">
            Sitenin mevcut görünümünü yönetim panelinden görüntüleyebilirsiniz.
        </p>


        <div style="
            width:100%;
            height:600px;
            overflow:hidden;
            border:1px solid #ddd;
            border-radius:8px;
            margin-top:15px;
        ">


            <iframe
                src="../index.php"
                style="
                    width:100%;
                    height:100%;
                    border:0;
                "
            ></iframe>


        </div>

    </div>


</div>


<?php include "../includes/footer.php"; ?>