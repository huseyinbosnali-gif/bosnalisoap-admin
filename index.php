</style><?php

require_once "config/database.php";


/*
|--------------------------------------------------------------------------
| KATEGORİLERİ VERİTABANINDAN AL
|--------------------------------------------------------------------------
*/

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
| AKTİF PDF KATALOĞU AL
|--------------------------------------------------------------------------
*/

$katalogSorgu = $pdo->query(
    "SELECT *
     FROM catalogs
     WHERE is_active = 1
     ORDER BY id DESC
     LIMIT 1"
);

$aktifKatalog = $katalogSorgu->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Bosnalı SOAX | Doğal Sabunlar</title>


<style>

*{
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f5f2ec;
    color:#30352d;
}

a{
    text-decoration:none;
    color:inherit;
}


/* ==========================================================
   ÜST MENÜ
========================================================== */

.header{
    background:#f5f2ec;
    border-bottom:1px solid rgba(60,75,45,.15);
}

.header-inner{
    max-width:1280px;
    margin:auto;
    min-height:110px;
    padding:15px 30px;

    display:flex;
    align-items:center;
    justify-content:space-between;

    gap:30px;
}


/* LOGO */

.logo{
    min-width:220px;
}

.logo-main{
    font-family:Georgia, serif;
    font-size:37px;
    font-weight:bold;
    color:#52643c;
    line-height:1;
}

.logo-sub{
    margin-top:5px;
    font-size:13px;
    letter-spacing:5px;
    color:#817765;
}


/* MENÜ */

.nav{
    display:flex;
    align-items:center;
    justify-content:flex-end;
    gap:34px;
    flex-wrap:wrap;
}

.nav a{
    font-size:15px;
    font-weight:bold;
    color:#4f5943;
    transition:.2s;
}

.nav a:hover{
    color:#829360;
}


/* ==========================================================
   HERO / BÜYÜK ÜST ALAN
========================================================== */

.hero{
    min-height:460px;

    background:
        radial-gradient(
            circle at 78% 45%,
            rgba(126,146,91,.30),
            transparent 30%
        ),
        linear-gradient(
            120deg,
            #e4ddcc,
            #f7f3e9 56%,
            #d9d7bd
        );

    display:flex;
    align-items:center;
}

.hero-inner{
    width:100%;
    max-width:1280px;

    margin:auto;
    padding:65px 30px;

    display:grid;
    grid-template-columns:1.1fr .9fr;

    gap:60px;

    align-items:center;
}


.hero-small{
    color:#78855e;
    letter-spacing:3px;
    text-transform:uppercase;
    font-size:13px;
    font-weight:bold;
}


.hero h1{
    font-family:Georgia, serif;

    font-size:54px;

    line-height:1.08;

    margin:15px 0 20px;

    color:#4c5f37;
}


.hero p{
    max-width:570px;

    font-size:18px;

    line-height:1.8;

    color:#656454;
}


.hero-button{
    display:inline-block;

    margin-top:15px;

    background:#849268;

    color:white;

    padding:15px 28px;

    border-radius:30px;

    font-size:14px;

    font-weight:bold;

    transition:.2s;
}


.hero-button:hover{
    background:#43532f;
}


/* HERO DEKOR */

.hero-decoration{
    height:310px;

    position:relative;

    display:flex;
    align-items:center;
    justify-content:center;
}


.soap-shape{
    position:absolute;

    width:260px;
    height:160px;

    border-radius:35px;

    background:
        linear-gradient(
            145deg,
            #faf7ec,
            #d8d0b8
        );

    box-shadow:
        0 30px 45px rgba(70,75,50,.18);

    transform:rotate(-8deg);
}


.soap-shape::after{
    content:"SOAX";

    position:absolute;

    left:50%;
    top:50%;

    transform:translate(-50%,-50%);

    font-family:Georgia, serif;

    font-size:35px;

    color:#849064;

    letter-spacing:3px;
}


.leaf{
    position:absolute;

    width:180px;
    height:70px;

    border-radius:100% 0 100% 0;

    background:#81915e;

    opacity:.65;
}


.leaf-1{
    right:35px;
    top:25px;

    transform:rotate(28deg);
}


.leaf-2{
    left:45px;
    bottom:25px;

    transform:rotate(210deg);

    background:#68794b;
}


/* ==========================================================
   ÜRÜNLER / KATEGORİLER
========================================================== */

.categories-section{
    max-width:1100px;
    margin:auto;
    padding:25px 30px 75px;
}

