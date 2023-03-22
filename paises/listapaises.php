<?php

/**
 *
 * listapaises | paises/listapaises.php
 *
 * @package     Diagnostico
 * @subpackage  Paises
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (16/11/2021)
 * @copyright   Copyright (c) 2021, Msal
 *
 * Método que retorna el array json con la nómina de países, como tenemos
 * un formato a dos columnas convertirmos los valores de acuerdo a lo 
 * esperado por la rutina javascript
*/

// incluimos e instanciamos la clase
require_once ("paises.class.php");
$paises = new Paises();

// definimos el array
$resultado = array();

// definimos los contadores
$columna = 0;
$fila = 0;

// obtenemos el vector
$nomina = $paises->listaPaises();

// recorremos el vector
foreach($nomina AS $registro){

    //  si está en la primer columna
    if ($columna == 0){

        // agregamos un elemento al array
        $resultado[$fila] = array("id" =>      $registro["idpais"],
                                  "pais" =>    $registro["pais"],
                                  "usuario" => $registro["usuario"],
                                  "fecha" =>   $registro["fecha_alta"],
                                  "editar" =>  "<img src='imagenes/meditar.png'>",
                                  "borrar" =>  "<img src='imagenes/borrar.png'>",                                  
                                  "id1" => "",
                                  "pais1" => "",
                                  "usuario1" => "",
                                  "fecha1" => "",
                                  "editar1" => "",
                                  "borrar1" => "");

        // incrementamos la columna
        $columna = 1;
        
    // si está en la segunda columna
    } else {

        // actualizamos los elementos del array
        $resultado[$fila]["id1"] = $registro["idpais"];
        $resultado[$fila]["pais1"] = $registro["pais"];
        $resultado[$fila]["usuario1"] = $registro["usuario"];
        $resultado[$fila]["fecha1"] = $registro["fecha_alta"];
        $resultado[$fila]["editar1"] = "<img src='imagenes/meditar.png'>";
        $resultado[$fila]["borrar1"] = "<img src='imagenes/borrar.png'>";

        // fijamos la primer columna e incrementamos la fila
        $columna = 0;
        $fila++;

    }

}

// retornamos el vector
echo json_encode($resultado);

?>