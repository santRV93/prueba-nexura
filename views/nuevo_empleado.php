<?php 

require '../data/database.php';
$db = new Database();

$areas = $db->listarAreas();
$roles = $db->listarRoles();

include('header.php');

?>
<div class="alert alert-info">Los campos marcados con asterisco (*) son obligatorios</div>

<form id="form-new-empleado" method="post" onsubmit="return false;">
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <label for="nombre"><b>Nombre completo *</b></label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control" maxlength="255" placeholder="Nombre completo del empleado" name="nombre" id="nombre" required>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <label for="email"><b>Correo electrónico *</b></label>
            </div>
            <div class="col-md-10">
                <input type="email" class="form-control" maxlength="255" placeholder="Correo electrónico" name="email" id="email" required>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <label><b>Sexo *</b></label>
            </div>
            <div class="col-md-10">
                <input type="radio" name="sexo" value="M" required> Masculino<br/>
                <input type="radio" name="sexo" value="F"> Femenino
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <label for="area"><b>Area *</b></label>
            </div>
            <div class="col-md-10">
                <select name="area_id" id="area" class="form-control" required>
                    <option value="">Seleccione un rol</option>
                    <?php foreach($areas as $key => $value){ ?>
                        <option value="<?= $value['id'] ?>"><?= $value['nombre'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <label for="descripcion"><b>Descripción *</b></label>
            </div>
            <div class="col-md-10">
                <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción de la experiencia del empleado" cols="30" rows="4" required></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-10 offset-2">
                <input type="checkbox" name="boletin" id="boletin"> Deseo recibir boletín informativo
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-2">
                <label><b>Roles *</b></label>
            </div>
            <div class="col-md-10">
                <?php foreach($roles as $key => $value){ ?>
                    <input type="checkbox" class="roles" name="roles[]" value="<?= $value['id'] ?>"> <?= $value['nombre'] ?><br/>
                <?php } ?>
            </div>
        </div>
    </div>
    <input type="hidden" name="tipo" value="save" />
    <div class="form-group" id="msg-registro">

    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-10 offset-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</form>

<script>

    $("button").click(function(){
        var roles = [];
        $('.roles:checkbox:checked').each(function() {
            roles.push($(this).val());
        });
        if (roles.length == 0) {
            $("#msg-registro").html('<div class="alert alert-danger">Debes de seleccionar al menos un rol</div>');
        }else{
            $("#form-new-empleado").validate({
                messages : {
                    nombre: {
                        required: "El nombre debe de ser obligatorio",
                        maxlength: "El nombre debe de tener máximo 255 caracteres"
                    },
                    email: {
                        required: "El correo debe de ser obligatorio",
                        maxlength: "El nombre debe de tener máximo 255 caracteres",
                        email: "Ingrese un correo válido. Ejm: correo@dominio.com"
                    },
                    sexo: {
                        required: "Debe de ingresar un género"
                    },
                    area: {
                        required: "Debe de elegir un área"
                    },
                    descripcion: {
                        required: "La descripción es obligatoria"
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        type: "POST",
                        url: "requests.php",
                        data: $("#form-new-empleado").serialize(),
                        dataType: "json"
                    })
                    .done(function(data){
                        if(data.registro){
                            $("#msg-registro").html('<div class="alert alert-success">Usuario registrado con éxito.</div>');
                            $("#form-new-empleado").trigger('reset');
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 3000);
                        }else{
                            $("#msg-registro").html('<div class="alert alert-danger">Ocurrió un error al registrar el empleado</div>');
                        }
                    });
                }
            });
        }
    });

</script>

<?php 
    include('footer.php');
?>
