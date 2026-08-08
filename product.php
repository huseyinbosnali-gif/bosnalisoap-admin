php<?php

require_once "config/database.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| ÜRÜNÜ GETİR
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT * FROM products WHERE id=?"
);

$sorgu->execute([$id]);

$urun = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$urun) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
<?= htmlspecialchars($urun["name"]) ?> | Bosnalı SOAX
</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f4efe4;
    color:#30352d;
}

a{
    text-decoration:none;
    color:inherit;
}


/* HEADER */

.header{
    background:#f4efe4;
    border-bottom:1px solid rgba(60,75,45,.15);
}

.header-inner{
    max-width:1280px;
    margin:auto;
    padding:18px 30px;
    min-height:100px;

    display:flex;
    align-items:center;
    justify-content:space-between;
}

.logo-main{
    font-family:Georgia, serif;
    font-size:34px;
    font-weight:bold;
    color:#52643c;
}

.logo-sub{
    font-size:12px;
    letter-spacing:5px;
    color:#817765;
}

.nav{
    display:flex;
    gap:28px;
    flex-wrap:wrap;
}

.nav a{
    color:#4f5943;
    font-size:14px;
    font-weight:bold;
}


/* ANA ALAN */

.product-detail{
    max-width:1280px;
    margin:auto;
    padding:60px 30px 80px;

    display:grid;
    grid-template-columns:1fr 1fr;
    gap:55px;
}


/* GÖRSEL */

.product-image{
    background:#ebe6d8;
    min-height:500px;

    display:flex;
    align-items:center;
    justify-content:center;

    overflow:hidden;
}

.product-image img{
    width:100%;
    height:100%;
    max-height:600px;
    object-fit:cover;
}

.no-image{
    color:#777;
    text-align:center;
    font-size:16px;
}


/* BİLGİLER */

.product-info h1{
    margin:0 0 15px;
    font-family:Georgia, serif;
    font-size:42px;
    color:#52643c;
}

.category{
    display:inline-block;
    margin-bottom:25px;

    padding:8px 14px;

    background:#dfe4cf;

    color:#596b3f;

    border-radius:20px;

    font-size:13px;

    font-weight:bold;
}

.description{
    font-size:16px;
    line-height:1.8;
    color:#5f6054;
    margin-bottom:30px;
}


/* BİLGİ TABLOSU */

.info-table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:30px;
}

.info-table td{
    padding:12px 10px;
    border-bottom:1px solid #ddd7c8;
    vertical-align:top;
}

.info-table td:first-child{
    width:180px;
    font-weight:bold;
    color:#596b3f;
}


/* TEKNİK */

.technical{
    margin-top:30px;

    padding:25px;

    background:#ebe6d8;

    border-left:4px solid #596b3f;
}

.technical h2{
    margin-top:0;
    font-family:Georgia, serif;
    color:#52643c;
}

.technical-content{
    line-height:1.8;
    color:#5f6054;
}


/* DOSYALAR */

.files{
    margin-top:30px;
}

.files h2{
    font-family:Georgia, serif;
    color:#52643c;
}

