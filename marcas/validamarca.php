<?php

/**
 *
 * marcas/validamarca.php
 *
 * @package     Diagnostico
 * @subpackage  Marcas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (19/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una técnica y el 
 * nombre de una marca y verifica que no esté declarada, 
 * retorna el número de registros encontrados
 * 
*/

// incluimos e instanciamos la clase
require_once("marcas.class.php");
$marcas = new Marcas();

// verificamos si está declarado
$registros = $marcas->validaMarca((int) $_GET["idtecnica"], $_GET["marca"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>