.section-title{
    text-align:center;
    margin-bottom:28px;

    display:flex;
    align-items:center;
    justify-content:center;
    gap:1.5cm;
}
.section-title::before,
.section-title::after{
    content:"";
    display:block;
    width:3cm;
    height:2px;
    background:#8d9d6a;
    flex-shrink:0;
}
.section-title span{
    display:block;
    color:#7f8c62;
    font-size:12px;
    font-weight:600;
    letter-spacing:3px;
    margin-bottom:8px;
}

.section-title h2{
    margin:0;
    font-family:Georgia, serif;
    font-size:24px;
    font-weight:400;
    color:#4f5f3d;
}




/* 3 SÜTUN */

.category-grid{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:28px;
}


/* KATEGORİ KARTI */

.category-card{
    display:flex;
    flex-direction:column;

    position:relative;
    overflow:hidden;

    min-height:280px;

    background:transparent;
    border:0;

    text-decoration:none;
    color:#4d573f;

    transition:transform .25s ease;
}

.category-card:hover{
    transform:translateY(-4px);
}


/* KATEGORİ GÖRSELİ */

.category-image{
    order:2;

    width:310px;
    height:280px;
    max-width:100%;

    margin:0 auto;

    overflow:hidden;
    background:transparent;

    display:flex;
    align-items:center;
    justify-content:center;
}

.category-image img{
    width:100%;
    height:100%;

    object-fit:contain;

    transition:transform .35s ease;
}

.category-card:hover .category-image img{
    transform:scale(1.025);
}


/* KATEGORİ ADI */

.category-name{
    order:1;

    min-height:58px;
    padding:0 8px 12px;

    background:transparent;
    color:#4e5c3d;

    font-family:Georgia, serif;
    font-size:20px;
    font-weight:400;

    text-align:center;
    line-height:1.25;

    display:flex;
    align-items:center;
    justify-content:center;
}


/* RESİM YOKSA */

.no-image{
    height:100%;
    width:100%;

    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;

    gap:10px;

    color:#7a806c;
    background:transparent;
}

.no-image-icon{
    font-size:46px;
}


/* KATEGORİ YOKSA */

.empty-categories{
    grid-column:1 / -1;

    padding:50px;

    text-align:center;

    background:#ebe6d8;

    border:1px dashed #8e9876;

    color:#686b5a;
}


/* TABLET */

@media (max-width:900px){

    .category-grid{
        grid-template-columns:repeat(2, 1fr);
    }

    .category-image{
        height:235px;
    }
}


/* TELEFON */

@media (max-width:600px){

    .categories-section{
        padding:45px 20px 60px;
    }

    .category-grid{
        grid-template-columns:1fr;
        gap:24px;
    }

    .category-image{
        height:250px;
    }

    .category-name{
        font-size:19px;
    }
}


/* ==========================================================
   ALT HIZLI MENÜ
========================================================== */

quick-links{
    display:block;
}

.quick-links{
    max-width:1142px;
    margin:20px auto;
    padding:10px 21px;

    box-sizing:border-box;
    background:#849268;
    border-radius:28px;
}


.quick-inner{
    max-width:1100px;
    margin:auto;

    display:grid;
    grid-template-columns:repeat(3, 1fr);

    background:transparent;

    border:1px solid rgba(255,255,255,.28);
    border-radius:28px;
    overflow:hidden;
}


.quick-item{
    min-height:32px;

    display:flex;

    align-items:center;

    justify-content:center;

    padding:8px 20px;

    color:white;

    text-align:center;

    font-weight:bold;

    border-right:1px solid rgba(255,255,255,.25);

    transition:.2s;
}


.quick-item:last-child{
    border-right:0;
}


.quick-item:hover{
    background:rgba(255,255,255,.10);
}


/* ==========================================================
   FOOTER
========================================================== */

.footer{
    background:#849268;

    color:#e8eadf;

    text-align:center;

    padding:25px 20px;

    font-size:14px;
}


/* ==========================================================
   TABLET
========================================================== */

@media(max-width:950px){

    .header-inner{
        flex-direction:column;
        justify-content:center;
    }


    .logo{
        min-width:0;
        text-align:center;
    }


    .nav{
        justify-content:center;
        gap:20px;
    }


    .hero-inner{
        grid-template-columns:1fr;
    }


    .hero-decoration{
        display:none;
    }


    .category-grid{
        grid-template-columns:repeat(2, 1fr);
    }

}


/* ==========================================================
   TELEFON
========================================================== */

