<?php

/**
 *
 * Class Estadistica | clases/Estadistica.class.php
 *
 * @package     Diagnostico
 * @subpackage  Estadistica
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     4.0 (15/11/2018)
 * @copyright   Copyright (c) 2018, INP
 *
 * Clase que contiene una serie de funciones estadísticas
 *
*/

// convención para la nomenclatura de las propiedades, comienzan con una
// letra mayúscula, de tener mas de una palabra no se utilizan separadores
// y la inicial de cada palabra va en mayúscula
// para las variables recibidas como parámetro el criterio es todas en
// minúscula

// convención para la nomenclatura de los metodos, comienzan con set o get
// según asignen un valor o lo lean y luego el nombre del valor a obtener

/**
 * Definición de la clase
 * @author Claudio Invernizzi <cinvernizzi@gmail.com>
 */
class Estadistica {

    // declaración de variables de clase

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param array $matriz - la matriz a utilizar
     * @param int $columna - la columna a utilizar
     * @return double $media - la media de la matriz
     * Método que recibe como parámetro una matriz de datos
     * y la columna con los datos numéricos, calcula la media
     * de esa matriz la que retorna formateada
     */
    public function calculaMedia($matriz, $columna){

    	// declaración de variables
    	$sumatoria = 0;

    	// recorremos el vector sumando los valores
    	for ($i = 0; $i < count($matriz) - 1; $i++){

            // lo sumamos al total
            $sumatoria += $matriz[$i][$columna];

    	}

    	// retornamos la media
    	return $sumatoria / count($matriz);

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param array $matriz - la matriz a utilizar
     * @param int $columna - la columna con los datos
     * @return double $desvio - el desvío de la muestra
     * Método que recibe como parámetro una matriz y la
     * columna con los datos numéricos, retorna el desvío
     * estandar de la muestra
     */
    public function calculaDesvio($matriz, $columna){

    	// la técnica utilizada es calcular la sumatoria de los cuadrados
    	// de las distancias con respecto a la media de cada uno de los
    	// valores y luego dividirlos por el número de elementos menos 1
    	// y calcular la raíz cuadrada

    	// declaración de variables
    	$media = 0;
    	$cuadistancias = 0;

        // primero obtenemos la media de la muestra y la convertimos
        // a un velor flotante
        $media = $this->calculaMedia($matriz, $columna);

    	// ahora recorremos la matriz sumando el cuadrado de las distancias
    	for ($i = 0; $i < count($matriz) - 1; $i++){

            // obtenemos la distancia
            $distancia = $media - $matriz[$i][$columna];

            // sumamos el cuadrado de la distancia
            $cuadistancias += pow($distancia, 2);

    	}

    	// ahora lo dividimos por el número de elementos menos 1
    	$cuadistancias = $cuadistancias / (count($matriz) - 1);

    	// obtenemos la raiz
    	return sqrt($cuadistancias);

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param array $matriz - la matriz a utilizar
     * @param int $columna - la columna con los datos
     * @return double $error - el error de la muestra
     * Método que recibe como parámetro una matriz de datos
     * y la columna con los datos numéricos, retorna el
     * error estandard de esa matriz
     */
    public function calculaError($matriz, $columna){

        // calculamos el desvío
        $desvio = $this->calculaDesvio($matriz, $columna);

        // calculamos y formateamos (recordemos que el desvió
        // lo retorna como una cadena)
        return number_format((floatval($desvio) / count($matriz)), 2, ",", ".");

    }

}
?>
