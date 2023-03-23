<?php

/**
 *
 * nominamarcas.php | marcas/nominamarcas.php
 *
 * @package     Diagnostico
 * @subpackage  Marcas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (19/10/2021)
 * @copyright   Copyright (c) 2021, INP
 * 
 * Método que recibe la clave de una técnica y retorna el 
 * array con la nómina de marcas de esa técnica, 
 * utilizado tanto en los combos como en la grilla del abm
 *
*/

// incluimos e instanciamos la clase
require_once("marcas.class.php");
$marcas = new Marcas();

// obtenemos la nómina verificando si recibió 
// por post o por get
if (isset($_GET["idtecnica"])){
    $idtecnica = $_GET["idtecnica"];
} elseif (isset($_POST["idtecnica"])){
    $idtecnica = $_POST["idtecnica"];
}
$nomina = $marcas->nominaMarcas((int) $idtecnica);

// definimos el vector
$resultado = array();

// vamos a recorrer el vector para agregar la imagen
foreach($nomina AS $registro){

    // agregamos la fila al vector
    $resultado[] = array("id" =>        $registro["id_marca"],
                         "marca" =>     $registro["marca"],
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
