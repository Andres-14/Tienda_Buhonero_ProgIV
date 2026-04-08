<?php 
include 'db.php'; 
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$u_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Tu Maletín - Stranger</title>
</head>
<body>
<header class="no-print">
    <h1>Tu Maletín de Equipo</h1>
    <nav>
        <a href="index.php" class="btn">Seguir Comprando</a>
        <button onclick="window.print()" class="btn" style="border-color:var(--gold-pesetas); color:var(--gold-pesetas);">Generar Factura (Imprimir)</button>
    </nav>
</header>

<div class="container">
    <div class="card">
        <h2 style="text-align:center;">Detalle de la Transacción</h2>
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                    <th class="no-print">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_general = 0;
                $sql = "SELECT c.id_carrito, c.cantidad, p.nombre_producto, p.precio_producto, p.imagen_url 
                        FROM carrito c 
                        JOIN productos p ON c.id_producto = p.id_producto 
                        WHERE c.id_usuario = $u_id";
                $res = mysqli_query($conn, $sql);

                if(mysqli_num_rows($res) > 0):
                    while($r = mysqli_fetch_assoc($res)): 
                        $subtotal = $r['cantidad'] * $r['precio_producto'];
                        $total_general += $subtotal;
                ?>
                <tr>
                    <td><img src="assets/<?php echo $r['imagen_url']; ?>" width="40"></td>
                    <td><?php echo $r['nombre_producto']; ?></td>
                    <td><?php echo $r['cantidad']; ?></td>
                    <td><?php echo number_format($r['precio_producto'], 0); ?> PTAS</td>
                    <td class="price"><?php echo number_format($subtotal, 0); ?> PTAS</td>
                    <td class="no-print">
                        <a href="acciones.php?del_cart=<?php echo $r['id_carrito']; ?>" style="color:red; font-weight:bold;">[X] Quitar</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:50px;">El maletín está vacío, stranger...</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div style="text-align:right; margin-top:30px;">
            <h3 style="color:var(--text-main);">Gran Total:</h3>
            <span class="price" style="font-size:2.5em;"><?php echo number_format($total_general, 0); ?> PTAS</span>
        </div>
    </div>
</div>

<footer style="text-align:center; margin-top:50px; font-size:0.8em; color:gray;" class="only-print">
    <p>"Heh heh heh... Thank you!"</p>
    <p>Factura generada el: <?php echo date("d/m/Y H:i"); ?></p>
</footer>

</body>
</html>