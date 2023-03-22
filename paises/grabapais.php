<?php

/**
 *
 * paises/grabapais.php
 *
 * @package     Diagnostico
 * @subpackage  Paises
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por post los datos de un
 * país y ejecuta la consulta de grabación
 *
*/

// incluimos e instanciamos la clase
require_once("paises.class.php");
$paises = new Paises();

// asignamos las propiedades
$paises->setIdPais((int) $_POST["IdPais"]);
$paises->setNombrePais($_POST["Pais"]);
$paises->setIdUsuario((int) $_POST["IdUsuario"]);

// grabamos
$resultado = $paises->grabaPais();

// retornamos
echo json_encode(array("Id" => $resultado));

?>