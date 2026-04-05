<?php include 'db.php'; if(!isset($_SESSION['user_id'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h1>Factura de Compra</h1>
    <table>
        <tr><th>Artículo</th><th>Precio</th></tr>
        <?php
        $total = 0; $u = $_SESSION['user_id'];
        $res = mysqli_query($conn, "SELECT p.* FROM carrito c JOIN productos p ON c.id_producto = p.id_producto WHERE c.id_usuario = $u");
        while($r = mysqli_fetch_assoc($res)): $total += $r['precio_producto']; ?>
            <tr><td><?php echo $r['nombre_producto']; ?></td><td class="price"><?php echo number_format($r['precio_producto'],0); ?> PTAS</td></tr>
        <?php endwhile; ?>
    </table>
    <h2 style="text-align:right">TOTAL: <span class="price"><?php echo number_format($total,0); ?> PTAS</span></h2>
    <button onclick="window.print()" class="btn">Imprimir Factura</button>
    <a href="index.php" class="btn">Seguir Comprando</a>
</div>
</body>
</html>