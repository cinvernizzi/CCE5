<?php

/**
 *
 * valores/grabavalor.php
 *
 * @package     Diagnostico
 * @subpackage  Valores
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por post los datos de un
 * valor de lectura y ejecuta la consulta de grabación
 *
*/

// incluimos e instanciamos la clase
require_once("valores.class.php");
$valores = new Valores();

// asignamos las propiedades
$valores->setIdValor((int) $_POST["IdValor"]);
$valores->setIdTecnicaValor((int) $_POST["IdTecnica"]);
$valores->setValor($_POST["Valor"]);
$valores->setIdUsuario((int) $_POST["IdUsuario"]);

// grabamos
$resultado = $valores->grabaValores();

// retornamos
echo json_encode(array("Id" => $resultado));

?>