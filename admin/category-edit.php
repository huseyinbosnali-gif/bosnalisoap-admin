<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: categories.php");
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

$sorgu->execute([$id]);

$kategori = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$kategori) {
    header("Location: categories.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| GÜNCELLEME
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");

    if ($name === "") {
        die("Kategori adı boş bırakılamaz.");
    }


    $image = $kategori["image"];


    /*
    |--------------------------------------------------------------------------
    | YENİ GÖRSEL
    |--------------------------------------------------------------------------
    */

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] === UPLOAD_ERR_OK
    ) {

        $klasor = "../uploads/categories/";

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
            die(
                "Sadece JPG, JPEG, PNG veya WEBP yükleyebilirsiniz."
            );
        }


        /*
        | Eski görseli sil
        */

        if (!empty($kategori["image"])) {

            $eskiDosya =
                $klasor .
                $kategori["image"];

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


    /*
    |--------------------------------------------------------------------------
    | VERİTABANINI GÜNCELLE
    |--------------------------------------------------------------------------
    */

    $guncelle = $pdo->prepare(
        "UPDATE categories
         SET name=?, image=?
         WHERE id=?"
    );


    $guncelle->execute([
        $name,
        $image,
        $id
    ]);


    header("Location: categories.php");
    exit;
}

?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


    <h1>
        Kategori Düzenle
    </h1>


    <div class="card">


        <form
            method="post"
            enctype="multipart/form-data"
        >


            <label>
                Kategori Adı
            </label>


            <input
                type="text"
                name="name"
                value="<?= htmlspecialchars($kategori["name"]) ?>"
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
                Mevcut Görsel
            </label>


            <div style="margin:10px 0 20px;">

                <?php if (!empty($kategori["image"])): ?>

                    <img
                        src="../uploads/categories/<?= htmlspecialchars($kategori["image"]) ?>"
                        style="
                            width:150px;
                            height:150px;
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
                    margin-bottom:25px;
                "
            >


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
                href="categories.php"
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