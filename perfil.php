<?php 
include 'db.php'; 

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['user_id'];
$mensaje = "";

if(isset($_POST['update_profile'])){
    $nuevo_nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $p1 = $_POST['p1'];
    $p2 = $_POST['p2'];

    mysqli_query($conn, "UPDATE usuarios SET nombre='$nuevo_nombre' WHERE id_usuario=$u_id");
    $mensaje = "¡Datos actualizados, stranger!";

    if(!empty($p1)){
        if($p1 === $p2){
            $hash = password_hash($p1, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE usuarios SET password='$hash' WHERE id_usuario=$u_id");
            $mensaje = "¡Nombre y contraseña actualizados!";
        } else {
            $mensaje = "Error: Las contraseñas no coinciden.";
        }
    }
}

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM usuarios WHERE id_usuario = $u_id"));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Mi Perfil - The Merchant</title>
</head>
<body>
<header>
    <h1>Tu Identidad</h1>
    <nav style="margin-top:10px;">
        <a href="index.php" class="btn">Volver a la Tienda</a>
        <a href="logout.php" class="btn" style="border-color:red; color:red;">Cerrar Sesión</a>
    </nav>
</header>

<div class="container" style="max-width: 500px;">
    <?php if($mensaje != ""): ?>
        <div class="card" style="border-color: var(--accent-blue); color: var(--accent-blue); margin-bottom:20px;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Editar Datos</h2>
        <form method="POST">
            <label style="display:block; text-align:left; margin-left:10%; font-size:0.8em; color:var(--gold-pesetas);">Nombre de sobreviviente:</label>
            <input type="text" name="nombre" value="<?php echo $user['nombre']; ?>" required>
            
            <label style="display:block; text-align:left; margin-left:10%; font-size:0.8em; color:var(--gold-pesetas);">Correo (No editable):</label>
            <input type="email" value="<?php echo $user['correo']; ?>" disabled style="opacity:0.5; cursor:not-allowed;">

            <hr style="border:0; border-top:1px solid #333; margin:20px 0;">
            
            <h3>Cambiar Contraseña</h3>
            <p style="font-size:0.8em; color:gray;">(Deja en blanco si no deseas cambiarla)</p>
            
            <input type="password" name="p1" placeholder="Nueva contraseña">
            <input type="password" name="p2" placeholder="Repite nueva contraseña">

            <button type="submit" name="update_profile" class="btn" style="width:90%; margin-top:10px;">Guardar Cambios</button>
        </form>
    </div>

    <?php if($user['es_admin']): ?>
        <div class="card" style="margin-top:20px; border: 1px dashed var(--accent-blue);">
            <h3>Acceso Maestro</h3>
            <p>Tienes rango de Administrador.</p>
            <a href="admin.php" class="btn">Ir al Panel de Control</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>