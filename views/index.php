<?php 

require '../data/database.php';
$db = new Database();

$empleados = $db->listarEmpleados();

include('header.php');
?>

<a href="nuevo_empleado.php">
    <button class="btn btn-primary float-right <?= ($empleados != null ? 'mb-3' : 'mt-5') ?>"><i class="fa fa-user-plus"></i> Crear</button>
</a>
<?php 
if($empleados != null){ ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th><i class="fa fa-user"></i> Nombre</th>
            <th><i class="fa fa-at"></i> Email</th>
            <th><i class="fa fa-venus-mars"></i> Sexo</th>
            <th><i class="fa fa-briefcase"></i> Área</th>
            <th><i class="fa fa-envelope"></i> Boletín</th>
            <th>Modificar</th>
            <th>Eliminar</th>
        </thead>
        <tbody>
            <?php foreach($empleados as $key => $value){ ?>
                <tr id="tb-<?= $value['id'] ?>">
                    <td><?= $value['nombre'] ?></td>
                    <td><?= $value['email'] ?></td>
                    <td><?= ($value['sexo'] == "F" ? "Femenino":"Masculino") ?></td>
                    <td><?= $value['area'] ?></td>
                    <td><?= ($value['boletin'] ? "Si":"No") ?></td>
                    <td><a href="editar_empleado.php?id=<?= $value['id'] ?>"><i class="fa fa-pencil-square-o fa-2x color-iconos" aria-hidden="true"></i></a></td>
                    <td><a onclick="eliminarEmpleado(<?= $value['id'] ?>)"><i class="fa fa-trash fa-2x color-iconos" aria-hidden="true"></i></a></td></td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>

<script>

    function eliminarEmpleado(id){
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: { id: id, tipo: "delete" },
            dataType: "json"
        })
        .done(function(data){
            if(data.eliminado){
                $("#tb-"+id).remove();
                alertify.success("Empleado eliminado con éxito");
            }else{
                alertify.error("Ocurrió un error al eliminar el empleado");
            }
        });
    }

</script>

<?php }else{ ?>
    <div class="alert alert-info">En estos momentos no hay empleados registrados</div>
<?php } ?>

<?php 
    include('footer.php');
?>

