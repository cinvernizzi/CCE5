<?php

/**
 *
 * tecnicas/puedeborrar.php
 *
 * @package     Diagnostico
 * @subpackage  Tecnicas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una técnica
 * y verifica que no esté asignado a ninguna muestra
 * retorna la cantidad de registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("tecnicas.class.php");
$tecnicas = new Tecnicas();

// verificamos si tiene registros
$registros = $tecnicas->puedeBorrar((int) $_GET["id"]);

// retornamos el número de registros
echo json_encode(array("Registros" => $registros));

?>