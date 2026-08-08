<?php
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Bosnalı SOAX Yönetim Paneli</title>


<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f5f6f8;
    color:#222;
}


/* SIDEBAR */

.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:240px;
    height:100vh;
    background:#222;
    color:white;
    overflow-y:auto;
}


.logo{
    padding:25px 15px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
    border-bottom:1px solid #333;
}


/* MENÜ */

.menu{
    padding-top:10px;
}


.menu a{
    display:block;
    color:white;
    padding:15px 20px;
    text-decoration:none;
    transition:0.2s;
}


.menu a:hover{
    background:#444;
}


.menu a.active{
    background:#444;
}


/* ANA İÇERİK */

.content{
    margin-left:240px;
    padding:30px;
    min-height:100vh;
}


.content h1{
    margin-top:0;
    margin-bottom:10px;
    font-size:30px;
}


/* KART */

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 3px 12px rgba(0,0,0,0.08);
    margin-bottom:20px;
}


.card h2{
    margin-top:0;
}


/* BUTON */

.button{
    display:inline-block;
    background:#222;
    color:white;
    padding:11px 18px;
    border-radius:6px;
    text-decoration:none;
    margin-right:8px;
    margin-bottom:8px;
}


.button:hover{
    background:#444;
}


/* TABLO */

table{
    width:100%;
    border-collapse:collapse;
}


th{
    background:#f1f1f1;
    font-weight:bold;
}


th,
td{
    border-bottom:1px solid #eee;
    padding:10px;
    text-align:left;
}


tr:hover{
    background:#fafafa;
}


/* FORM */

input,
textarea,
select{
    font-family:Arial, sans-serif;
}


input:focus,
textarea:focus,
select:focus{
    outline:none;
    border-color:#555;
}


/* MOBİL */

@media(max-width:800px){

    .sidebar{
        position:relative;
        width:100%;
        height:auto;
    }


    .content{
        margin-left:0;
        padding:20px;
    }


    .logo{
        padding:18px;
    }

}

</style>

</head>


<body>