<?php

/**
 *
 * jurisdicciones/verificaindec.php
 *
 * @package     Diagnostico
 * @subpackage  Jurisdicciones
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (24/02/2023)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave indec de un alta y 
 * verifica que no se encuentre repetida
 *
*/

// incluimos e instanciamos la clase
require_once("jurisdicciones.class.php");
$jurisdicciones = new Jurisdicciones();

// verificamos si está declarado
$registros = $jurisdicciones->verificaIndec($_GET["clave"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>
