<?php

/**
 *
 * buscar.php | localidades/buscar.php
 *
 * @package     Diagnostico
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (29/10/2021)
 * @copyright   Copyright (c) 2021, INP
 * 
 * Método que recibe por get parte del nombre de una localidad
 * y retorna el array json con los registros coincidentes
 *
*/

// incluimos e instanciamos la clase
require_once("localidades.class.php");
$localidades = new Localidades();

// obtenemos la nómina
$nomina = $localidades->nominaLocalidades($_GET["localidad"]);

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("codloc" =>    $registro["codloc"],
                         "pais" =>      $registro["pais"],
                         "idpais" =>    $registro["idpais"],
                         "provincia" => $registro["provincia"],
                         "localidad" => $registro["localidad"],
                         "editar" =>    "<img src='imagenes/meditar.png'>");

}

// retornamos el vector
echo json_encode($resultado);