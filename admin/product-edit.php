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
    $category = $_POST["category"];
    $description = $_POST["description"];

    $image = $urun["image"];


    if(isset($_FILES["image"]) && $_FILES["image"]["name"] != ""){


        $klasor="../uploads/";


        $image = time()."_".$_FILES["image"]["name"];


        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $klasor.$image
        );

    }


    $guncelle = $pdo->prepare(
        "UPDATE products SET
        name=?,
        category=?,
        description=?,
        image=?
        WHERE id=?"
    );


    $guncelle->execute([
        $name,
        $category,
        $description,
        $image,
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


<label>
Ürün Adı
</label>

<br>

<input 
type="text"
name="name"
value="<?= $urun["name"] ?>"
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
value="<?= $urun["category"] ?>"
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

Güncelle

</button>


</form>


</div>


</div>


<?php include "../includes/footer.php"; ?>