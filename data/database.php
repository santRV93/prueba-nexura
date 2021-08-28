<?php

class Database{
    private $mysqli;
    private $host = 'localhost';
    private $user = 'root';
    private $port = 3306;
    private $password = ''; 
    private $database = 'empleados';

    function conectar(){
        $this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);
        if ($this->mysqli->connect_errno) {
            echo "Fallo al conectar a MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
        }
    }

    function listarEmpleados(){
        $this->conectar();
        $sql = "SELECT e.*,a.nombre as area
                FROM empleados e 
                INNER JOIN areas a ON e.area_id = a.id
                order by id desc";
        $result = $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $this->mysqli->close();

        return $result;
    }

    function obtenerEmpleado($id){
        $this->conectar();
        $sql = "SELECT e.*
                FROM empleados e
                WHERE id = $id";
        $result = $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $this->mysqli->close();

        return $result;
    }
    
    function listarRoles(){
        $this->conectar();
        $sql = "SELECT * from roles";
        $result = $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $this->mysqli->close();

        return $result;
    }

    function listarAreas(){
        $this->conectar();
        $sql = "SELECT * from areas";
        $result = $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $this->mysqli->close();

        return $result;
    }

    function listarRolesEmpleado($id){
        $this->conectar();
        $sql = "SELECT rol_id from empleado_rol where empleado_id = $id";
        $result = $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $roles = [];
        foreach($result as $key => $value){
            $roles[] = $value['rol_id'];
        }
        $this->mysqli->close();

        return $roles;
    }

    function guardarEmpleado($data){
        $this->conectar();
        $registro = 0;
        if(isset($data['boletin'])){
            $boletin = 1;
        }else{
            $boletin = 0;
        }
        $sql = "INSERT INTO empleados(nombre, email, sexo, area_id, boletin, descripcion)VALUES('".$data['nombre']."','".$data['email']."','".$data['sexo']."',".$data['area_id'].",".$boletin.",'".$data['descripcion']."')";
        $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        if($this->mysqli->affected_rows > 0){
            $registro = 1;
            $empleado_id = $this->mysqli->insert_id;
            foreach($data['roles'] as $key => $value){
                $this->mysqli->query("INSERT INTO empleado_rol(empleado_id, rol_id)VALUES($empleado_id, $value)");
            }
        }
        $resultado = array('registro' => $registro);
        $this->mysqli->close();

        return $resultado;
    }

    function editarEmpleado($data){
        $this->conectar();
        $actualizacion = 0;
        if(isset($data['boletin'])){
            $boletin = 1;
        }else{
            $boletin = 0;
        }
        $sql = "UPDATE empleados set nombre = '".$data['nombre']."', email = '".$data['email']."', sexo = '".$data['sexo']."', area_id = ".$data['area_id'].", boletin = ".$boletin.", descripcion = '".$data['descripcion']."' WHERE id = ".$data['id'];
        $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        $empleado_id = $data['id'];
        $roles = $this->listarRolesEmpleado($empleado_id);
        $this->conectar();
        foreach($data['roles'] as $key => $value){
            if(!in_array($value, $roles)){
                $this->mysqli->query("INSERT INTO empleado_rol(empleado_id, rol_id)VALUES($empleado_id, $value)");
            }
        }
        $imp_roles = implode(',', $data['roles']);
        $this->mysqli->query("DELETE FROM empleado_rol WHERE empleado_id = ".$empleado_id." and rol_id not in(".$imp_roles.")");
        $actualizacion = 1;

        $resultado = array('actualizacion' => $actualizacion);
        $this->mysqli->close();

        return $resultado;
    }

    function eliminarEmpleado($id){
        $this->conectar();
        $eliminado = 0;
        $sql = "DELETE FROM empleados where id = $id";
        $this->mysqli->query("DELETE FROM empleado_rol where empleado_id = $id");
        $this->mysqli->query($sql) or die("Error en la sentencia: ".$sql);
        $eliminado = 1;
        $resultado = array('eliminado' => $eliminado);
        $this->mysqli->close();

        return $resultado;
    }
}