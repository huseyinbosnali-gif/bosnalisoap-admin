<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"] ?? "";

    $product_code = $_POST["product_code"] ?? "";
    $barcode = $_POST["barcode"] ?? "";
    $lot_number = $_POST["lot_number"] ?? "";
    $technical_info = $_POST["technical_info"] ?? "";
    $production_date = $_POST["production_date"] ?? "";

    // Ürün resmi
$resim = "";

if(isset($_FILES["image"]) && $_FILES["image"]["name"] != ""){

    $klasor = "../uploads/";

    if(!is_dir($klasor)){
        mkdir($klasor);
    }

    $resim = time() . "_" . $_FILES["image"]["name"];

    move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $klasor . $resim
    );
}


// PDF dosyası
$pdf_file = "";

if(isset($_FILES["pdf_file"]) && $_FILES["pdf_file"]["name"] != ""){

    $klasor = "../uploads/pdf/";

    if(!is_dir($klasor)){
        mkdir($klasor,0777,true);
    }

    $pdf_file = time() . "_" . $_FILES["pdf_file"]["name"];

    move_uploaded_file(
        $_FILES["pdf_file"]["tmp_name"],
        $klasor . $pdf_file
    );
}


// MSDS dosyası
$msds_file = "";

if(isset($_FILES["msds_file"]) && $_FILES["msds_file"]["name"] != ""){

    $klasor = "../uploads/msds/";

    if(!is_dir($klasor)){
        mkdir($klasor,0777,true);
    }

    $msds_file = time() . "_" . $_FILES["msds_file"]["name"];

    move_uploaded_file(
        $_FILES["msds_file"]["tmp_name"],
        $klasor . $msds_file
    );
}
// Analiz / COA dosyası
$analysis_file = "";

if(isset($_FILES["analysis_file"]) && $_FILES["analysis_file"]["name"] != ""){

    $klasor = "../uploads/analysis/";

    if(!is_dir($klasor)){
        mkdir($klasor,0777,true);
    }

    $analysis_file = time() . "_" . $_FILES["analysis_file"]["name"];

    move_uploaded_file(
        $_FILES["analysis_file"]["tmp_name"],
        $klasor . $analysis_file
    );
}


   $ekle = $pdo->prepare(
    "INSERT INTO products 
    (
        name,
        product_code,
        barcode,
        lot_number,
        category,
        description,
        image,
        pdf_file,
        msds_file,
        analysis_file,
        technical_info,
        production_date
    )
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"
);


    $ekle->execute([
    $name,
    $product_code,
    $barcode,
    $lot_number,
    $category,
    $description,
    $resim,
$pdf_file,
$msds_file,
$analysis_file,
$technical_info,
$production_date
]);


    header("Location: products.php");
    exit;

}


?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


<h1>
🧼 Yeni Ürün Ekle
</h1>


<div class="card">


<form method="post" enctype="multipart/form-data">


<label>
Ürün Adı
</label>

<br>

<label>Ürün Adı</label>
<br>
<input
    type="text"
    name="name"
    required
    style="width:100%;padding:10px;"
>

<br><br>

<label>Ürün Kodu</label>
<br>
<input
    type="text"
    name="product_code"
    style="width:100%;padding:10px;"
>

<br><br>

<label>Barkod</label>
<br>
<input
    type="text"
    name="barcode"
    style="width:100%;padding:10px;"
>

<br><br>

<label>Lot / Parti No</label>
<br>
<input
    type="text"
    name="lot_number"
    style="width:100%;padding:10px;"
>

<br><br>

<label>Kategori</label>
<br>
<input
    type="text"
    name="category"
    style="width:100%;padding:10px;"
>

<br><br>

<label>Açıklama</label>
<br>
<textarea
    name="description"
    style="width:100%;height:100px;padding:10px;"
></textarea>

<br><br>

<label>Teknik Bilgiler</label>
<br>
<textarea
    name="technical_info"
    style="width:100%;height:150px;padding:10px;"
></textarea>

<br><br>

<label>Üretim Tarihi</label>
<br>
<input
    type="date"
    name="production_date"
    style="width:100%;padding:10px;"
>

<br><br>

<label>Ürün Resmi</label>
<br>
<input
    type="file"
    name="image"
>

<br><br>

<label>PDF Katalog</label>
<br>
<input
    type="file"
    name="pdf_file"
    accept=".pdf"
>

<br><br>

<label>MSDS Dosyası</label>
<br>
<input
    type="file"
    name="msds_file"
    accept=".pdf"
>
Analiz / COA Dosyası

<input
    type="file"
    name="analysis_file"
    accept=".pdf"
>
<br><br>

<button type="submit">
    Kaydet
</button>

</button>


</form>


</div>


</div>


<?php include "../includes/footer.php"; ?>