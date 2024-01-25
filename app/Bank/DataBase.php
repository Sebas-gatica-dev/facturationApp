<?php

namespace App\Bank;

use App\Models\Cliente;
use Illuminate\Http\Request;

class DataBase 
{
    protected $conexion;
    protected $nombres_fantasia;


    public function __construct($db)
    {
       $this -> conexion = mysqli_connect(env('DB_EXTERNAL_HOST'), env('DB_EXTERNAL_USERNAME'),env('DB_EXTERNAL_PASSWORD'), $db);
    }

    public function totalUsuarios(){
        $consulta = "SELECT COUNT(name) FROM users  WHERE  id > 1";
        $resultado = mysqli_query($this -> conexion,$consulta);
        $row = $resultado->fetch_row();
             
       

        return $row[0];
    }


    public function totalUsuariosMesAnterior(){

        date_default_timezone_set("America/Argentina/Buenos_Aires");

        $mes_anterior = date('m', strtotime('-1 month'));
        $anio = date("Y");

        $consulta = "SELECT COUNT(name) FROM users WHERE  id > 1 AND MONTH(created_at) = " . $mes_anterior . ' AND YEAR(created_at) = ' . $anio;
        $resultado = mysqli_query($this -> conexion,$consulta);
        $row = $resultado->fetch_row();

        return $row[0];
    }

    public function totalActivos(){
        
        $consulta = "SELECT COUNT(name) FROM users WHERE id > 1 AND `status` = 'activo'";
        $resultado = mysqli_query($this -> conexion,$consulta);
        $row = $resultado->fetch_row();

        return $row[0];

    }

    public function totalInactivos(){
        
        $consulta = "SELECT COUNT(name) FROM users WHERE id > 1 AND  `status` != 'activo'";
        $resultado = mysqli_query($this -> conexion,$consulta);
        $row = $resultado->fetch_row();

        return $row[0];

    }


    public function cantidadDeEmpresas()
{
    $consulta = "SELECT * FROM empresa";
    $resultado = mysqli_query($this->conexion, $consulta);



    $this->nombres_fantasia = Cliente::pluck('nombre_fantasia')->toArray();



    
    if ($resultado) {
        $empresas = [];

        while ($row = $resultado->fetch_assoc()) {
            $empresas[] = $row;
        }

        // Filtrar las empresas que no están en $nombres_fantasia
        $empresasFiltradas = array_filter($empresas, function ($empresa) {
            return !in_array($empresa['nombre_fantasia'], $this->nombres_fantasia);
        });

        // Devolver la cantidad de empresas filtradas
        return count($empresasFiltradas);
    } else {
        // Manejar el error en caso de que la consulta falle
        return 0;
    }
}




// public function cantidadDeEmpresas()
// {
//     $consulta = "SELECT COUNT(*) FROM empresa";
//     $resultado = mysqli_query($this->conexion, $consulta);


    
//     if ($resultado) {
//         $row = $resultado->fetch_row();
//         return $row ? $row[0] : 0;
//     } else {
//         // Manejar el error en caso de que la consulta falle
//         return 0;
//     }
// }


public function obtenerEmpresasFiltradas()
{
    $consulta = "SELECT * FROM empresa";
    $resultado = mysqli_query($this->conexion, $consulta);



    $this->nombres_fantasia = Cliente::pluck('nombre_fantasia')->toArray();



    
    if ($resultado) {
        $empresas = [];

        while ($row = $resultado->fetch_assoc()) {
            $empresas[] = $row;
        }

        // Filtrar las empresas que no están en $nombres_fantasia
        $empresasFiltradas = array_filter($empresas, function ($empresa) {
            return !in_array($empresa['nombre_fantasia'], $this->nombres_fantasia);
        });

        // Devolver la cantidad de empresas filtradas
        return count($empresasFiltradas);
    } else {
        // Manejar el error en caso de que la consulta falle
        return 0;
    }
}


public function obtenerEmpresas()
{
    $consulta = "SELECT * FROM empresa";
    $resultado = mysqli_query($this->conexion, $consulta);
    
    $empresas = [];

    if ($resultado) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $empresas[] = $row;
        }
    } else {
        // Manejar el error en caso de que la consulta falle
        return [];
    }

    return $empresas;
}



    public function getStatusClientePorNombre($nombreCliente){
        $consulta = "SELECT status FROM users WHERE id > 1 AND name = '" . $nombreCliente . "'";
        $resultado = mysqli_query($this->conexion, $consulta);
    
        if ($resultado) {
            $row = $resultado->fetch_row();
            return $row ? $row[0] : null;
        } else {
            // Manejar el error en caso de que la consulta falle
            return null;
        }
    }
    

    
    public function totalActivosPorEmpresa($empresa, $status = 'activo'){
        
        $consulta = "SELECT COUNT(users.name) FROM users
        INNER JOIN empresa ON users.id_empresa = empresa.id
        WHERE users.id > 1 AND users.status = '" .  $status . "' and nombre_fantasia = '" . $empresa . "'";

        $resultado = mysqli_query($this -> conexion,$consulta);
        $row = $resultado->fetch_row();

        return $row[0];

    }

    public function totalInactivosPorEmpresa($empresa)
{
    $consulta = "SELECT COUNT(users.name) FROM users
        INNER JOIN empresa ON users.id_empresa = empresa.id
        WHERE users.id > 1 AND users.status = 'inactivo' AND nombre_fantasia = '" . $empresa . "'";

    $resultado = mysqli_query($this->conexion, $consulta);
    $row = $resultado->fetch_row();

    return $row[0];
}



    public function totalActivosSeparados(){
        
        $consulta = "SELECT e.nombre_fantasia AS nombre_empresa,
        SUM(u.status = 'activo') AS usuarios_activos,
        SUM(u.status = 'inactivo') AS usuarios_inactivos
 FROM empresa e
 LEFT JOIN users u ON e.id = u.id_empresa
 GROUP BY e.nombre_fantasia, e.id
 ORDER BY e.id ASC";

        $resultado = mysqli_query($this -> conexion,$consulta);

        $json = array();
        while($row = mysqli_fetch_array($resultado)){
            $json[] = [
               'nombre' => $row['nombre_empresa'],
               'activos' => $row['usuarios_activos'],
               'inactivos' => $row['usuarios_inactivos'],
            ];
           
        }
   
        $object = (object) $json;
   
        return $object;

    }

    public function comandos($consulta){

        try{

            $resultado = mysqli_query($this -> conexion,$consulta);

            if(!$resultado){
                die('Error de consulta'. mysqli_error($this -> conexion));
            }

            return true;
        }
        catch (\Exception $e) {
            $errores_lista[] = $e->getMessage();
            return $errores_lista;
         }

    }





}
