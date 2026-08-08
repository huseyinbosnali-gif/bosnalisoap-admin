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
| KATALOG EKLE
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $is_active = isset($_POST["is_active"]) ? 1 : 0;


    if ($title === "") {

        $mesaj = "Katalog başlığı boş bırakılamaz.";

    } else {

        $pdf_file = null;


        /*
        |--------------------------------------------------------------------------
        | PDF YÜKLE
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

                $pdf_file =
                    time() .
                    "_" .
                    uniqid() .
                    ".pdf";


                if (
                    !move_uploaded_file(
                        $_FILES["pdf_file"]["tmp_name"],
                        $klasor . $pdf_file
                    )
                ) {

                    $mesaj = "PDF dosyası yüklenemedi.";
                }
            }

        } else {

            $mesaj = "Lütfen bir PDF katalog seçin.";
        }


        /*
        |--------------------------------------------------------------------------
        | VERİTABANINA EKLE
        |--------------------------------------------------------------------------
        */

        if ($mesaj === "" && $pdf_file) {

            /*
            | Yeni katalog aktifse diğerlerini pasif yap
            */

            if ($is_active === 1) {

                $pdo->exec(
                    "UPDATE catalogs
                     SET is_active = 0"
                );
            }


            $ekle = $pdo->prepare(
                "INSERT INTO catalogs
                (
                    title,
                    pdf_file,
                    is_active
                )
                VALUES (?, ?, ?)"
            );


            $ekle->execute([
                $title,
                $pdf_file,
                $is_active
            ]);


            header("Location: catalogs.php");
            exit;
        }
    }
}


/*
|--------------------------------------------------------------------------
| KATALOGLARI GETİR
|--------------------------------------------------------------------------
*/

$sorgu = $pdo->query(
    "SELECT *
     FROM catalogs
     ORDER BY id DESC"
);

$kataloglar = $sorgu->fetchAll(PDO::FETCH_ASSOC);

?>


<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


    <h1>
        PDF Katalog Yönetimi
    </h1>


    <p style="color:#666;">
        Web sitesinde indirilecek güncel PDF kataloğunu buradan yönetebilirsiniz.
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


    <div class="card">

        <h2>
            ➕ Yeni Katalog Yükle
        </h2>


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
                required
                placeholder="Örn: Bosnalı SOAX 2026 Ürün Kataloğu"
                style="
                    width:100%;
                    padding:11px;
                    margin:8px 0 18px;
                    border:1px solid #ccc;
                    border-radius:6px;
                "
            >


            <label>
                PDF Dosyası
            </label>

            <input
                type="file"
                name="pdf_file"
                accept=".pdf,application/pdf"
                required
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
                    margin-bottom:20px;
                "
            >

                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    checked
                >

                Aktif katalog

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
                💾 Katalog Kaydet
            </button>


        </form>

    </div>



    <div class="card">

        <h2>
            📄 Mevcut Kataloglar
        </h2>


        <?php if (!empty($kataloglar)): ?>

            <div style="overflow-x:auto;">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Başlık
                            </th>

                            <th>
                                Dosya
                            </th>

                            <th>
                                Durum
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


                    <?php foreach ($kataloglar as $katalog): ?>

                        <tr>


                            <td>

                                <b>
                                    <?= htmlspecialchars($katalog["title"]) ?>
                                </b>

                            </td>


                            <td>

                                <a
                                    href="../uploads/catalogs/<?= htmlspecialchars($katalog["pdf_file"]) ?>"
                                    target="_blank"
                                >
                                    PDF Aç
                                </a>

                            </td>


                            <td>

                                <?php if ((int)$katalog["is_active"] === 1): ?>

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

                                <?= htmlspecialchars($katalog["created_at"]) ?>

                            </td>


                            <td>

                                <a href="catalog-edit.php?id=<?= $katalog['id'] ?>">
    ✏ Düzenle
</a>

                                &nbsp;

                                <a
    href="catalog-delete.php?id=<?= $katalog['id'] ?>"
    onclick="return confirm('Bu kataloğu silmek istediğinize emin misiniz?')"
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
                Henüz katalog yüklenmemiş.
            </p>

        <?php endif; ?>


    </div>


</div>


<?php include "../includes/footer.php"; ?>