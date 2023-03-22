<?php

/**
 *
 * nominalocalidades.php | localidades/nominalocalidades.php
 *
 * @package     Seroteca
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (02/08/2021)
 * @copyright   Copyright (c) 2021, INP
 * 
 * Método que recibe por get la clave de una provincia 
 * y retorna el array json con la nómina completa de 
 * localidades
 *
*/

// incluimos e instanciamos la clase
require_once("localidades.class.php");
$localidades = new Localidades();

// obtenemos la nómina
$nomina = $localidades->listaLocalidades($_GET["codprov"]);

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("id" =>          $registro["id"],
                         "idlocalidad" => $registro["idlocalidad"],
                         "localidad" =>   $registro["localidad"],
                         "provincia" =>   $registro["provincia"],
                         "codpcia" =>     $registro["codpcia"],
                         "poblacion" =>   $registro["poblacion"],
                         "fecha" =>       $registro["fecha_alta"],
                         "usuario" =>     $registro["usuario"],
                         "editar" =>      "<img src='imagenes/meditar.png'>",
                         "borrar" =>      "<img src='imagenes/borrar.png'>");

}

// retornamos el vector
echo json_encode($resultado);

?>