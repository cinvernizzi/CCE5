<?php

/**
 *
 * validadependencia/validadependencia.php
 *
 * @package     Diagnostico
 * @subpackage  Dependencias
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (13/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get el nombre de una dependencia
 * y verifica que no esté repetida, retorna el número de 
 * registros encontrados
 * 
*/

// incluimos e instanciamos la clase
require_once("dependencias.class.php");
$dependencias = new Dependencias();

// verificamos si está declarado
$registros = $dependencias->validaDependencia($_GET["dependencia"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>