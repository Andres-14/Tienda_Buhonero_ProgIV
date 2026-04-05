<?php 
include 'db.php'; 
if(!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) header("Location: index.php");

if(isset($_POST['nuevo_p'])){
    $n = $_POST['nombre']; $p = $_POST['precio']; $i = $_POST['img'];
    mysqli_query($conn, "INSERT INTO productos (nombre_producto, precio_producto, imagen_url) VALUES ('$n', '$p', '$i')");
}
if(isset($_GET['del'])){
    $id = $_GET['del']; mysqli_query($conn, "DELETE FROM productos WHERE id_producto=$id");
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h1>Gestión del Inventario</h1>
    <div class="card" style="max-width:500px; margin:auto;">
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre Arma" required>
            <input type="number" name="precio" placeholder="Precio PTAS" required>
            <input type="text" name="img" placeholder="nombre_archivo.png" required>
            <button type="submit" name="nuevo_p" class="btn">Añadir al Stock</button>
        </form>
    </div>
    <table>
        <tr><th>Producto</th><th>Acción</th></tr>
        <?php $res = mysqli_query($conn, "SELECT * FROM productos");
        while($p = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo $p['nombre_producto']; ?></td>
                <td><a href="?del=<?php echo $p['id_producto']; ?>" style="color:red">Eliminar</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br><a href="index.php" class="btn">Volver</a>
</div>
</body>
</html>