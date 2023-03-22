<?php

/**
 *
 * localidades/puedeborrar.php
 *
 * @package     Diagnostico
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave indec de una provincia 
 * y la clave indec de la localidad y verifica que no esté 
 * asignado a ningún paciente o institución retorna la 
 * cantidad de registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("localidades.class.php");
$localidades = new Localidades();

// verificamos si tiene registros
$registros = $localidades->puedeBorrar($_GET["idprovincia"], $_GET["idlocalidad"]);

// retornamos el número de registros
echo json_encode(array("Registros" => $registros));

?>