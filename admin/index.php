<?php

session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require_once "../config/database.php";

$admin = $_SESSION["admin"];

$sorgu = $pdo->prepare(
    "SELECT * FROM users WHERE username=?"
);

$sorgu->execute([$admin]);

$kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

?>


<?php include "../includes/header.php"; ?>


<?php include "../includes/menu.php"; ?>


<div class="content">


<h1>
Dashboard
</h1>


<div class="card">

<h2>
Hoş Geldiniz 👋
</h2>


<p>
Kullanıcı:
<b>
<?= $admin ?>
</b>
</p>


<p>
Son giriş zamanı:
<b>
<?= $kullanici["last_login"] ?>
</b>
</p>


</div>



<div class="card">

<h2>
Sistem Durumu
</h2>


<p>
🧼 Ürün Yönetimi:
Hazırlanıyor
</p>


<p>
📁 Kategori Yönetimi:
Pasif
</p>


<p>
💾 Son Yedekleme:
Henüz yapılmadı
</p>


</div>



<div class="card">

<h2>
Kısa Yollar
</h2>


<a href="#" class="button">
📦 Ürün Ekle
</a>


<a href="#" class="button">
🖼 Görsel Yükle
</a>


</div>


</div>



<?php include "../includes/footer.php"; ?>