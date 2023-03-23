<?php

/**
 *
 * valores/borrar.php
 *
 * @package     Diagnostico
 * @subpackage  Valores
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de un valor
 * y ejecuta la consulta de eliminación
 *
*/

// incluimos e instanciamos la clase
require_once("valores.class.php");
$valores = new Valores();
$resultado = $valores->borraValor((int) $_GET["id"]);

// retornamos la operación
echo json_encode(array("Resultado" => $resultado));

?>