<?php

/**
 *
 * marcas/grabamarca.php
 *
 * @package     Diagnostico
 * @subpackage  Marcas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (19/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por post los datos de una
 * marca y ejecuta la consulta de grabación
 * 
*/

// incluimos e instanciamos la clase
require_once("marcas.class.php");
$marcas = new Marcas();

// asignamos las propiedades
$marcas->setMarca($_POST["Marca"]);
$marcas->setIdTecnica((int) $_POST["IdTecnica"]);
$marcas->setIdUsuario((int) $_POST["IdUsuario"]);
$marcas->setIdMarca((int) $_POST["IdMarca"]);

// grabamos
$resultado = $marcas->grabaMarca();

// retornamos
echo json_encode(array("Id" => $resultado));

?>