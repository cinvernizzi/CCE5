<?php

/**
 *
 * localidades/grabalocalidad.php
 *
 * @package     Diagnostico
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (14/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por post los datos de una localdidad
 * y el tipo de evento (edición o inserción) y ejecuta la 
 * consulta de grabación
 *
*/

// incluimos e instanciamos la clase
require_once("localidades.class.php");
$localidades = new Localidades();

// asignamos las propiedades
$localidades->setIdProvincia($_POST["IdProvincia"]);
$localidades->setNombreLocalidad($_POST["Localidad"]);
$localidades->setIdLocalidad($_POST["IdLocalidad"]);
$localidades->setPoblacionLocalidad((int) $_POST["Poblacion"]);
$localidades->setIdUsuario((int) $_POST["IdUsuario"]);

// fijamos el evento
if ((int) $_POST["Id"] != 0){
    $evento = "editar";
} else {
    $evento = "insertar";
}

// grabamos
$resultado = $localidades->grabaLocalidad($evento);

// retornamos
echo json_encode(array("Resultado" => $resultado));

?>