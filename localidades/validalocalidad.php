<?php

/**
 *
 * localidades/validalocalidad.php
 *
 * @package     Diagnostico
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get el nombre de una localidad y 
 * la clave indec de una provincia y verifica que no esté 
 * repetido, retorna el número de registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("localidades.class.php");
$localidades = new Localidades();

// verificamos si está declarado
$registros = $localidades->validalocalidad($_GET["idprovincia"], $_GET["localidad"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>