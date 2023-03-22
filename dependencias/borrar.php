<?php

/**
 *
 * dependencias/borrar.php
 *
 * @package     Diagnostico
 * @subpackage  Dependencias
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (13/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una dependencia
 * y ejecuta la consulta de eliminación
 *
*/

// incluimos e instanciamos la clase
require_once("dependencias.class.php");
$dependencias = new Dependencias();
$resultado = $dependencias->borraDependencia((int) $_GET["id"]);

// retornamos la operación
echo json_encode(array("Resultado" => $resultado));

?>