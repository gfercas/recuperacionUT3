<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de documentos</title>
</head>
<body>
    <form action="index.php" method="post" enctype="multipart/form-data">
        Enviar islas: <input type="file" name="islas">
        <input type="submit" value="Enviar islas">
    </form>
    <form action="index.php" method="post" enctype="multipart/form-data">
        Enviar municipios: <input type="file" name="municipios">
        <input type="submit" value="Enviar municipios">
    </form>
    <br><br>
    <a href="visualizador.php">Ver Datos</a>
</body>
</html>

<?php
if(isset($_POST) && !empty($_FILES)){
    include('conexion.php');
    //insertamos islas
    if(isset($_FILES['islas']) && !empty($_FILES['islas'])){
        $islasCSV = fopen($_FILES['islas']['tmp_name'], 'r');
        $isla = fgetcsv($islasCSV); // saltamos la primera fila con las cabeceras
        $isla = fgetcsv($islasCSV);
        while($isla){
            $sql = "INSERT INTO islas VALUES (:IDIsla, :nombre)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':IDIsla', $isla[0]);
            $stmt->bindParam(':nombre', $isla[1]);
            $stmt->execute();
            $isla = fgetcsv($islasCSV);
        }
    }
    // insertamos municipios (mismo código que islas)
    if(isset($_FILES['municipios']) && !empty($_FILES['municipios'])){
        $municipiosCSV = fopen($_FILES['municipios']['tmp_name'], 'r');
        $municipio = fgetcsv($municipiosCSV); // saltamos la primera fila con las cabeceras
        $municipio = fgetcsv($municipiosCSV);
        while($municipio){
            $sql = "INSERT INTO municipios VALUES (:IDmunicipio, :nombre, :IDIsla, :superficie, :perimetro)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':IDmunicipio', $municipio[0]);
            $stmt->bindParam(':nombre', $municipio[2]);
            $stmt->bindParam(':IDIsla', $municipio[6]);
            $stmt->bindParam(':superficie', $municipio[9]);
            $stmt->bindParam(':perimetro', $municipio[10]);
            $stmt->execute();
            $municipio = fgetcsv($municipiosCSV);
        }
    }
}