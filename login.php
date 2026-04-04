<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Identificarse - Forastero</title>
</head>
<body>
    <div class="container" style="max-width: 450px; margin-top: 100px;">
        <div class="card">
            <h1>Iniciar Sesión</h1>
            <?php if(isset($_GET['error'])) echo "<p style='color:red;'>Credenciales incorrectas, forastero...</p>"; ?>
            
            <form action="acciones.php" method="POST">
                <input type="email" name="correo" placeholder="Tu correo de negocios" required>
                <input type="password" name="pass" placeholder="Tu contraseña secreta" required>
                <button type="submit" name="login" class="btn">Entrar al Mercado</button>
            </form>
            
            <p style="margin-top:20px;">¿Eres nuevo aquí? <br>
            <a href="registro.php" style="color:var(--accent-blue);">Crea una cuenta</a></p>
            <a href="index.php" class="btn" style="font-size:12px; margin-top:10px;">Volver</a>
        </div>
    </div>
</body>
</html>