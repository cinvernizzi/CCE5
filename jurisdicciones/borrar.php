<?php

/**
 *
 * jurisdicciones/borrar.php
 *
 * @package     Diagnostico
 * @subpackage  Jurisdicciones
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la clave de un país
 * y la clave indec de la provincia y ejecuta 
 * la consulta de eliminación
 *
*/

// incluimos e instanciamos la clase
require_once("jurisdicciones.class.php");
$jurisdicciones = new Jurisdicciones();
$resultado = $jurisdicciones->borraProvincia((int) $_GET["idpais"], $_GET["idprovincia"]);

// retornamos la operación
echo json_encode(array("Resultado" => $resultado));

?>