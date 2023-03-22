<?php

/**
 *
 * nominapaises.php | paises/nominapaises.php
 *
 * @package     Seroteca
 * @subpackage  Paises
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (02/08/2021)
 * @copyright   Copyright (c) 2021, INP
 *
 * Método que retorna el array json con la nómina completa de
 * países, utilizado tanto en los combos como en la grilla
 * del abm
 *
*/

// incluimos e instanciamos la clase
require_once("paises.class.php");
$paises = new Paises();

// obtenemos la nómina
$nomina = $paises->listaPaises();

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("idpais" => $registro["idpais"],
                         "pais" =>   $registro["pais"],
                         "usuario" =>  $registro["usuario"],
                         "fecha" =>    $registro["fecha_alta"],
                         "editar" =>   "<img src='imagenes/meditar.png'>",
                         "borrar" =>   "<img src='imagenes/borrar.png'>");

}

// retornamos el vector
echo json_encode($resultado);

?>