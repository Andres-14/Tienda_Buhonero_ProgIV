<?php 
include 'db.php'; 
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$u_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM usuarios WHERE id_usuario = $u_id"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Mi Perfil</title>
</head>
<body>
    <header>
        <h1>Tu Identidad</h1>
        <a href="index.php" class="btn">Volver a la Tienda</a>
    </header>

    <div class="container" style="max-width: 600px;">
        <div class="card">
            <h2 style="color:var(--gold-pesetas);"><?php echo $user['nombre']; ?></h2>
            <p><strong>Correo:</strong> <?php echo $user['correo']; ?></p>
            <p><strong>Nivel de Usuario:</strong> <?php echo $user['es_admin'] ? "ADMINISTRADOR DEL MERCADO" : "CLIENTE ESTÁNDAR"; ?></p>
            
            <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">

            <?php if($user['es_admin']): ?>
                <div style="background: rgba(0, 242, 255, 0.1); padding: 15px; border: 1px dashed var(--accent-blue); margin-bottom: 20px;">
                    <p>Tienes acceso al inventario masivo.</p>
                    <a href="admin.php" class="btn">PANEL DE CONTROL</a>
                </div>
            <?php endif; ?>

            <a href="logout.php" class="btn" style="border-color:red; color:red;">CERRAR SESIÓN</a>
        </div>
    </div>
</body>
</html>