@media(max-width:600px){

    .header-inner{
        padding:20px;
    }


    .nav{
        gap:15px;
    }


    .nav a{
        font-size:13px;
    }


    .hero{
        min-height:auto;
    }


    .hero-inner{
        padding:50px 22px;
    }


    .hero h1{
        font-size:40px;
    }


    .hero p{
        font-size:16px;
    }


    .categories-section{
        padding:50px 20px;
    }


    .section-title h2{
        font-size:35px;
    }


    .category-grid{
        grid-template-columns:1fr;
    }


    .quick-inner{
        grid-template-columns:1fr;

        border-radius:25px;
    }


    .quick-item{
        border-right:0;

        border-bottom:
            1px solid rgba(255,255,255,.25);
    }


    .quick-item:last-child{
        border-bottom:0;
    }

}
/* ==========================================================
   ÇOKLU SLIDER
========================================================== */

.slider-wrapper{
    position:relative;
    width:100%;
    max-width:1220px;
    margin:0 auto;
    overflow:visible;
}

.slider-slide{
    display:none;
    animation:sliderFade .7s ease;
}

.slider-slide.active{
    display:flex;
}

@keyframes sliderFade{

    from{
        opacity:.25;
    }

    to{
        opacity:1;
    }

}

.slider-arrow{
    position:absolute;
    top:50%;
    transform:translateY(-50%);

    z-index:20;

    width:48px;
    height:48px;

    border:0;
    border-radius:50%;

    background:rgba(63,77,49,.75);
    color:white;

    font-size:25px;

    cursor:pointer;

    transition:.2s;
}

.slider-arrow:hover{
    background:#3f4d31;
}

.slider-prev{
    left:20px;
}

.slider-next{
    right:20px;
}

.slider-dots{
    position:absolute;

    left:50%;
    bottom:20px;

    transform:translateX(-50%);

    display:flex;
    gap:9px;

    z-index:20;
}

.slider-dot{
    width:11px;
    height:11px;

    padding:0;

    border:2px solid white;

    border-radius:50%;

    background:transparent;

    cursor:pointer;
}

.slider-dot.active{
    background:white;
}

@media(max-width:600px){

    .slider-arrow{
        width:40px;
        height:40px;
        font-size:20px;
    }

    .slider-prev{
        left:8px;
    }

    .slider-next{
        right:8px;
    }

}
/* =========================
   YENİ ÜST HEADER TASARIMI
   ========================= */

body{
    background:#F5F2EC;
}

.new-header{
    max-width:1180px;
    margin:0 auto;
    padding:22px 30px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:40px;
    background:#F5F2EC;
}

.brand-area{
    display:flex;
    align-items:center;
    text-decoration:none;
    position:relative;
    min-width:470px;
}

.brand-center{
    display:flex;
    align-items:center;
    gap:10px;
    position:relative;
    z-index:2;
}

.brand-bos{
    width:76px;
    height:auto;
}

.brand-name{
    width:170px;
    height:auto;
}

.brand-leaf-left{
    width:95px;
    left:-58px;
    top:-10px;
}

.brand-leaf-right{
    width:92px;
    right:-35px;
    top:-6px;
}

.new-nav{
    display:flex;
    align-items:center;
    gap:26px;
    margin-left:auto;
    padding-right:8px;
}

.new-nav a{
    color:#59654a;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    letter-spacing:.2px;
    white-space:nowrap;
}

.new-nav a:hover{
    color:#2f3b28;
}

@media (max-width:900px){

    .new-header{
        flex-direction:column;
        padding:16px 18px;
    }

    .brand-area{
        min-width:0;
        justify-content:center;
    }

    .new-nav{
        margin-left:0;
        flex-wrap:wrap;
        justify-content:center;
        gap:16px 20px;
    }

        .brand-leaf-left{
        left:-60px;
    }

    .brand-leaf-right{
        right:-60px;
    }

} /* @media burada bitiyor */


/* SOL YAPRAK */
.brand-leaf-left{
    width:42px;
    left:-28px;
    top:8px;
}

/* KATALOG SONRASI SAĞ YAPRAK */
.menu-leaf-right{
    width:50px;
    height:auto;
    margin-left:8px;
    display:block;
    position:static;
    flex-shrink:0;
} 
 /* ÜST BÖLÜM İNCE HİZALAMA */

.brand-area{
    transform:translateX(22px);
}

.new-nav{
    transform:translateX(-12px);
}
/* HEADER SON HİZALAMA */

