<?php

/**
 *
 * marcas/puedeborrar.php
 *
 * @package     Diagnostico
 * @subpackage  Marcas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (19/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una marca
 * y verifica que no esté asignado a ningún paciente
 * retorna la cantidad de registros encontrados
 * 
*/

// incluimos e instanciamos la clase
require_once("marcas.class.php");
$marcas = new Marcas();

// verificamos si tiene registros
$registros = $marcas->puedeBorrar((int) $_GET["id"]);

// retornamos el número de registros
echo json_encode(array("Registros" => $registros));

?>