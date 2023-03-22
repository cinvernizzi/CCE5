<?php

/**
 *
 * nominajurisdicciones.php | jurisdicciones/nominajurisdicciones.php
 *
 * @package     Diagnostico
 * @subpackage  Jurisdicciones
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (02/08/2021)
 * @copyright   Copyright (c) 2021, INP
 * 
 * Método que recibe la clave de un país y retorna el array json 
 * con la nómina completa de jurisdicciones
 *
*/

// incluimos e instanciamos la clase
require_once("jurisdicciones.class.php");
$jurisdicciones = new Jurisdicciones();

// obtenemos los valores recibidos
$idpais = isset($_POST["idpais"]) ? intval($_POST["idpais"]) : 0;
$pagina = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;

// calculamos el offset
$offset = ($pagina - 1) * $rows;

// obtenemos el número total de registros
$registros = $jurisdicciones->numeroProvincias($idpais);

// obtenemos el vector
$nomina = $jurisdicciones->listaPaginada($idpais, $offset, $rows);

// definimos el vector
$items = array();

// agregamos el total de registros
$resultado["total"] = $registros;

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $items[] = array("id" =>          $registro["id"],
                     "idprovincia" => $registro["idprovincia"],
                     "provincia" =>   $registro["nombreprovincia"],
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