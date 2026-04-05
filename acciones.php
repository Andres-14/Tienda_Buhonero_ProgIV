<?php
include 'db.php';

if(isset($_POST['login'])){
    $c = mysqli_real_escape_string($conn, $_POST['correo']);
    $p = $_POST['pass'];
    $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM usuarios WHERE correo='$c'"));
    if($u && password_verify($p, $u['password'])){
        $_SESSION['user_id'] = $u['id_usuario'];
        $_SESSION['es_admin'] = $u['es_admin'];
        header("Location: index.php");
    } else { header("Location: login.php?error=1"); }
}

if(isset($_POST['register'])){
    $p1 = $_POST['p1']; $p2 = $_POST['p2'];
    if($p1 === $p2){
        $h = password_hash($p1, PASSWORD_DEFAULT);
        $n = mysqli_real_escape_string($conn, $_POST['nombre']);
        $c = mysqli_real_escape_string($conn, $_POST['correo']);
        mysqli_query($conn, "INSERT INTO usuarios (nombre, correo, password) VALUES ('$n', '$c', '$h')");
        header("Location: login.php");
    } else { header("Location: registro.php?error=match"); }
}

if(isset($_POST['add_cart'])){
    if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
    $u = $_SESSION['user_id']; $p = $_POST['id_p'];
    mysqli_query($conn, "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES ($u, $p, 1)");
    header("Location: index.php");
}
?>