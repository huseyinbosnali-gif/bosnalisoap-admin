<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: catalogs.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| KATALOĞU GETİR
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT * FROM catalogs WHERE id=?"
);

$sorgu->execute([$id]);

$katalog = $sorgu->fetch(PDO::FETCH_ASSOC);


if (!$katalog) {
    header("Location: catalogs.php");
    exit;
}


$mesaj = "";


/*
|--------------------------------------------------------------------------
| GÜNCELLE
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $is_active = isset($_POST["is_active"]) ? 1 : 0;


    if ($title === "") {

        $mesaj = "Katalog başlığı boş bırakılamaz.";

    } else {

        $pdf_file = $katalog["pdf_file"];


        /*
        |--------------------------------------------------------------------------
        | YENİ PDF SEÇİLDİYSE
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES["pdf_file"]) &&
            $_FILES["pdf_file"]["error"] === UPLOAD_ERR_OK
        ) {

            $klasor = "../uploads/catalogs/";

            if (!is_dir($klasor)) {
                mkdir($klasor, 0777, true);
            }


            $uzanti = strtolower(
                pathinfo(
                    $_FILES["pdf_file"]["name"],
                    PATHINFO_EXTENSION
                )
            );


            if ($uzanti !== "pdf") {

                $mesaj = "Sadece PDF dosyası yükleyebilirsiniz.";

            } else {

                $yeni_pdf =
                    time() .
                    "_" .
                    uniqid() .
                    ".pdf";


                if (
                    move_uploaded_file(
                        $_FILES["pdf_file"]["tmp_name"],
                        $klasor . $yeni_pdf
                    )
                ) {

                    /*
                    | Eski PDF'yi sil
                    */

                    if (!empty($katalog["pdf_file"])) {

                        $eskiDosya =
                            $klasor .
                            $katalog["pdf_file"];

                        if (file_exists($eskiDosya)) {
                            unlink($eskiDosya);
                        }
                    }


                    $pdf_file = $yeni_pdf;

                } else {

                    $mesaj = "Yeni PDF dosyası yüklenemedi.";
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | AKTİF KATALOGSA DİĞERLERİNİ PASİF YAP
        |--------------------------------------------------------------------------
        */

        if ($mesaj === "" && $is_active === 1) {

            $pasif = $pdo->prepare(
                "UPDATE catalogs
                 SET is_active = 0
                 WHERE id != ?"
            );

            $pasif->execute([$id]);
        }


        /*
        |--------------------------------------------------------------------------
        | VERİTABANINI GÜNCELLE
        |--------------------------------------------------------------------------
        */

        if ($mesaj === "") {

            $guncelle = $pdo->prepare(
                "UPDATE catalogs
                 SET
                    title=?,
                    pdf_file=?,
                    is_active=?
                 WHERE id=?"
            );


            $guncelle->execute([
                $title,
                $pdf_file,
                $is_active,
                $id
            ]);


            header("Location: catalogs.php");
            exit;
        }
    }
}

?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


<h1>
PDF Katalog Düzenle
</h1>


<?php if ($mesaj): ?>

<div
    class="card"
    style="
        border-left:4px solid #c00;
        color:#900;
    "
>
    <?= htmlspecialchars($mesaj) ?>
</div>

<?php endif; ?>


<div class="card">


<form
    method="post"
    enctype="multipart/form-data"
>


<label>
Katalog Başlığı
</label>

<input
    type="text"
    name="title"
    value="<?= htmlspecialchars($katalog["title"]) ?>"
    required
    style="
        width:100%;
        padding:11px;
        margin:8px 0 20px;
        border:1px solid #ccc;
        border-radius:6px;
    "
>


<label>
Mevcut PDF
</label>

<div style="margin:10px 0 20px;">

    <a
        href="../uploads/catalogs/<?= htmlspecialchars($katalog["pdf_file"]) ?>"
        target="_blank"
    >
        📄 Mevcut Kataloğu Aç
    </a>

</div>


<label>
Yeni PDF Yükle
</label>

<p style="color:#777;font-size:13px;">
    PDF'yi değiştirmek istemiyorsanız bu alanı boş bırakın.
</p>

<input
    type="file"
    name="pdf_file"
    accept=".pdf,application/pdf"
    style="
        display:block;
        margin-top:8px;
        margin-bottom:20px;
    "
>


<label
    style="
        display:flex;
        align-items:center;
        gap:8px;
        margin-bottom:25px;
    "
>

<input
    type="checkbox"
    name="is_active"
    value="1"
    <?= (int)$katalog["is_active"] === 1 ? "checked" : "" ?>
>

Aktif katalog

</label>


<button
    type="submit"
    style="
        background:#222;
        color:white;
        padding:12px 25px;
        border:0;
        border-radius:6px;
        cursor:pointer;
    "
>
💾 Güncelle
</button>


&nbsp;


<a
    href="catalogs.php"
    style="
        display:inline-block;
        padding:12px 20px;
        background:#ddd;
        color:#222;
        text-decoration:none;
        border-radius:6px;
    "
>
Vazgeç
</a>


</form>


</div>


</div>


<?php include "../includes/footer.php"; ?>