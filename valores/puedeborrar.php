<?php

/**
 *
 * valores/puedeborrar.php
 *
 * @package     Diagnostico
 * @subpackage  Valores
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de un valor
 * y la clave de la técnica y verifica que no esté 
 * asignado a ninguna muestra retorna la cantidad 
 * de registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("valores.class.php");
$valores = new Valores();

// verificamos si tiene registros
$registros = $valores->puedeBorrar((int) $_GET["idtecnica"], $_GET["valor"]);

// retornamos el número de registros
echo json_encode(array("Registros" => $registros));

?>