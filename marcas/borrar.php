<?php

/**
 *
 * marcas/borrar.php
 *
 * @package     Diagnostico
 * @subpackage  Marcas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (19/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una marca
 * y ejecuta la consulta de eliminación
 *
*/

// incluimos e instanciamos la clase
require_once("marcas.class.php");
$marcas = new Marcas();
$resultado = $marcas->borraMarca((int) $_GET["id"]);

// retornamos la operación
echo json_encode(array("Resultado" => $resultado));

?>