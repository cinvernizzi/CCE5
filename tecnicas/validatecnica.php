<?php

/**
 *
 * tecnicas/validatecnica.php
 *
 * @package     Diagnostico
 * @subpackage  Tecnicas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get nombre de una técnica
 * y verifica que no esté repetido, retorna el número de
 * registros encontrados
 *
*/

// incluimos e instanciamos la clase
require_once("tecnicas.class.php");
$tecnicas = new Tecnicas();

// verificamos si está declarado
$registros = $tecnicas->verificaTecnica($_GET["tecnica"]);

// retornamos
echo json_encode(array("Registros" => $registros));

?>