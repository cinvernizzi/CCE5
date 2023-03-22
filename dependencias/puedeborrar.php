<?php

/**
 *
 * dependencias/puedeborrar.php
 *
 * @package     Diagnostico
 * @subpackage  Dependencias
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (13/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una dependencia
 * y verifica que no esté asignado a ningúna institución
 * retorna la cantidad de registros encontrados
 * 
*/

// incluimos e instanciamos la clase
require_once("dependencias.class.php");
$dependencias = new Dependencias();

// verificamos si tiene registros
$registros = $dependencias->puedeBorrar((int) $_GET["id"]);

// retornamos el número de registros
echo json_encode(array("Registros" => $registros));

?>