<?php

/**
 *
 * recuperamail | usuarios/recuperamail.php
 *
 * @package     Diagnostico
 * @subpackage  Usuarios
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (25/01/2022)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la dirección de correo de un 
 * usuario y verifica que corresponda a un usuario 
 * registrado, en cuyo caso reinicia la contraseña de 
 * ingreso y retorna el array con el nombre de usuario 
 * y la nueva contraseña
 *
*/

// incluimos e instanciamos las clases
require_once ("usuarios.class.php");
$usuario = new Usuarios();

// obtenemos los registros
$resultado = $usuario->recuperaMail($_GET["mail"]);

// retornamos
echo json_encode(array("Resultado" => $resultado["resultado"],
                       "Usuario" =>   $resultado["usuario"],
                       "Password" =>  $resultado["password"]));

?>