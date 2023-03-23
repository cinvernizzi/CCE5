<?php

/**
 *
 * tecnicas/borrar.php
 *
 * @package     Diagnostico
 * @subpackage  Tecnicas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una técnica
 * y ejecuta la consulta de eliminación
 *
*/

// incluimos e instanciamos la clase
require_once("tecnicas.class.php");
$tecnicas = new Tecnicas();
$resultado = $tecnicas->borraTecnica((int) $_GET["id"]);

// retornamos la operación
echo json_encode(array("Resultado" => $resultado));

?>