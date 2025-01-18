<?php
include('conexion.php');
$sql = "SELECT * from islas";
$stmt = $conn->prepare($sql);
$stmt->execute();
$islas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de datos</title>
</head>
<body>
    <form action="visualizador.php" method="get">
        <select name="isla">
            <option value='' selected disabled>Isla</option>";
        <?php
        foreach($islas as $isla){
            echo "<option value='".$isla['IDIsla']."'>".$isla['nombre']."</option>";
        }
        ?>
        </select>
        <input type="submit" value="Ver municipios">
    </form>
    <br>
    <form action="visualizador.php" method="get">
        <input type="text" name="municipio" placeholder="nombre municipio">
        <input type="submit" value="Buscar municipio">
    </form>
    <br>
    <form action="visualizador.php" method="get">
        <input type="submit" value="Ver todos los municipios">
    </form>
    <table>
        <tr>
            <th><a href="visualizador.php?orden=municipio">Nombre</a></th>
            <th><a href="visualizador.php?orden=isla">Isla</a></th>
            <th><a href="visualizador.php?orden=superficie">Superficie</a></th>
            <th><a href="visualizador.php?orden=perimetro">Perimetro</a></th>
        </tr>
        <?php
        // estos ifs no son una maravilla pero nos cumplen la función aquí
        if(isset($_GET['isla'])){
            $sql = "SELECT municipios.nombre as municipio, islas.nombre as isla, superficie, perimetro 
            FROM municipios INNER JOIN islas ON municipios.IDIsla = islas.IDIsla WHERE islas.IDIsla = :IDIsla order by municipio";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':IDIsla', $_GET['isla']);
        } elseif(isset($_GET['municipio'])){
            $sql = "SELECT municipios.nombre as municipio, islas.nombre as isla, superficie, perimetro 
            FROM municipios INNER JOIN islas ON municipios.IDIsla = islas.IDIsla WHERE municipios.nombre like :nombre order by municipio";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nombre', '%'.$_GET['municipio'].'%');
        } elseif(isset($_GET['orden'])){
            $desc = '';
            if($_GET['orden'] == 'superficie' || $_GET['orden'] == 'perimetro') $desc = 'DESC';
            $sql = "SELECT municipios.nombre as municipio, islas.nombre as isla, superficie, perimetro 
            FROM municipios INNER JOIN islas ON municipios.IDIsla = islas.IDIsla order by " . $_GET['orden'] . " " . $desc;
            $stmt = $conn->prepare($sql);
        } else {
            $sql = "SELECT municipios.nombre as municipio, islas.nombre as isla, superficie, perimetro 
            FROM municipios INNER JOIN islas ON municipios.IDIsla = islas.IDIsla order by municipio";
            $stmt = $conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach($result as $row){
            echo "<tr>";
            echo "<td>".$row['municipio']."</td>";
            echo "<td>".$row['isla']."</td>";
            echo "<td>".$row['superficie']."</td>";
            echo "<td>".$row['perimetro']."</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>