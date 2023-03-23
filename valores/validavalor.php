<?php

/**
 *
 * valores/validavalor.php
 *
 * @package     Diagnostico
 * @subpackage  Valores
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de una técnica y 
 * el valor y verifica que no esté repetido, retorna el número de
 * registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("valores.class.php");
$valores = new Valores();

// verificamos si está declarado
$registros = $valores->verificaValor((int) $_GET["idtecnica"], $_GET["valor"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>