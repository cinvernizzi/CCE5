<?php

/**
 *
 * seguridad/validar.php
 *
 * @package     CCE
 * @subpackage  Seguridad
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.5.0 (23/03/2023)
 * @copyright   Copyright (c) 2018, INP
 *
 * Procedimiento que recibe por get el nombre de usuario y
 * contraseña, verifica los datos del usuario y retorna falso
 * en caso de error, si está correcto instancia la sesión y
 * retorna los valores de acceso
 *
*/

// incluímos e instanciamos la clase
require_once ("seguridad.class.php");
$seguridad = new Seguridad();

// asignamos los valores
$seguridad->setUsuario($_GET["usuario"]);
$seguridad->setPassword($_GET["password"]);

// verificamos el acceso
$estado = $seguridad->Validar();

// si no validó
if (!$estado){

    // retorna el error
    echo json_encode(array("Resultado" => false));

// si validó
} else {

    // retornamos los valores
    echo json_encode(array("Resultado" =>     true, 
                           "Id" =>            $seguridad->getId(),
                           "Responsable" =>   $seguridad->getResponsable(),
                           "Leish" =>         $seguridad->getLeish(),
                           "NivelCentral" =>  $seguridad->getNivelCentral(),
                           "IdLaboratorio" => $seguridad->getIdLaboratorio(),
                           "Jurisdiccion" =>  $seguridad->getJurisdiccion(),
                           "CodProv" =>       $seguridad->getCodProv(),
                           "Usuario" =>       $seguridad->getUsuario(),
                           "NivelCentral" =>  $seguridad->getNivelCentral()));

}
?>
