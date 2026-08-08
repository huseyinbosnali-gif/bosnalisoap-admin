<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$mesaj = "";


/*
|--------------------------------------------------------------------------
| KATEGORİ EKLEME
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");

    if ($name === "") {

        $mesaj = "Kategori adı boş bırakılamaz.";

    } else {

        $image = null;


        /*
        |--------------------------------------------------------------------------
        | KATEGORİ GÖRSELİ
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


            if (in_array($uzanti, $izinli, true)) {

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

            } else {

                $mesaj =
                    "Sadece JPG, JPEG, PNG veya WEBP görsel yükleyebilirsiniz.";

            }
        }


        /*
        |--------------------------------------------------------------------------
        | VERİTABANINA EKLE
        |--------------------------------------------------------------------------
        */

        if ($mesaj === "") {

            $ekle = $pdo->prepare(
                "INSERT INTO categories
                (name, image)
                VALUES (?, ?)"
            );


            $ekle->execute([
                $name,
                $image
            ]);


            header("Location: categories.php");
            exit;
        }
    }
}


/*
|--------------------------------------------------------------------------
| KATEGORİLERİ GETİR
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->query(
    "SELECT *
     FROM categories
     ORDER BY id DESC"
);

$kategoriler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


    <h1>
        Kategori Yönetimi
    </h1>


    <p style="color:#666;">
        Ana sayfadaki kategori kutuları buradan yönetilir.
    </p>


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


    <!-- KATEGORİ EKLE -->

    <div class="card">

        <h2>
            ➕ Yeni Kategori Ekle
        </h2>


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
                placeholder="Örn: Bitkisel Sabunlar"
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
                Kategori Görseli
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


            <button
                type="submit"
                style="
                    background:#222;
                    color:white;
                    padding:12px 22px;
                    border:0;
                    border-radius:6px;
                    cursor:pointer;
                "
            >
                💾 Kategoriyi Kaydet
            </button>


        </form>

    </div>



    <!-- KATEGORİ LİSTESİ -->

    <div class="card">

        <h2>
            📂 Mevcut Kategoriler
        </h2>


        <?php if (!empty($kategoriler)): ?>


            <div style="overflow-x:auto;">


                <table>


                    <thead>

                        <tr>

                            <th>
                                Görsel
                            </th>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Tarih
                            </th>

                            <th>
                                İşlem
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach ($kategoriler as $kategori): ?>


                        <tr>


                            <td>

                                <?php if (!empty($kategori["image"])): ?>

                                    <img
                                        src="../uploads/categories/<?= htmlspecialchars($kategori["image"]) ?>"
                                        style="
                                            width:70px;
                                            height:70px;
                                            object-fit:cover;
                                            border-radius:8px;
                                        "
                                    >

                                <?php else: ?>

                                    Görsel Yok

                                <?php endif; ?>

                            </td>



                            <td>

                                <b>
                                    <?= htmlspecialchars($kategori["name"]) ?>
                                </b>

                            </td>



                            <td>

                                <?= htmlspecialchars($kategori["created_at"]) ?>

                            </td>



                            <td>

                                <a href="category-edit.php?id=<?= $kategori['id'] ?>">
    ✏ Düzenle
</a>

                                &nbsp;

                                <a
    href="category-delete.php?id=<?= $kategori['id'] ?>"
    onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')"
>
    🗑 Sil
</a>

                            </td>


                        </tr>


                    <?php endforeach; ?>


                    </tbody>


                </table>


            </div>


        <?php else: ?>


            <p>
                Henüz kategori eklenmemiş.
            </p>


        <?php endif; ?>


    </div>


</div>


<?php include "../includes/footer.php"; ?>