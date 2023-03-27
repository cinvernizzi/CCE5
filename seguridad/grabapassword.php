<?php

/**
 *
 * grabapassword | seguridad/grabapassword.php
 *
 * @package     CCE
 * @subpackage  Seguridad
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (26/03/2023)
 * @copyright   Copyright (c) 2021, INP
 *
 * Método que recibe por get la clave del usuario y el nuevo
 * password y ejecuta la consulta de actualización
 * 
*/

// incluimos e instanciamos la clase
require_once ("seguridad.class.php");
$seguridad = new Seguridad();

// asignamos en la clase
$seguridad->setId((int) $_GET["idusuario"]);
$seguridad->setNuevoPass($_GET["password"]);

// actualizamos
$seguridad->nuevoPassword();

// si hubo algún error
if ($seguridad->getId() != 0){
    $resultado = true;
} else {
    $resultado = false;
}

// retornamos 
echo json_encode(array("Resultado" => $resultado));

?>
