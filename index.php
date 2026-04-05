<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Stranger's Market</title>
</head>
<body>
<header>
    <h1>Stranger's Market</h1>
    <nav style="margin-top:15px;">
        <a href="index.php" class="btn">Mercado</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="carrito.php" class="btn">Maletín</a>
            <a href="perfil.php" class="btn">Mi Perfil</a>
            <a href="logout.php" class="btn" style="border-color:#a30000; color:#a30000;">Salir</a>
        <?php else: ?>
            <a href="login.php" class="btn">Identificarse</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container">
    <div class="grid">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM productos");
        while($p = mysqli_fetch_assoc($res)): ?>
            <div class="card"><img src="assets/<?php echo $p['imagen_url']; ?>" alt="item">
                
                <h3><?php echo $p['nombre_producto']; ?></h3>
                <p class="price"><?php echo number_format($p['precio_producto'], 0); ?> PTAS</p>
                <form action="acciones.php" method="POST">
                    <input type="hidden" name="id_p" value="<?php echo $p['id_producto']; ?>">
                    <button type="submit" name="add_cart" class="btn">Añadir al Maletín</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>