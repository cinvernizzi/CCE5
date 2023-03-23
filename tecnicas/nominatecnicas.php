<?php

/**
 *
 * nominatecnicas.php | tecnicas/nominatecnicas.php
 *
 * @package     Diagnostico
 * @subpackage  Tecnicas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (02/08/2021)
 * @copyright   Copyright (c) 2021, INP
 *
 * Método que retorna el array json con la nómina completa de
 * tecnicas, utilizado tanto en los combos como en la grilla
 * del abm
 *
*/

// incluimos e instanciamos la clase
require_once("tecnicas.class.php");
$tecnicas = new Tecnicas();

// obtenemos la nómina
$nomina = $tecnicas->nominaTecnicas();

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("idtecnica" =>  $registro["id_tecnica"],
                         "tecnica" =>    $registro["tecnica"],
                         "nombre" =>     $registro["nombre"],
                         "inmuno" =>     $registro["inmuno"],
                         "defecto" =>    $registro["defecto"],
                         "filas" =>      $registro["filas"],
                         "columnas" =>   $registro["columnas"],
                         "usuario" =>    $registro["usuario"],
                         "fecha" =>      $registro["fecha_alta"],
                         "editar" =>     "<img src='imagenes/meditar.png'>",
                         "borrar" =>     "<img src='imagenes/borrar.png'>");

}

// retornamos el vector
echo json_encode($resultado);

?>
