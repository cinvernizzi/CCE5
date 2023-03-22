<?php

/**
 *
 * localidades/borrar.php
 *
 * @package     Diagnostico
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave indec de una provincia 
 * y la clave indec de una localidad y ejecuta la consulta 
 * de eliminación
 *
*/

// incluimos e instanciamos la clase
require_once("localidades.class.php");
$localidades = new Localidades();
$resultado = $localidades->borraLocalidad($_GET["idprovincia"], $_GET["idlocalidad"]);

// retornamos la operación
echo json_encode(array("Resultado" => $resultado));

?>