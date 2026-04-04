<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Unirse al Mercado</title>
</head>
<body>
    <div class="container" style="max-width: 450px; margin-top: 60px;">
        <div class="card">
            <h1>Nueva Cuenta</h1>
            <?php if(isset($_GET['error'])) echo "<p style='color:red;'>¡Las contraseñas no coinciden! !Forastero!</p>"; ?>
            
            <form action="acciones.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre completo" required>
                <input type="email" name="correo" placeholder="Correo electrónico" required>
                <input type="password" name="p1" placeholder="Contraseña" required>
                <input type="password" name="p2" placeholder="Repite tu contraseña" required>
                
                <button type="submit" name="register" class="btn">Registrarse</button>
            </form>
            
            <p><a href="login.php" style="color:var(--accent-blue);">Ya tengo cuenta</a></p>
        </div>
    </div>
</body>
</html>