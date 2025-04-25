<?php
include 'conexion.php';

if (isset($_GET['provincia'])) {
    $provincia = $_GET['provincia'];
    $query = "SELECT codigo_distrito, nombre_distrito FROM distrito WHERE codigo_provincia = '$provincia'";
    $result = $conexion->query($query);
    $distritos = [];
    while ($row = $result->fetch_assoc()) {
        $distritos[] = $row;
    }
    echo json_encode($distritos);
    exit;
}

if (isset($_GET['distrito'])) {
    $distrito = $_GET['distrito'];
    $query = "SELECT codigo_corregimiento, nombre_corregimiento FROM corregimiento WHERE codigo_distrito = '$distrito'";
    $result = $conexion->query($query);
    $corregimientos = [];
    while ($row = $result->fetch_assoc()) {
        $corregimientos[] = $row;
    }
    echo json_encode($corregimientos);
    exit;
}

if (isset($_GET['departamento'])) {
    $departamento = $_GET['departamento'];
    $query = "SELECT codigo, nombre FROM cargo WHERE dep_codigo = '$departamento'";
    $result = $conexion->query($query);
    $cargos = [];
    while ($row = $result->fetch_assoc()) {
        $cargos[] = $row;
    }
    echo json_encode($cargos);
    exit;
}
?>