.brand-center{
    margin-left:28px;
}

/* Masaüstünde header sıkışmasın */
.new-header{
    width:100%;
    box-sizing:border-box;
}

.brand-area{
    flex-shrink:0;
}

/* Orta ekran */
@media (max-width:1100px){

    .new-header{
        flex-wrap:wrap;
        justify-content:center;
    }

    .brand-area{
        width:100%;
        justify-content:center;
        transform:none;
    }

    .new-nav{
        width:100%;
        justify-content:center;
        margin-top:10px;
        transform:none;
    }

    .brand-leaf-left{
        position:static;
        margin-right:8px;
    }

    .brand-center{
        margin-left:0;
    }
}

/* Telefon */
@media (max-width:650px){

    .brand-bos{
        width:58px;
    }

    .brand-name{
        width:135px;
    }

    .brand-leaf-left{
        width:34px;
    }

    .menu-leaf-right{
        width:38px;
    }

    .new-nav{
        gap:12px 16px;
    }

    .new-nav a{
        font-size:12px;
    }
}
/* RESPONSIVE HEADER SON DÜZELTME */

@media (max-width:1100px){

    .new-header{
        flex-direction:column;
        align-items:center;
        gap:14px;
    }

    .brand-area{
        width:auto;
        min-width:0;
        justify-content:center;
        transform:none;
    }

    .new-nav{
        width:100%;
        justify-content:center;
        flex-wrap:wrap;
        transform:none;
        margin:0;
    }
}

@media (max-width:700px){

    .new-nav{
        gap:10px 14px;
    }

    .menu-leaf-right{
        width:32px;
        margin-left:2px;
    }
}
/* =========================
   SLIDER SON TASARIM
   ========================= */

.slider-slide{
    width:calc(100% - 125px);
    max-width:1100px;
    min-height:390px;

    margin:18px auto 0;

    border-radius:0;
    overflow:hidden;

    background-position:center !important;
    background-size:cover !important;
    background-repeat:no-repeat !important;
}

.slider-slide .hero-inner{
    width:100%;
    max-width:1100px;
    min-height:390px;
}

/* Tablet */
@media (max-width:900px){

    .slider-slide{
        width:calc(100% - 30px);
        min-height:360px;
        margin-top:15px;
    }

    .slider-slide .hero-inner{
        min-height:360px;
        padding:40px 30px;
    }
}

/* Telefon */
@media (max-width:600px){

    .slider-slide{
        width:calc(100% - 20px);
        min-height:300px;
        margin-top:10px;
    }

    .slider-slide .hero-inner{
        min-height:300px;
        padding:30px 22px;
    }

}
/* =========================
   SLIDER YAZI TASARIMI
   ========================= */

.slider-slide .hero-inner{
    display:flex;
    align-items:center;
}

.slider-slide .hero-inner > div{
    max-width:500px;
    margin-left:20px;
}

.slider-slide .hero-small{
    font-size:12px;
    letter-spacing:2.5px;
    margin-bottom:12px;
}

.slider-slide h1{
    font-size:31px;
    font-weight:400;
    line-height:1.08;
    margin:0 0 16px;
}

.slider-slide p{
    font-size:16px;
    line-height:1.65;
    max-width:470px;
    margin:0 0 18px;
}

.slider-slide .hero-button{
    margin-top:4px;
}

/* TABLET */
@media (max-width:900px){

    .slider-slide .hero-inner > div{
        max-width:430px;
        margin-left:0;
    }

    .slider-slide h1{
        font-size:38px;
    }

    .slider-slide p{
        font-size:15px;
    }
}

/* TELEFON */
@media (max-width:600px){

    .slider-slide .hero-inner{
        align-items:flex-end;
    }

    .slider-slide .hero-inner > div{
        max-width:100%;
    }

    .slider-slide h1{
        font-size:30px;
    }

    .slider-slide p{
        font-size:14px;
        line-height:1.5;
    }
}
/* SLIDER OKLARI + NOKTALAR */

.slider-arrow{
    width:34px;
    height:34px;

    border:1px solid rgba(255,255,255,.65);
    border-radius:50%;

    background:rgba(70,80,55,.35);
    color:#fff;

    font-size:17px;
    line-height:34px;

    transition:.2s ease;
}

.slider-arrow:hover{
    background:rgba(70,80,55,.65);
}

.slider-prev{
    left:18px;
}

.slider-next{
    right:18px;
}

.slider-dots{
    bottom:13px;
    gap:7px;
}

