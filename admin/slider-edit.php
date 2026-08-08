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
| SLIDER BİLGİSİNİ GETİR
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->prepare(
    "SELECT * FROM sliders WHERE id=?"
);

$sorgu->execute([$id]);

$slider = $sorgu->fetch(PDO::FETCH_ASSOC);


if (!$slider) {
    header("Location: sliders.php");
    exit;
}


$mesaj = "";


/*
|--------------------------------------------------------------------------
| GÜNCELLEME
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $button_text = trim($_POST["button_text"] ?? "");
    $button_link = trim($_POST["button_link"] ?? "");
    $sort_order = (int)($_POST["sort_order"] ?? 0);
    $is_active = isset($_POST["is_active"]) ? 1 : 0;


    if ($title === "") {

        $mesaj = "Slider başlığı boş bırakılamaz.";

    } else {

        $image = $slider["image"];


        /*
        |--------------------------------------------------------------------------
        | YENİ GÖRSEL YÜKLENİRSE
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] === UPLOAD_ERR_OK
        ) {

            $klasor = "../uploads/sliders/";

            if (!is_dir($klasor)) {
                mkdir($klasor, 0777, true);
            }


            $uzanti = strtolower(
                pathinfo(
                    $_FILES["image"]["name"],
                    PATHINFO_EXTENSION
                )
            );


            $izinli = [
                "jpg",
                "jpeg",
                "png",
                "webp"
            ];


            if (!in_array($uzanti, $izinli, true)) {

                $mesaj =
                    "Sadece JPG, JPEG, PNG veya WEBP görsel yükleyebilirsiniz.";

            } else {

                /*
                | Eski görseli sil
                */

                if (!empty($slider["image"])) {

                    $eskiDosya =
                        $klasor .
                        $slider["image"];


                    if (file_exists($eskiDosya)) {
                        unlink($eskiDosya);
                    }
                }


                /*
                | Yeni görsel adı
                */

                $image =
                    time() .
                    "_" .
                    uniqid() .
                    "." .
                    $uzanti;


                move_uploaded_file(
                    $_FILES["image"]["tmp_name"],
                    $klasor . $image
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VERİTABANINI GÜNCELLE
        |--------------------------------------------------------------------------
        */

        if ($mesaj === "") {

            $guncelle = $pdo->prepare(
                "UPDATE sliders
                 SET
                    title=?,
                    description=?,
                    button_text=?,
                    button_link=?,
                    image=?,
                    sort_order=?,
                    is_active=?
                 WHERE id=?"
            );


            $guncelle->execute([
                $title,
                $description,
                $button_text,
                $button_link,
                $image,
                $sort_order,
                $is_active,
                $id
            ]);


            header("Location: sliders.php");
            exit;
        }
    }
}

?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


<h1>
Slider Düzenle
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
Başlık
</label>

<input
    type="text"
    name="title"
    value="<?= htmlspecialchars($slider["title"]) ?>"
    required
    style="
        width:100%;
        padding:11px;
        margin:8px 0 15px;
        border:1px solid #ccc;
        border-radius:6px;
    "
>


<label>
Açıklama
</label>

<textarea
    name="description"
    rows="4"
    style="
        width:100%;
        padding:11px;
        margin:8px 0 15px;
        border:1px solid #ccc;
        border-radius:6px;
        resize:vertical;
    "
><?= htmlspecialchars($slider["description"] ?? "") ?></textarea>


<label>
Buton Yazısı
</label>

<input
    type="text"
    name="button_text"
    value="<?= htmlspecialchars($slider["button_text"] ?? "") ?>"
    style="
        width:100%;
        padding:11px;
        margin:8px 0 15px;
        border:1px solid #ccc;
        border-radius:6px;
    "
>


<label>
Buton Linki
</label>

<input
    type="text"
    name="button_link"
    value="<?= htmlspecialchars($slider["button_link"] ?? "") ?>"
    style="
        width:100%;
        padding:11px;
        margin:8px 0 15px;
        border:1px solid #ccc;
        border-radius:6px;
    "
>


<label>
Mevcut Görsel
</label>


<div style="margin:10px 0 20px;">

<?php if (!empty($slider["image"])): ?>

<img
    src="../uploads/sliders/<?= htmlspecialchars($slider["image"]) ?>"
    style="
        width:300px;
        max-width:100%;
        height:170px;
        object-fit:cover;
        border-radius:10px;
    "
>

<?php else: ?>

<p>
Mevcut görsel yok.
</p>

<?php endif; ?>

</div>


<label>
Yeni Görsel
</label>

<input
    type="file"
    name="image"
    accept=".jpg,.jpeg,.png,.webp"
    style="
        display:block;
        margin-top:8px;
        margin-bottom:20px;
    "
>


<label>
Sıralama
</label>

<input
    type="number"
    name="sort_order"
    value="<?= (int)$slider["sort_order"] ?>"
    style="
        width:120px;
        padding:10px;
        margin:8px 0 20px;
        border:1px solid #ccc;
        border-radius:6px;
        display:block;
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
    <?= (int)$slider["is_active"] === 1 ? "checked" : "" ?>
>

Aktif

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
    href="sliders.php"
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