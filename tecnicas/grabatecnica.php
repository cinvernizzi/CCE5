<?php

/**
 *
 * tecnicas/grabatecnica.php
 *
 * @package     Diagnostico
 * @subpackage  Tecnicas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por post los datos de una
 * técnica y ejecuta la consulta de grabación
 *
*/

// incluimos e instanciamos la clase
require_once("tecnicas.class.php");
$tecnicas = new Tecnicas();

// asignamos las propiedades
$tecnicas->setIdTecnica((int) $_POST["IdTecnica"]);
$tecnicas->setTecnica($_POST["Tecnica"]);
$tecnicas->setNombreTecnica($_POST["Nombre"]);
$tecnicas->setInmuno($_POST["Inmuno"]);
$tecnicas->setDefecto($_POST["Defecto"]);
$tecnicas->setFilas((int) $_POST["Filas"]);
$tecnicas->setColumnas((int) $_POST["Columnas"]);
$tecnicas->setIdUsuario((int) $_POST["IdUsuario"]);

// grabamos
$resultado = $tecnicas->grabaTecnica();

// retornamos
echo json_encode(array("Id" => $resultado));

?>