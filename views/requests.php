<?php 

require '../data/database.php';
$db = new Database();

$data = $_POST;
extract($_POST);

if($tipo == "save"){
    $accion = $db->guardarEmpleado($data);
}else if($tipo == "edit"){
    $accion = $db->editarEmpleado($data);
}else if($tipo == "delete"){
    $accion = $db->eliminarEmpleado($id);
}

echo json_encode($accion);