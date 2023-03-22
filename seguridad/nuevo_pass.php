<?php

/**
 *
 * seguridad/nuevo_pass.php
 *
 * @package     CCE
 * @subpackage  Seguridad
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.5.0 (22/03/2023)
 * @copyright   Copyright (c) 2018, INP
 *
 * Procedimiento que recibe la contraseña actual, la nueva y
 * la id del usuario y actualiza la contraseña retorna el estado 
 * de la operación
*/

// inclusión de archivos
require_once ("seguridad.class.php");
require_once ("../responsables/responsables.class.php");
require_once ("../correos/plantillacorreo.class.php");

// instancia la clase
$seguridad = new Seguridad();
$responsable = new Responsables();

// setea los valores recibidos por post
$seguridad->setPassword($_POST["password"]);
$seguridad->setNuevoPass($_POST["nuevo_password"]);
$seguridad->setId((int) $_POST["id"]);

// cambia el password
$resultado = $seguridad->NuevoPassword();

// obtenemos los datos del usuario
$responsable->getResponsable((int) $_POST["id"]);

// ahora le enviamos un correo de confirmación al usuario
$mensaje = "<h2>Actualización de Contraseña</h2>";
$mensaje .= "<p>Estimado/a " . $responsable->getNombre() . "<br>";
$mensaje .= $responsable->getInstitucion() . "</p>";
$mensaje .= "<p align='justify'>";
$mensaje .= "Recientemente usted ha actualizado su contraseña ";
$mensaje .= "de acceso al Sistema de Control de Calidad de Laboratorios de ";
$mensaje .= "Chagas, a continuación, le confirmamos sus nuevos datos de conexión:</p>";
$mensaje .= "<p>Usuario: " . $responsable->getUsuario() . "<br>";
$mensaje .= "Contraseña: " . $_GET["nuevo_password"] . "</p>";
$mensaje .= "<p align='justify'>";
$mensaje .= "Le recomendamos conservar este correo como recordatorio de sus ";
$mensaje .= "credenciales de ingreso.</p>";
$mensaje .= "<p align='justify'>";
$mensaje .= "Recuerde que la web de Control de Calidad Externo se encuentra ";
$mensaje .= "disponible en <a href: http://fatalachaben.mooo.com/calidad/>";
$mensaje .= "http://fatalachaben.mooo.com/calidad</a>.<br></p>";
$mensaje .= "<p align='justify'>";
$mensaje .= "No olvide que ante cualquier inconveniente puede comunicarse ";
$mensaje .= "a <a mailto: cinvernizzi@gmail.com>cinvernizzi@gmail.com</a> o ";
$mensaje .= "<a mailto: diagnostico.inp.anlis@gmail.com>diagnostico.inp.anlis@gmail.com</a> ";
$mensaje .= "donde intentaremos resolver sus dificultades. </p>";

// instanciamos el objeto
$mail = new Plantilla();

// destinatario del mensaje
$mail->addAddress($responsable->getMail(), $responsable->getNombre());

// cargamos el contenido del texto
$mail->setMensaje($mensaje);

// enviamos el mensaje
$mail->send();

// retornamos
echo json_encode(array("Resultado" => $resultado));

?>
