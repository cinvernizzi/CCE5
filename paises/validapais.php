<?php

/**
 *
 * validapais/validapais.php
 *
 * @package     Diagnostico
 * @subpackage  Paises
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get nombre de un país
 * y verifica que no esté repetido, retorna el número de
 * registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("paises.class.php");
$paises = new Paises();

// verificamos si está declarado
$registros = $paises->validaPais($_GET["pais"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>