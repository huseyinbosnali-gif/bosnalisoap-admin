<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"];


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



    $ekle = $pdo->prepare(
        "INSERT INTO products 
        (name, category, description, image)
        VALUES (?,?,?,?)"
    );


    $ekle->execute([
        $name,
        $category,
        $description,
        $resim
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

<input 
type="text"
name="name"
required
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
></textarea>


<br><br>


<label>
Ürün Resmi
</label>

<br>

<input 
type="file"
name="image"
>


<br><br>


<button 
type="submit"
style="
padding:12px 25px;
background:#222;
color:white;
border:0;
cursor:pointer;
">

Kaydet

</button>


</form>


</div>


</div>


<?php include "../includes/footer.php"; ?>