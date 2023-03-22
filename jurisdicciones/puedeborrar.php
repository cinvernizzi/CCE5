<?php

/**
 *
 * jurisdicciones/puedeborrar.php
 *
 * @package     Diagnostico
 * @subpackage  Jurisdicciones
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de un país
 * y la clave indec de una provincia y verifica que no esté 
 * asignado a ningún paciente o institución, retorna la 
 * cantidad de registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("jurisdicciones.class.php");
$jurisdicciones = new Jurisdicciones();

// verificamos si tiene registros
$registros = $jurisdicciones->puedeBorrar((int) $_GET["idpais"], $_GET["idprovincia"]);

// retornamos el número de registros
echo json_encode(array("Registros" => $registros));

?>