<?php

/**
 *
 * jurisdicciones/validajurisdiccion.php
 *
 * @package     Diagnostico
 * @subpackage  Jurisdicciones
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de un país y la 
 * el nombre de una provincia y verifica que no esté repetido, 
 * retorna el número de registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("jurisdicciones.class.php");
$jurisdicciones = new Jurisdicciones();

// verificamos si está declarado
$registros = $jurisdicciones->validaJurisdiccion($_GET["provincia"], (int) $_GET["idpais"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>