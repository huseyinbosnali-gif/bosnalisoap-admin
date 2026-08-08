<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


$id = $_GET["id"];


$sorgu = $pdo->prepare(
    "SELECT * FROM products WHERE id=?"
);

$sorgu->execute([$id]);

$urun = $sorgu->fetch(PDO::FETCH_ASSOC);



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $name = $_POST["name"];
$product_code = $_POST["product_code"];
$barcode = $_POST["barcode"];
$lot_number = $_POST["lot_number"];
$category = $_POST["category"];
$description = $_POST["description"];
$technical_info = $_POST["technical_info"];
$production_date = $_POST["production_date"];

    $image = $urun["image"];

$pdf_file = $urun["pdf_file"];
$msds_file = $urun["msds_file"];
$analysis_file = $urun["analysis_file"];

    if(isset($_FILES["image"]) && $_FILES["image"]["name"] != ""){


        $klasor="../uploads/";


        $image = time()."_".$_FILES["image"]["name"];


        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $klasor.$image
        );

    }
    // PDF güncelleme
if(isset($_FILES["pdf_file"]) && $_FILES["pdf_file"]["name"] != ""){

    $klasor = "../uploads/pdf/";

    if(!is_dir($klasor)){
        mkdir($klasor, 0777, true);
    }

    $pdf_file = time() . "_" . $_FILES["pdf_file"]["name"];

    move_uploaded_file(
        $_FILES["pdf_file"]["tmp_name"],
        $klasor . $pdf_file
    );

}


// MSDS güncelleme
if(isset($_FILES["msds_file"]) && $_FILES["msds_file"]["name"] != ""){

    $klasor = "../uploads/msds/";

    if(!is_dir($klasor)){
        mkdir($klasor, 0777, true);
    }

    $msds_file = time() . "_" . $_FILES["msds_file"]["name"];

    move_uploaded_file(
        $_FILES["msds_file"]["tmp_name"],
        $klasor . $msds_file
    );

}
// Analiz / COA güncelleme
if(isset($_FILES["analysis_file"]) && $_FILES["analysis_file"]["name"] != ""){

    $klasor = "../uploads/analysis/";

    if(!is_dir($klasor)){
        mkdir($klasor, 0777, true);
    }

    $analysis_file = time() . "_" . $_FILES["analysis_file"]["name"];

    move_uploaded_file(
        $_FILES["analysis_file"]["tmp_name"],
        $klasor . $analysis_file
    );
}

    $guncelle = $pdo->prepare(
    "UPDATE products SET
    name=?,
    product_code=?,
    barcode=?,
    lot_number=?,
    category=?,
    description=?,
    image=?,
    pdf_file=?,
msds_file=?,
analysis_file=?,
technical_info=?,
production_date=?
WHERE id=?"
);


    $guncelle->execute([
    $name,
    $product_code,
    $barcode,
    $lot_number,
    $category,
    $description,
    $image,
    $pdf_file,
$msds_file,
$analysis_file,
$technical_info,
$production_date,
$id
]);


    header("Location: products.php");
    exit;

}


?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


<h1>
✏ Ürün Düzenle
</h1>


<div class="card">


<form method="post" enctype="multipart/form-data">


<label>Ürün Adı</label>

<input
type="text"
name="name"
value="<?= htmlspecialchars($urun['name'] ?? '') ?>"
style="width:100%;padding:10px;"
>

<br><br>

<label>Ürün Kodu</label>

<input
type="text"
name="product_code"
value="<?= htmlspecialchars($urun['product_code'] ?? '') ?>"
style="width:100%;padding:10px;"
>

<br><br>

<label>Barkod No</label>

<input
type="text"
name="barcode"
value="<?= htmlspecialchars($urun['barcode'] ?? '') ?>"
style="width:100%;padding:10px;"
>

<br><br>

<label>Lot Numarası</label>

<input
type="text"
name="lot_number"
value="<?= htmlspecialchars($urun['lot_number'] ?? '') ?>"
style="width:100%;padding:10px;"
>

<br><br>


<label>
Kategori
</label>

<br>

<input
type="text"
name="category"
value="<?= htmlspecialchars($urun['category'] ?? '') ?>"
style="width:100%;padding:10px;"
>
<label>Açıklama</label>

<textarea
name="description"
style="width:100%;padding:10px;"
><?= htmlspecialchars($urun['description'] ?? '') ?></textarea>

<br><br>

<label>Teknik Bilgi</label>

<textarea
name="technical_info"
style="width:100%;padding:10px;"
><?= htmlspecialchars($urun['technical_info'] ?? '') ?></textarea>

<br><br>

<label>Üretim Tarihi</label>

<input
type="date"
name="production_date"
value="<?= htmlspecialchars($urun['production_date'] ?? '') ?>"
style="width:100%;padding:10px;"
>

<br><br>


<label>
Açıklama
</label>

<br>

<textarea 
name="description"
style="width:100%;height:120px;"
><?= $urun["description"] ?></textarea>


<br><br>


<label>
Yeni Resim
</label>

<br>

<input type="file" name="image">


<br><br>


<button
style="
background:#222;
color:white;
padding:12px 25px;
border:0;
"
>
<label>PDF Dosyası</label>

<input
type="file"
name="pdf_file"
accept=".pdf"
>
<?php if (!empty($urun['pdf_file'])): ?>

    <p>
        Mevcut PDF:
        <a href="../uploads/pdf/<?= htmlspecialchars($urun['pdf_file']) ?>" target="_blank">
            📄 PDF'yi Görüntüle
        </a>
    </p>

<?php endif; ?>
<br><br>

<label>MSDS Dosyası</label>

<input
type="file"
name="msds_file"
accept=".pdf"
>
<?php if (!empty($urun['msds_file'])): ?>

    <p>
        Mevcut MSDS:
        <a href="../uploads/msds/<?= htmlspecialchars($urun['msds_file']) ?>" target="_blank">
            📄 MSDS'yi Görüntüle
        </a>
    </p>

<?php endif; ?>
<br><br>
Analiz / COA Dosyası

<input
    type="file"
    name="analysis_file"
    accept=".pdf"
>

<?php if(!empty($urun["analysis_file"])): ?>

<p>
    Mevcut Analiz / COA:
    <a href="../uploads/analysis/<?= htmlspecialchars($urun["analysis_file"]) ?>" target="_blank">
        📄 Analiz / COA'yı Görüntüle
    </a>
</p>

<?php endif; ?>
Güncelle

</button>


</form>


</div>


</div>


<?php include "../includes/footer.php"; ?>