.slider-dot{
    width:8px;
    height:8px;
    border:1px solid #fff;
}

.slider-dot.active{
    background:#fff;
}

/* TELEFON */
@media (max-width:600px){
/* DAR EKRANDA OKLAR GİZLENSİN */
@media (max-width:900px){
    .slider-arrow{
        display:none;
    }
}
    .slider-arrow{
        width:30px;
        height:30px;
        font-size:15px;
        line-height:30px;
    }

    .slider-prev{
        left:8px;
    }

    .slider-next{
        right:8px;
    }
}
/* SLIDER OKLARINI KENARDA TUT */

.slider-wrapper{
    position:relative;
    width:100%;
    max-width:1220px;
    margin:0 auto;
    overflow:visible;
}

.slider-prev{
    left:18px;
}

.slider-next{
    right:18px;
}

@media (max-width:900px){

    .slider-prev{
        left:8px;
    }

    .slider-next{
        right:8px;
    }
}

@media (max-width:600px){

    .slider-arrow{
        width:28px;
        height:28px;
        font-size:14px;
    }

    .slider-prev{
        left:5px;
    }

    .slider-next{
        right:5px;
    }
}

</style>

</head>


<body>


<!-- ========================================================
     HEADER
========================================================= -->

<header class="header">

    <div class="header-inner new-header">

    <a href="index.php" class="brand-area">

        <img
            src="assets/images/header/yaprak-sol.png"
            alt=""
            class="brand-leaf brand-leaf-left"
        >

        <div class="brand-center">

            <img
                src="assets/images/header/bos-logo.png"
                alt="BOS"
                class="brand-bos"
            >

            <img
                src="assets/images/header/bosnali-sabun.png"
                alt="Bosnalı Sabun"
                class="brand-name"
            >

        </div>

        

    </a>


    <nav class="nav new-nav">

        <a href="index.php">
            ANA SAYFA
        </a>

        <a href="#urunler">
            ÜRÜNLER
        </a>

        <a href="#">
            HAKKIMIZDA
        </a>

        <a href="#">
            İLETİŞİM
        </a>

        <?php if ($aktifKatalog): ?>

            <a
                href="uploads/catalogs/<?= htmlspecialchars($aktifKatalog["pdf_file"]) ?>"
                target="_blank"
            >
                KATALOG
            </a>

        <?php endif; ?>

        <img
    src="assets/images/header/yaprak-sag.png"
    alt=""
    class="menu-leaf-right"
>
    </nav>

</div>

</header>



<!-- =========================================================
     HERO
========================================================= -->

<?php if (!empty($sliderlar)): ?>

<div class="slider-wrapper">

<?php foreach ($sliderlar as $index => $slider): ?>

    <section
        class="hero slider-slide <?= $index === 0 ? 'active' : '' ?>"

        <?php if (!empty($slider["image"])): ?>

            style="
                background:
                    linear-gradient(
                        90deg,
                        rgba(244,239,228,.96) 0%,
                        rgba(244,239,228,.82) 40%,
                        rgba(244,239,228,.10) 75%
                    ),
                    url('uploads/sliders/<?= htmlspecialchars($slider["image"]) ?>')
                    center / cover no-repeat;
            "

        <?php endif; ?>
    >

            <div class="hero-inner">

                <div>

                    <div class="hero-small">
                        BOSNALI SOAX
                    </div>

                    <h1>
                        <?= htmlspecialchars($slider["title"]) ?>
                    </h1>

                    <?php if (!empty($slider["description"])): ?>

                        <p>
                            <?= nl2br(
                                htmlspecialchars(
                                    $slider["description"]
                                )
                            ) ?>
                        </p>

                    <?php endif; ?>

                    <?php if (!empty($slider["button_text"])): ?>

                        <a
                            href="<?= htmlspecialchars(
                                $slider["button_link"] ?: "#urunler"
                            ) ?>"
                            class="hero-button"
                        >
                            <?= htmlspecialchars($slider["button_text"]) ?>
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </section>

    <?php endforeach; ?>


    <?php if (count($sliderlar) > 1): ?>

        <button
            type="button"
            class="slider-arrow slider-prev"
            aria-label="Önceki slider"
        >
            &#10094;
        </button>

        <button
            type="button"
            class="slider-arrow slider-next"
            aria-label="Sonraki slider"
        >
            &#10095;
        </button>


        <div class="slider-dots">

            <?php foreach ($sliderlar as $index => $slider): ?>

                <button
                    type="button"
                    class="slider-dot <?= $index === 0 ? 'active' : '' ?>"
                    data-slide="<?= $index ?>"
                    aria-label="Slider <?= $index + 1 ?>"
                ></button>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>


