<?php

/**
 *
 * enviamail | seguridad/enviamail.php
 *
 * @package     Diagnostico
 * @subpackage  Seguridad
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (24/03/2023)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que recibe por get la dirección de correo de un usuario
 * la contraseña generada aleatoreamente y la dirección de correo
 * importa la plantilla y envía el correo con los datos de 
 * conexión
 *
*/

// importamos la clase plantilla que contiene la configuración 
// del correo y solo debemos reemplazar el texto del mensaje
require_once("../correos/plantillacorreo.class.php");
$correo = new Plantilla();

// definimos el cuerpo del mensaje
$mensaje = "<p style='text-align:justify;'>";
$mensaje .= "Recientemente usted ha solicitado la recuperación de la contraseña 
             de acceso a la Plataforma de Control de Calidad Externo, a continuación, 
             le informamos que los datos de conexión son los siguientes:</p> ";
$mensaje .= "<p>Usuario: " . $_GET["usuario"] . "<br>";
$mensaje .= "Contraseña: " . $_GET["password"] . "</p>";
$mensaje .= "<p style='text-align: justify;'>";
$mensaje .= "Al ingresar podrá actualizar la contraseña, por una solo conocida
             por usted.</p>";
$mensaje .= "<p style='text-align: justify;'>";
$mensaje .= "No olvide que ante cualquier inconveniente puede comunicarse
             a cce.inp@outlook.com.ar o diagnostico.inp.anlis@gmail.com donde intentaremos
             resolver sus dificultades.</p>";

// ahora reemplazamos en la plantilla el texto 
$correo->setMensaje($mensaje);

// fijamos el destinatario
$correo->addAddress($_GET["mail"]);

// enviamos el mensaje
try {
    $correo->send();
    $resultado = true;
} catch (Exception $e) {
    $resultado = false;
}

// retornamos
echo json_encode(array("Resultado" => $resultado));

?>
