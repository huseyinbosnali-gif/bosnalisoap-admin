<?php

require_once "config/database.php";

$category_id = $_GET["category_id"] ?? null;

if (!$category_id) {
    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| KATEGORİYİ GETİR
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT * FROM categories WHERE id=?"
);

$sorgu->execute([$category_id]);

$kategori = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$kategori) {
    header("Location: index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| O KATEGORİYE AİT ÜRÜNLERİ GETİR
|--------------------------------------------------------------------------
|
| products tablosunda category alanı metin olarak tutulduğu için
| kategori adına göre eşleştiriyoruz.
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT *
     FROM products
     WHERE category=?
     ORDER BY id DESC"
);

$sorgu->execute([$kategori["name"]]);

$urunler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
<?= htmlspecialchars($kategori["name"]) ?> | Bosnalı SOAX
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


/* SAYFA BAŞLIĞI */

.page-title{
    background:#596b3f;
    color:white;
    padding:45px 20px;
    text-align:center;
}

.page-title h1{
    margin:0;
    font-family:Georgia, serif;
    font-size:42px;
}

.page-title p{
    margin:10px 0 0;
    opacity:.85;
}


/* ÜRÜNLER */

.products-section{
    max-width:1280px;
    margin:auto;
    padding:60px 30px 80px;
}

.product-grid{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:30px;
}

.product-card{
    background:#fffdf7;
    border:1px solid rgba(89,107,63,.20);
    overflow:hidden;
    transition:.25s;
}

.product-card:hover{
    transform:translateY(-5px);
    box-shadow:0 15px 30px rgba(60,70,45,.12);
}

.product-image{
    height:300px;
    background:#ebe6d8;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.product-image img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.no-image{
    color:#777;
    text-align:center;
    font-size:15px;
}

.product-info{
    padding:22px;
}

.product-info h2{
    margin:0 0 10px;
    font-family:Georgia, serif;
    font-size:24px;
    color:#52643c;
}

.product-code{
    font-size:13px;
    color:#8a8778;
    margin-bottom:12px;
}

.product-description{
    font-size:15px;
    line-height:1.6;
    color:#666457;
}

.detail-button{
    display:inline-block;
    margin-top:15px;
    padding:11px 18px;
    background:#596b3f;
    color:white;
    border-radius:25px;
    font-size:13px;
    font-weight:bold;
}


/* BOŞ DURUM */

.empty{
    grid-column:1 / -1;
    background:#ebe6d8;
    padding:55px 25px;
    text-align:center;
    border:1px dashed #8e9876;
}


/* GERİ */

.back-area{
    margin-bottom:25px;
}

.back-link{
    color:#596b3f;
    font-weight:bold;
}


/* MOBİL */

@media(max-width:900px){

    .header-inner{
        flex-direction:column;
        gap:20px;
    }

    .product-grid{
        grid-template-columns:repeat(2,1fr);
    }

}

@media(max-width:600px){

    .product-grid{
        grid-template-columns:1fr;
    }

    .page-title h1{
        font-size:34px;
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


<section class="page-title">

    <h1>
        <?= htmlspecialchars($kategori["name"]) ?>
    </h1>

    <p>
        Bu kategorideki ürünleri inceleyebilirsiniz.
    </p>

</section>


<section class="products-section">

    <div class="back-area">

        <a
            href="index.php#urunler"
            class="back-link"
        >
            ← Kategorilere Dön
        </a>

    </div>


    <div class="product-grid">


        <?php if (!empty($urunler)): ?>


            <?php foreach ($urunler as $urun): ?>


                <div class="product-card">


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


                    <div class="product-info">


                        <h2>
                            <?= htmlspecialchars($urun["name"]) ?>
                        </h2>


                        <?php if (!empty($urun["product_code"])): ?>

                            <div class="product-code">
                                Ürün Kodu:
                                <?= htmlspecialchars($urun["product_code"]) ?>
                            </div>

                        <?php endif; ?>


                        <?php if (!empty($urun["description"])): ?>

                            <div class="product-description">

                                <?= nl2br(
                                    htmlspecialchars(
                                        mb_strimwidth(
                                            $urun["description"],
                                            0,
                                            160,
                                            "..."
                                        )
                                    )
                                ) ?>

                            </div>

                        <?php endif; ?>


                        <a
                            href="product.php?id=<?= $urun["id"] ?>"
                            class="detail-button"
                        >
                            Ürünü İncele
                        </a>


                    </div>


                </div>


            <?php endforeach; ?>


        <?php else: ?>


            <div class="empty">

                Bu kategoriye henüz ürün eklenmemiş.

            </div>


        <?php endif; ?>


    </div>

</section>


</body>

</html>