.file-buttons{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.file-button{
    display:inline-block;

    padding:13px 20px;

    background:#596b3f;

    color:white;

    border-radius:25px;

    font-size:14px;

    font-weight:bold;

    transition:.2s;
}

.file-button:hover{
    background:#43532f;
}

.file-button.secondary{
    background:#7c8568;
}


/* GERİ */

.back-area{
    max-width:1280px;
    margin:30px auto 0;
    padding:0 30px;
}

.back-link{
    color:#596b3f;
    font-weight:bold;
}


/* FOOTER */

.footer{
    background:#3f4d31;
    color:#e8eadf;
    text-align:center;
    padding:25px 20px;
    font-size:14px;
}


/* MOBİL */

@media(max-width:900px){

    .header-inner{
        flex-direction:column;
        gap:20px;
    }

    .product-detail{
        grid-template-columns:1fr;
    }

}

@media(max-width:600px){

    .product-detail{
        padding:40px 20px 60px;
    }

    .product-info h1{
        font-size:34px;
    }

    .info-table td:first-child{
        width:130px;
    }

}

</style>

</head>

<body>


<header class="header">

    <div class="header-inner">

        <a href="index.php">

            <div class="logo-main">
                Bosnalı
            </div>

            <div class="logo-sub">
                SABUN
            </div>

        </a>


        <nav class="nav">

            <a href="index.php">
                ANA SAYFA
            </a>

            <a href="index.php#urunler">
                ÜRÜNLER
            </a>

            <a href="#">
                HAKKIMIZDA
            </a>

            <a href="#">
                İLETİŞİM
            </a>

            <a href="#">
                KATALOG
            </a>

        </nav>

    </div>

</header>



<div class="back-area">

    <a
        href="javascript:history.back()"
        class="back-link"
    >
        ← Ürünlere Dön
    </a>

</div>



<section class="product-detail">


    <!-- ÜRÜN GÖRSELİ -->

    <div class="product-image">

        <?php if (!empty($urun["image"])): ?>

            <img
                src="uploads/<?= htmlspecialchars($urun["image"]) ?>"
                alt="<?= htmlspecialchars($urun["name"]) ?>"
            >

        <?php else: ?>

            <div class="no-image">
                Ürün Görseli Yok
            </div>

        <?php endif; ?>

    </div>



    <!-- ÜRÜN BİLGİLERİ -->

    <div class="product-info">


        <h1>
            <?= htmlspecialchars($urun["name"]) ?>
        </h1>


        <?php if (!empty($urun["category"])): ?>

            <div class="category">
                <?= htmlspecialchars($urun["category"]) ?>
            </div>

        <?php endif; ?>


        <?php if (!empty($urun["description"])): ?>

            <div class="description">

                <?= nl2br(
                    htmlspecialchars(
                        $urun["description"]
                    )
                ) ?>

            </div>

        <?php endif; ?>



        <table class="info-table">


            <?php if (!empty($urun["product_code"])): ?>

                <tr>
                    <td>Ürün Kodu</td>
                    <td>
                        <?= htmlspecialchars($urun["product_code"]) ?>
                    </td>
                </tr>

            <?php endif; ?>


            <?php if (!empty($urun["barcode"])): ?>

                <tr>
                    <td>Barkod</td>
                    <td>
                        <?= htmlspecialchars($urun["barcode"]) ?>
                    </td>
                </tr>

            <?php endif; ?>


            <?php if (!empty($urun["lot_number"])): ?>

                <tr>
                    <td>Lot No</td>
                    <td>
                        <?= htmlspecialchars($urun["lot_number"]) ?>
                    </td>
                </tr>

            <?php endif; ?>


            <?php if (!empty($urun["production_date"])): ?>

                <tr>
                    <td>Üretim Tarihi</td>
                    <td>
                        <?= htmlspecialchars($urun["production_date"]) ?>
                    </td>
                </tr>

            <?php endif; ?>


        </table>



        <!-- TEKNİK BİLGİLER -->

        <?php if (!empty($urun["technical_info"])): ?>

            <div class="technical">

                <h2>
                    Teknik Bilgiler
                </h2>

                <div class="technical-content">

                    <?= nl2br(
                        htmlspecialchars(
                            $urun["technical_info"]
                        )
                    ) ?>

                </div>

            </div>

        <?php endif; ?>



<!-- DOSYALAR -->

<?php
$pdfVar = !empty($urun["pdf_file"]);
$msdsVar = !empty($urun["msds_file"]);
$analysisVar = !empty($urun["analysis_file"]);
?>

<?php if ($pdfVar || $msdsVar || $analysisVar): ?>

    <div class="files">

        <h2>
            Dokümanlar
        </h2>

        <div class="file-buttons">

            <?php if ($pdfVar): ?>

                <a
                    class="file-button"
                    href="uploads/pdf/<?= htmlspecialchars($urun["pdf_file"]) ?>"
                    target="_blank"
                >
                    📄 Ürün PDF
                </a>

            <?php endif; ?>


            <?php if ($msdsVar): ?>

                <a
                    class="file-button secondary"
                    href="uploads/msds/<?= htmlspecialchars($urun["msds_file"]) ?>"
                    download
                >
                    📄 MSDS İndir
                </a>

            <?php endif; ?>


            <?php if ($analysisVar): ?>

    <a
        class="file-button secondary"
        href="uploads/analysis/<?= htmlspecialchars($urun["analysis_file"]) ?>"
        download
    >
        📄 Analiz / COA İndir
    </a>

<?php endif; ?>


</div>

</div>

<?php endif; ?>


    </div>


</section>



<footer class="footer">

    © <?= date("Y") ?>
    Bosnalı Sabun Yağ ve Kimyevi Ürünler Ltd. Şti.

</footer>


</body>

</html>