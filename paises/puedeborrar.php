<?php

/**
 *
 * paises/puedeborrar.php
 *
 * @package     Diagnostico
 * @subpackage  Paises
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de un país
 * y verifica que no esté asignado a ningún paciente
 * o institución, retorna la cantidad de registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("paises.class.php");
$paises = new Paises();

// verificamos si tiene registros
$registros = $paises->puedeBorrar((int) $_GET["id"]);

// retornamos el número de registros
echo json_encode(array("Registros" => $registros));

?>