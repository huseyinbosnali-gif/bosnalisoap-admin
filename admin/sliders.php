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
| SLIDER EKLEME
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

        $image = null;


        /*
        |--------------------------------------------------------------------------
        | GÖRSEL YÜKLEME
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
        | VERİTABANINA EKLE
        |--------------------------------------------------------------------------
        */

        if ($mesaj === "") {

            $ekle = $pdo->prepare(
                "INSERT INTO sliders
                (
                    title,
                    description,
                    button_text,
                    button_link,
                    image,
                    sort_order,
                    is_active
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );


            $ekle->execute([
                $title,
                $description,
                $button_text,
                $button_link,
                $image,
                $sort_order,
                $is_active
            ]);


            header("Location: sliders.php");
            exit;
        }
    }
}


/*
|--------------------------------------------------------------------------
| SLIDERLARI GETİR
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->query(
    "SELECT *
     FROM sliders
     ORDER BY sort_order ASC, id DESC"
);

$sliderlar = $sorgu->fetchAll(PDO::FETCH_ASSOC);

?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


    <h1>
        Slider / Banner Yönetimi
    </h1>


    <p style="color:#666;">
        Ana sayfadaki büyük banner alanını buradan yönetebilirsiniz.
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


    <!-- YENİ SLIDER -->

    <div class="card">

        <h2>
            ➕ Yeni Slider Ekle
        </h2>


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
                required
                placeholder="Örn: Doğadan Gelen Temizlik"
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
                placeholder="Slider açıklaması"
                style="
                    width:100%;
                    padding:11px;
                    margin:8px 0 15px;
                    border:1px solid #ccc;
                    border-radius:6px;
                    resize:vertical;
                "
            ></textarea>


            <label>
                Buton Yazısı
            </label>

            <input
                type="text"
                name="button_text"
                placeholder="Örn: Ürünleri Keşfet"
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
                placeholder="Örn: #urunler"
                style="
                    width:100%;
                    padding:11px;
                    margin:8px 0 15px;
                    border:1px solid #ccc;
                    border-radius:6px;
                "
            >


            <label>
                Slider Görseli
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
                value="0"
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
                    margin-bottom:20px;
                "
            >

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    checked
                >

                Aktif

            </label>


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
                💾 Slider Kaydet
            </button>


        </form>

    </div>



    <!-- SLIDER LİSTESİ -->

    <div class="card">

        <h2>
            🖼️ Mevcut Sliderlar
        </h2>


        <?php if (!empty($sliderlar)): ?>


            <div style="overflow-x:auto;">


                <table>


                    <thead>

                        <tr>

                            <th>
                                Görsel
                            </th>

                            <th>
                                Başlık
                            </th>

                            <th>
                                Sıra
                            </th>

                            <th>
                                Durum
                            </th>

                            <th>
                                İşlem
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach ($sliderlar as $slider): ?>


                        <tr>


                            <td>

                                <?php if (!empty($slider["image"])): ?>

                                    <img
                                        src="../uploads/sliders/<?= htmlspecialchars($slider["image"]) ?>"
                                        style="
                                            width:120px;
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
                                    <?= htmlspecialchars($slider["title"]) ?>
                                </b>

                                <?php if (!empty($slider["description"])): ?>

                                    <div
                                        style="
                                            color:#777;
                                            font-size:13px;
                                            margin-top:5px;
                                        "
                                    >
                                        <?= htmlspecialchars(
                                            mb_strimwidth(
                                                $slider["description"],
                                                0,
                                                80,
                                                "..."
                                            )
                                        ) ?>
                                    </div>

                                <?php endif; ?>

                            </td>


                            <td>

                                <?= (int)$slider["sort_order"] ?>

                            </td>


                            <td>

                                <?php if ((int)$slider["is_active"] === 1): ?>

                                    <span style="color:green;font-weight:bold;">
                                        Aktif
                                    </span>

                                <?php else: ?>

                                    <span style="color:#999;font-weight:bold;">
                                        Pasif
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <a href="slider-edit.php?id=<?= $slider['id'] ?>">
    ✏ Düzenle
</a>

                                &nbsp;

                                <a
    href="slider-delete.php?id=<?= $slider['id'] ?>"
    onclick="return confirm('Bu sliderı silmek istediğinize emin misiniz?')"
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
                Henüz slider eklenmemiş.
            </p>


        <?php endif; ?>


    </div>


</div>


<?php include "../includes/footer.php"; ?>