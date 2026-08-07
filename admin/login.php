<?php
session_start();

require_once "../config/database.php";

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sorgu = $pdo->prepare(
        "SELECT * FROM users WHERE username = ?"
    );

    $sorgu->execute([$username]);

    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($kullanici && $password == $kullanici["password"]) {

        $_SESSION["admin"] = $kullanici["username"];

        $guncelle = $pdo->prepare(
            "UPDATE users SET last_login = NOW() WHERE id = ?"
        );

        $guncelle->execute([$kullanici["id"]]);

        header("Location: index.php");
        exit;

    } else {

        $hata = "Kullanıcı adı veya şifre hatalı!";

    }

}

?>

<!DOCTYPE html>
<html lang="tr">
<head>

<meta charset="UTF-8">
<title>Bosnalı SOAX Yönetim Paneli</title>

<style>

body{
    font-family: Arial, sans-serif;
    background:#f3f3f3;
}

.login-box{
    width:350px;
    margin:120px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 15px #ccc;
}

h2{
    text-align:center;
}

input{
    width:100%;
    padding:12px;
    margin:8px 0;
}

button{
    width:100%;
    padding:12px;
    background:#333;
    color:white;
    border:0;
    cursor:pointer;
}

.hata{
    color:red;
    text-align:center;
}

</style>

</head>

<body>

<div class="login-box">

<h2>
Bosnalı SOAX<br>
Yönetim Paneli
</h2>

<?php if($hata): ?>

<p class="hata">
<?= $hata ?>
</p>

<?php endif; ?>


<form method="post">

<input 
type="text" 
name="username" 
placeholder="Kullanıcı Adı"
required>


<input 
type="password" 
name="password" 
placeholder="Şifre"
required>


<button type="submit">
Giriş Yap
</button>


</form>

</div>

</body>
</html>