<?php else: ?>


<section class="hero">

    <div class="hero-inner">

        <div>

            <div class="hero-small">
                BOSNALI SOAX
            </div>

            <h1>
                Doğadan Gelen Temizlik
            </h1>

            <p>
                Geleneksel sabun üretim tecrübemizi,
                modern üretim anlayışıyla buluşturuyoruz.
            </p>

            <a
                href="#urunler"
                class="hero-button"
            >
                Ürünleri Keşfet
            </a>

        </div>

    </div>

</section>


<?php endif; ?>



<!-- ========================================================
     KATEGORİLER
========================================================= -->

<section
    class="categories-section"
    id="urunler"
>


    <div class="section-title">

        

        <h2>
            ÜRÜNLER
        </h2>

    </div>



    <div class="category-grid">


        <?php if (!empty($kategoriler)): ?>


            <?php foreach ($kategoriler as $kategori): ?>


                <a
                    class="category-card"
                    href="products.php?category_id=<?= $kategori['id'] ?>"
                    title="<?= htmlspecialchars($kategori["name"]) ?>"
                >


                    <div class="category-name">

                        <?= htmlspecialchars($kategori["name"]) ?>

                    </div>


                    <div class="category-image">


                        <?php if (!empty($kategori["image"])): ?>


                            <img
                                src="uploads/categories/<?= htmlspecialchars($kategori["image"]) ?>"
                                alt="<?= htmlspecialchars($kategori["name"]) ?>"
                            >


                        <?php else: ?>


                            <div class="no-image">

                                <div class="no-image-icon">
                                    🧼
                                </div>

                                <div>
                                    Kategori Görseli
                                </div>

                            </div>


                        <?php endif; ?>


                    </div>


                </a>


            <?php endforeach; ?>


        <?php else: ?>


            <div class="empty-categories">

                Henüz kategori eklenmemiş.

                <br><br>

                Yönetim panelinden kategori eklediğinizde
                burada otomatik olarak görünecektir.

            </div>


        <?php endif; ?>


    </div>


</section>



<!-- ========================================================
     ALT YEŞİL MENÜ
========================================================= -->

<section class="quick-links">


    <div class="quick-inner">

    <a
        href="#"
        class="quick-item"
    >
        Sabun Kalıpları
    </a>

    <a
        href="#"
        class="quick-item"
    >
        Sabun Nasıl Kullanılır?
    </a>

    <?php if ($aktifKatalog): ?>

    <a
        href="uploads/catalogs/<?= htmlspecialchars($aktifKatalog['pdf_file']) ?>"
        class="quick-item"
        download
    >
        Evde Sabun Yapımı
    </a>

<?php endif; ?>

</div>


</section>



<!-- ========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    © <?= date("Y") ?>
    Bosnalı Sabun Yağ ve Kimyevi Ürünler Ltd. Şti.

</footer>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const slides =
        document.querySelectorAll(".slider-slide");

    const dots =
        document.querySelectorAll(".slider-dot");

    const prev =
        document.querySelector(".slider-prev");

    const next =
        document.querySelector(".slider-next");


    if (slides.length <= 1) {
        return;
    }


    let current = 0;
    let timer;


    function showSlide(index) {

        if (index < 0) {
            index = slides.length - 1;
        }

        if (index >= slides.length) {
            index = 0;
        }


        slides.forEach(function(slide) {
            slide.classList.remove("active");
        });


        dots.forEach(function(dot) {
            dot.classList.remove("active");
        });


        slides[index].classList.add("active");


        if (dots[index]) {
            dots[index].classList.add("active");
        }


        current = index;
    }


    function nextSlide() {
        showSlide(current + 1);
    }


    function startTimer() {

        clearInterval(timer);

        timer = setInterval(
            nextSlide,
            6000
        );
    }


    if (next) {

        next.addEventListener(
            "click",
            function () {

                showSlide(current + 1);

                startTimer();

            }
        );

    }


    if (prev) {

        prev.addEventListener(
            "click",
            function () {

                showSlide(current - 1);

                startTimer();

            }
        );

    }


    dots.forEach(function(dot) {

        dot.addEventListener(
            "click",
            function () {

                showSlide(
                    parseInt(
                        this.dataset.slide
                    )
                );

                startTimer();

            }
        );

    });


    startTimer();

});

</script>
</body>

</html>