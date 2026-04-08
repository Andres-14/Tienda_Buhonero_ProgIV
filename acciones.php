<?php
include 'db.php';

if (isset($_POST['login'])) {
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $password = $_POST['pass'];

    $query = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conn, $query);
    $usuario = mysqli_fetch_assoc($resultado);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['user_id'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['es_admin'] = $usuario['es_admin'];
        
        header("Location: index.php");
    } else {
        header("Location: login.php?error=1");
    }
    exit();
}

if (isset($_POST['register'])) {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $p1 = $_POST['p1'];
    $p2 = $_POST['p2'];

    if ($p1 === $p2) {
        $hash = password_hash($p1, PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (nombre, correo, password, es_admin) VALUES ('$nombre', '$correo', '$hash', 0)";
        
        if (mysqli_query($conn, $query)) {
            header("Location: login.php?registro=success");
        } else {
            echo "Error al registrar: " . mysqli_error($conn);
        }
    } else {
        header("Location: registro.php?error=match");
    }
    exit();
}

if (isset($_POST['add_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $u_id = $_SESSION['user_id'];
    $p_id = $_POST['id_p'];

    $check = mysqli_query($conn, "SELECT * FROM carrito WHERE id_usuario = $u_id AND id_producto = $p_id");
    
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = $u_id AND id_producto = $p_id");
    } else {
        mysqli_query($conn, "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES ($u_id, $p_id, 1)");
    }
    
    header("Location: index.php");
    exit();
}

if (isset($_GET['del_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $c_id = $_GET['del_cart'];
    $u_id = $_SESSION['user_id'];
    mysqli_query($conn, "DELETE FROM carrito WHERE id_carrito = $c_id AND id_usuario = $u_id");
    
    header("Location: carrito.php");
    exit();
}

header("Location: index.php");
?>