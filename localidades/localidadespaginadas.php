<?php

/**
 *
 * localidadespaginadas.php | localidades/localidadespaginadas.php
 *
 * @package     Diagnostico
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (15/11/2021)
 * @copyright   Copyright (c) 2021, INP
 * 
 * Método que recibe por post la clave de una provincia 
 * el número de página y el número de registros a 
 * retornar y devuelve el array json con la nómina de 
 * localidades
 *
*/

// incluimos e instanciamos la clase
require_once("localidades.class.php");
$localidades = new Localidades();

// obtenemos los valores recibidos
$codprov = isset($_POST["codprov"]) ? $_POST["codprov"] : "";
$pagina = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;

// calculamos el offset
$offset = ($pagina - 1) * $rows;

// obtenemos el número total de registros
$registros = $localidades->numeroLocalidades($codprov);

// obtenemos el vector
$nomina = $localidades->listaPaginada($codprov, $offset, $rows);

// definimos el vector
$items = array();

// agregamos el total de registros
$resultado["total"] = $registros;

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $items[] = array("id" =>          $registro["id"],
                     "idlocalidad" => $registro["idlocalidad"],
                     "localidad" =>   $registro["localidad"],
                     "provincia" =>   $registro["provincia"],
                     "codpcia" =>     $registro["codpcia"],
                     "poblacion" =>   $registro["poblacion"],
                     "fecha" =>       $registro["fecha_alta"],
                     "usuario" =>     $registro["usuario"],
                     "editar" =>      "<img src='imagenes/meditar.png'>",
                     "borrar" =>      "<img src='imagenes/borrar.png'>");

}

// agregamos el vector
$resultado["rows"] = $items;

// retornamos el vector
echo json_encode($resultado);

?>