<?php

/**
 *
 * nominadependencias | dependencias/nominadependencias.php
 *
 * @package     Diagnostico
 * @subpackage  Dependencias
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (13/10/2021)
 * @copyright   Copyright (c) 2017, INP
 *
 * Método que retorna el array json con la nómina de tipos
 * de dependencias
 * Utilizado tanto para cargar los combos de tipo de dependencias
 * como la grilla del abm
 *
*/

// incluimos e instanciamos la clase
require_once("dependencias.class.php");
$dependencias = new Dependencias();

// obtenemos el vector
$nomina = $dependencias->listaDependencias();

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("iddependencia" => $registro["iddependencia"],
                         "dependencia" =>   $registro["dependencia"],
                         "descripcion" =>   $registro["descripcion"],
                         "usuario" =>       $registro["usuario"],
                         "fecha" =>         $registro["fecha"],
                         "editar" =>        "<img src='imagenes/meditar.png'>",
                         "borrar" =>        "<img src='imagenes/borrar.png'>");

}

// retornamos el vector
echo json_encode($resultado);

?>