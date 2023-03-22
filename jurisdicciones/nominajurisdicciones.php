<?php

/**
 *
 * nominajurisdicciones.php | jurisdicciones/nominajurisdicciones.php
 *
 * @package     Seroteca
 * @subpackage  Jurisdicciones
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (02/08/2021)
 * @copyright   Copyright (c) 2021, INP
 * 
 * Método que recibe la clave de un país y retorna el array json 
 * con la nómina completa de jurisdicciones
 *
*/

// incluimos e instanciamos la clase
require_once("jurisdicciones.class.php");
$jurisdicciones = new Jurisdicciones();

// obtenemos la nómina
$nomina = $jurisdicciones->listaProvincias((int) $_GET["idpais"]);

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("id" =>          $registro["id"],
                         "idprovincia" => $registro["idprovincia"],
                         "provincia" =>   $registro["provincia"],
                         "poblacion" =>   $registro["poblacion"],
                         "fecha" =>       $registro["fecha_alta"],
                         "usuario" =>     $registro["usuario"],
                         "editar" =>      "<img src='imagenes/meditar.png'>",
                         "borrar" =>      "<img src='imagenes/borrar.png'>");

}

// retornamos el vector
echo json_encode($resultado);

?>