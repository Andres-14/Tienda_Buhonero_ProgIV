<?php 
include 'db.php'; 

if(!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] != 1) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['guardar_producto'])){
    $n = mysqli_real_escape_string($conn, $_POST['nombre']);
    $p = $_POST['precio'];
    
    $nombre_imagen = "";
    if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
        $nombre_imagen = $_FILES['foto']['name'];
        $ruta_temporal = $_FILES['foto']['tmp_name'];
        $ruta_destino = "assets/" . $nombre_imagen;
        
        if (!file_exists('assets')) { mkdir('assets', 0777, true); }
        move_uploaded_file($ruta_temporal, $ruta_destino);
    }

    if(isset($_POST['id_edit']) && $_POST['id_edit'] != ""){
        $id = $_POST['id_edit'];
        $update_img = ($nombre_imagen != "") ? ", imagen_url='$nombre_imagen'" : "";
        mysqli_query($conn, "UPDATE productos SET nombre_producto='$n', precio_producto='$p' $update_img WHERE id_producto=$id");
    } else {
        $img_final = ($nombre_imagen != "") ? $nombre_imagen : "default.png";
        mysqli_query($conn, "INSERT INTO productos (nombre_producto, precio_producto, imagen_url) VALUES ('$n', '$p', '$img_final')");
    }
    header("Location: admin.php");
    exit();
}

if(isset($_GET['del_prod'])){
    $id = $_GET['del_prod'];
    mysqli_query($conn, "DELETE FROM productos WHERE id_producto=$id");
    header("Location: admin.php");
    exit();
}

if(isset($_GET['del_user'])){
    $id = $_GET['del_user'];
    if($id != $_SESSION['user_id']){
        mysqli_query($conn, "DELETE FROM usuarios WHERE id_usuario=$id");
    }
    header("Location: admin.php");
    exit();
}

if(isset($_GET['ascender'])){
    $id = $_GET['ascender'];
    mysqli_query($conn, "UPDATE usuarios SET es_admin = 1 WHERE id_usuario = $id");
    header("Location: admin.php");
    exit();
}

$edit_p = null;
if(isset($_GET['edit_prod'])){
    $id = $_GET['edit_prod'];
    $res_edit = mysqli_query($conn, "SELECT * FROM productos WHERE id_producto=$id");
    $edit_p = mysqli_fetch_assoc($res_edit);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Panel del Buhonero - Admin</title>
</head>
<body>
<div class="container">
    <h1>Panel de Control Maestro</h1>
    <a href="index.php" class="btn">Volver a la Tienda</a>

    <hr style="border-color:#333; margin: 30px 0;">

    <div class="card" style="max-width: 600px; margin: 0 auto 30px auto;">
        <h3><?php echo $edit_p ? "Editar Artículo" : "Añadir Mercancía"; ?></h3>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_edit" value="<?php echo $edit_p['id_producto'] ?? ''; ?>">
            <input type="text" name="nombre" placeholder="Nombre del item" value="<?php echo $edit_p['nombre_producto'] ?? ''; ?>" required>
            <input type="number" name="precio" placeholder="Precio (PTAS)" value="<?php echo $edit_p['precio_producto'] ?? ''; ?>" required>
            
            <div id="drop-zone" style="border: 2px dashed var(--accent-blue); padding: 25px; margin-bottom: 20px; cursor: pointer; background: rgba(0,0,0,0.3);">
                <p id="drop-text">Arrastra la imagen aquí o haz clic para buscar</p>
                <input type="file" name="foto" id="file-input" accept="image/*" style="display: none;">
                <center>
                    <img id="preview" src="<?php echo isset($edit_p) ? 'assets/'.$edit_p['imagen_url'] : ''; ?>" 
                         style="max-width: 150px; display: <?php echo isset($edit_p) ? 'block' : 'none'; ?>; margin-top: 15px; border: 1px solid var(--accent-blue);">
                </center>
            </div>

            <button type="submit" name="guardar_producto" class="btn"><?php echo $edit_p ? "Actualizar" : "Añadir"; ?></button>
            <?php if($edit_p): ?> <a href="admin.php" class="btn" style="border-color:gray; color:gray;">Cancelar</a> <?php endif; ?>
        </form>
    </div>

    <h2>Inventario de Armas e Items</h2>
    <table>
        <tr><th>Miniatura</th><th>Nombre</th><th>Precio</th><th>Acciones</th></tr>
        <?php 
        $res = mysqli_query($conn, "SELECT * FROM productos");
        while($p = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><img src="assets/<?php echo $p['imagen_url']; ?>" width="60" style="object-fit: contain;"></td>
            <td><?php echo $p['nombre_producto']; ?></td>
            <td class="price"><?php echo number_format($p['precio_producto'], 0); ?> PTAS</td>
            <td>
                <a href="?edit_prod=<?php echo $p['id_producto']; ?>" style="color:var(--accent-blue);">Editar</a> | 
                <a href="?del_prod=<?php echo $p['id_producto']; ?>" style="color:red;" onclick="return confirm('¿Eliminar del inventario?')">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <hr style="border-color:#333; margin: 50px 0;">

    <h2>Gestión de Usuarios</h2>
    <table>
        <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rango</th><th>Acciones</th></tr>
        <?php 
        $users = mysqli_query($conn, "SELECT * FROM usuarios");
        while($u = mysqli_fetch_assoc($users)): ?>
        <tr>
            <td>#<?php echo $u['id_usuario']; ?></td>
            <td><?php echo $u['nombre']; ?></td>
            <td><?php echo $u['correo']; ?></td>
            <td><?php echo $u['es_admin'] ? "<span style='color:var(--accent-blue)'>ADMIN</span>" : "CLIENTE"; ?></td>
            <td>
                <?php if(!$u['es_admin']): ?>
                    <a href="?ascender=<?php echo $u['id_usuario']; ?>" style="color:var(--gold-pesetas);">Ascender</a> | 
                <?php endif; ?>
                <?php if($u['id_usuario'] != $_SESSION['user_id']): ?>
                    <a href="?del_user=<?php echo $u['id_usuario']; ?>" style="color:red;" onclick="return confirm('¿Eliminar usuario?')">Borrar</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const preview = document.getElementById('preview');
    const dropText = document.getElementById('drop-text');

    dropZone.onclick = () => fileInput.click();
    fileInput.onchange = (e) => handleFiles(e.target.files);

    dropZone.ondragover = (e) => {
        e.preventDefault();
        dropZone.style.background = "rgba(0, 242, 255, 0.1)";
    };
    dropZone.ondragleave = () => {
        dropZone.style.background = "rgba(0,0,0,0.3)";
    };
    dropZone.ondrop = (e) => {
        e.preventDefault();
        dropZone.style.background = "rgba(0,0,0,0.3)";
        const files = e.dataTransfer.files;
        fileInput.files = files; 
        handleFiles(files);
    };

    function handleFiles(files) {
        if (files.length > 0) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.style.display = "block";
                dropText.innerText = "Archivo cargado: " + files[0].name;
            };
            reader.readAsDataURL(files[0]);
        }
    }
</script>
</body>
</html>