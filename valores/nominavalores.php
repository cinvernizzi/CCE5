<?php

/**
 *
 * nominavalores.php | valores/nominavalores.php
 *
 * @package     Diagnostico
 * @subpackage  Valores
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (02/08/2021)
 * @copyright   Copyright (c) 2021, INP
 *
 * Método que recibe por get la clave de una técnica y 
 * retorna el array json con la nómina completa de
 * valores de esa técnica, utilizado tanto en los combos como 
 * en la grilla del abm
 *
*/

// incluimos e instanciamos la clase
require_once("valores.class.php");
$valores = new Valores();

// obtenemos la nómina que puede recibirla por post o get
if (isset($_POST["idtecnica"])){
    $idtecnica = (int) $_POST["idtecnica"];
} elseif (isset($_GET["idtecnica"])){
    $idtecnica = (int) $_GET["idtecnica"];
}

// obtenemos la nómina
$nomina = $valores->nominaValores($idtecnica);

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("id" =>        $registro["id_valor"],
                         "valor" =>     $registro["valor"],
                         "idtecnica" => $registro["id_tecnica"],
                         "tecnica" =>   $registro["tecnica"],
                         "usuario" =>   $registro["usuario"],
                         "fecha" =>     $registro["fecha_alta"],
                         "editar" =>    "<img src='imagenes/meditar.png'>",
                         "borrar" =>    "<img src='imagenes/borrar.png'>");

}

// retornamos el vector
echo json_encode($resultado);

?>
