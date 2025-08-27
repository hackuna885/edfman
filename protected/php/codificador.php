<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Registro de Nuevos Usuarios</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/animate.css">
</head>
<body>

     <div class="container animated fadeIn">
        <div class="abs-center">
            
                <div class="p-3">
                    <div class="row">
                        <div class="col-auto jumbotron mx-auto">    
                             <div class="text-center">
                                 <img src="../img/mail.svg" class="img-fluid animated swing delay-2s" style="width: 250px;">
                             </div>
                            <h2 class="text-center">¡Lista de usuarios Codificada!</h2>
                            <br>
                            <p>
                            <table class="table table-responsive-md" style="font-size: .8em;">
                                <tr>
                                    <th>id</th>
                                    <th>Nombre</th>
                                    <th>Code (MD5)</th>
                                </tr>
                                
                                <?php 
                                error_reporting(E_ALL ^ E_DEPRECATED);
                                header("Content-Type: text/html; Charset=UTF-8");

                                $con = new SQLite3("../data/nom035.db") or die("Problemas para conectar");

                                //BUSCA LOS REGISTROS QUE NO TIENEN MD5 EN "codeMd5"
                                $cs = $con -> query("SELECT id, Nombre, codeMd5 FROM dataEmpleados ORDER BY id");

                                $usuariosFaltantes = [];

                                while ($resul = $cs -> fetchArray()) {
                                    $id = $resul['id'];
                                    $nombre = $resul['Nombre'];
                                    $hashMd5 = md5($nombre);
                                    if (empty($resul['codeMd5'])){
                                        // Guardar los datos en un array para procesarlos más tarde
                                        $usuariosFaltantes[] = ['id' => $id, 'nombre' => $nombre, 'hashMd5' => $hashMd5];
                                        // print_r($usuariosFaltantes);

                                    }
                                    
                                    echo '
                                    <tr>
                                        <td>'.$id.'</td>
                                        <td>'.$nombre.'</td>
                                        <td class="text-justify text-monospace">'.$hashMd5.'</td>
                                    </tr>
                                    ';
                                }

                                ?>
                            </table>
                            </p>
                            <?php if (count($usuariosFaltantes) > 0): ?>
                            <form action="" method="POST">
                                <button type="submit" name="insertar_todos" class="btn btn-primary btn-lg form-control">Insertar todos los hashes MD5</button>
                            </form>
                            <?php else: ?>
                                <p class="text-center text-success">Todos los usuarios ya tienen un hash MD5 generado.</p>
                            <?php endif; ?>
                            <a href="../vendor/system/home/inicio.app" class="btn btn-secondary btn-lg form-control">Click aquí para regresar</a>
                        </div>
                    </div>
                </div>
        </div>
     </div>

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

</body>

<?php
// Insertar en la base de datos si se presiona el botón
if (isset($_POST['insertar_todos'])) {
    foreach ($usuariosFaltantes as $usuario) {
        $id = $usuario['id'];
        $hashMd5 = $usuario['hashMd5'];

        // Actualiza el hash MD5 en la tabla para el usuario correspondiente
        $query = "UPDATE dataEmpleados SET codeMd5 = :hashMd5 WHERE id = :id";
        $stmt = $con->prepare($query);
        $stmt->bindValue(':hashMd5', $hashMd5);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Verificar si este es el último elemento del array
        if ($usuario === end($usuariosFaltantes)) {
            // Cuando el foreach termine, se recarga la página
            echo '<script>
                window.location.reload();
            </script>';
        }
    }

}
?>
</html>
