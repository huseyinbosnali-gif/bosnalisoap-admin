<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";


$urunler = $pdo->query(
    "SELECT * FROM products ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);


?>

<?php include "../includes/header.php"; ?>

<?php include "../includes/menu.php"; ?>


<div class="content">


<h1>
📦 Ürün Yönetimi
</h1>


<div class="card">

<a href="product-add.php" 
style="
background:#222;
color:white;
padding:12px 20px;
text-decoration:none;
border-radius:5px;
">

+ Yeni Ürün Ekle

</a>

</div>



<div class="card">


<table width="100%" cellpadding="10" cellspacing="0">

<tr>

<th>Resim</th>
<th>Ürün Adı</th>
<th>Kategori</th>
<th>Tarih</th>
<th>İşlem</th>

</tr>


<?php foreach($urunler as $urun): ?>

<tr>


<td>

<?php if($urun["image"]): ?>

<img src="../uploads/<?= $urun["image"] ?>"
width="80">

<?php else: ?>

Resim Yok

<?php endif; ?>

</td>


<td>
<?= $urun["name"] ?>
</td>


<td>
<?= $urun["category"] ?>
</td>


<td>
<?= $urun["created_at"] ?>
</td>


<td>

<a href="product-edit.php?id=<?= $urun['id'] ?>">
✏ Düzenle

</a>


&nbsp;


<a href="product-delete.php?id=<?= $urun['id'] ?>"
onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">

🗑 Sil

</a>


</td>


</tr>


<?php endforeach; ?>


<tr>

<th>ID</th>

<th>Ürün Adı</th>

<th>Kategori</th>

<th>Tarih</th>

<th>İşlem</th>

</tr>


<?php foreach($urunler as $urun): ?>


<tr>


<td>
<?= $urun["id"] ?>
</td>


<td>
<?= $urun["name"] ?>
</td>


<td>
<?= $urun["category"] ?>
</td>


<td>
<?= $urun["created_at"] ?>
</td>


<td>

<a href="product-edit.php?id=<?= $urun['id'] ?>">
✏ Düzenle
</a>


<a href="product-delete.php?id=<?= $urun['id'] ?>"
onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">
    🗑 Sil
</a>


</td>


</tr>


<?php endforeach; ?>


</table>


</div>


</div>


<?php include "../includes/footer.php"; ?>