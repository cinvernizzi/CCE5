<?php

/**
 *
 * validapassword | seguridad/validapassword.php
 *
 * @package     CCE
 * @subpackage  Seguridad
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (08/07/2021)
 * @copyright   Copyright (c) 2021, Msal
 *
 * Método que recibe por get el password actual y la clave del
 * usuario y verifica que coincida con la declarada en la base
 * 
*/

// incluimos e instanciamos la clase
require_once ("seguridad.class.php");
$seguridad = new Seguridad();

// verificamos las credenciales
$resultado = $seguridad->validaPassword((int) $_GET["idusuario"], $_GET["password"]);

// retornamos 
echo json_encode(array("Resultado" => $resultado));

?>
