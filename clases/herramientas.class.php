<?php

/**
 *
 * Class Herramientas | clases/herramientas.class.php
 *
 * @package     CCE
 * @subpackage  Clases
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.4.0 (16/08/2018)
 * @copyright   Copyright (c) 2018, INP
 *
 * Clase que implemente una serie de procedimientos utilizados
 * por todo el sistema
 *
*/

// declaramos el tipeo estricto
declare (strict_types=1);

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
class Herramientas {

    // aquí no tenemos un constructor ni variables de clase

    /**
     * Método que recibe como parámetro la fecha y retorna una
     * cadena con la fecha en letras
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $fecha en formato dd/mm/YYYY
     * @return string
     */
    public function fechaLetras(string $fecha) : string {

        // declaración de variables
        $mesLetras = "";

        // verificamos la longitud de la cadena
        if (strlen($fecha) != 10){

            // abandona por error
            echo $fecha;
            echo "La cadena es incorrecta, debe ser dd/mm/YYYY";
            exit;

        }

        // obtenemos el día
        $dia = substr($fecha, 0, 2);

        // si el día no es numérico
        if (!is_numeric($dia)){

            // abandona por error
            echo "El día debe ser un número";
            exit;

        }

        // obtenemos el mes
        $mes = substr($fecha, 3, 2);

        // si el mes no es numérico
        if (!is_numeric($mes)){

            // abandona por error
            echo "El mes debe ser un número";
            exit;

        }

        // obtenemos el año
        $anio = substr($fecha, 6, 4);

        // si el año no es un número
        if (!is_numeric($anio)){

            // abandona por error
            echo "El año debe ser un número";
            exit;

        }

        // convertimos el mes a número para eliminar el 0
        $mes = intval($mes);

        // verificamos que el mes se encuentre entre 1 y 12
        if ($mes < 1 || $mes > 12){

            // abandona por error
            echo "El mes debe estar comprendido entre 1 y 12";
            exit;

        }

        // convertimos el día a número
        $dia = intval($dia);

        // verificamos que el día no sea mayor de 31
        if ($dia < 1 || $dia > 31){

            // abandona por error
            echo "El día no puede ser mayor de 31";
            exit;

        }

        // según el mes verificamos que el día no sea mayor de 30
        if (($mes == 4 || $mes == 6 || $mes == 9 || $mes == 10) && $dia > 30 ){

            // abandona por error
            echo "Algunos meses solo pueden tener 30 días";
            exit;

        }

        // si es febrero verificamos que el día no sea mayor de
        // 28 o 29 si el año es biciesto
        if ($mes == 2){

            // si es biciesto
            if ($anio % 4 == 0){

                // verifica que no sea mayor de 29
                if ($dia > 29){

                    // abandona por error
                    echo "Febrero solo puede tener 29 días";
                    exit;

                }

            // si no es biciesto
            } else {

                // verifica que no sea mayor de 28
                if ($dia > 28){

                    // abandona por error
                    echo "Febrero solo puede tener 28 días";
                    exit;

                }

            }

        }

        // según el mes convertimos a letras
        switch ($mes){

            // según el mes
            case 1:
                $mesLetras = "Enero";
                break;
            case 2:
                $mesLetras = "Febrero";
                break;
            case 3:
                $mesLetras = "Marzo";
                break;
            case 4:
                $mesLetras = "Abril";
                break;
            case 5:
                $mesLetras = "Mayo";
                break;
            case 6:
                $mesLetras = "Junio";
                break;
            case 7:
                $mesLetras = "Julio";
                break;
            case 8:
                $mesLetras = "Agosto";
                break;
            case 9:
                $mesLetras = "Septiembre";
                break;
            case 10:
                $mesLetras = "Octubre";
                break;
            case 11:
                $mesLetras = "Noviembre";
                break;
            case 12:
                $mesLetras = "Diciembre";
                break;
            default:
                $mesLetras = "Desconocido";
                break;

        }

        // componemos la cadena
        return $dia . " de " . $mesLetras . " de " . $anio;

    }

    /**
     * Método que recibe como parámetro una cadena en formato
     * dd/mm/YY y retorna el día de la semana correspondiente
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $cadena - la cadena a convertir
     * @return string el día de la semana
     */
    public function diaSemana(string $cadena) : string {

        // primero convertimos la cadena a un objeto fecha
        $fecha = date_create_from_format("d/m/Y", $cadena);

        // definimos el array con los días de la semana
        $semana = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");

        // ahora obtenemos el número del día
        $dia = date('w', strtotime(date_format($fecha, 'Y/m/d')));

        // ahora retornamos el día en letras
        return $semana[$dia];

    }

    /**
     * Método que recibe como parámetros dos números y retorna el
     * porcentaje de esos dos números formateado
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $mayor - el primer número
     * @param int $menor - el segundo número
     * @return string $porcentaje
     */
    public function Porcentaje(int $mayor, int $menor) : string {

        // verifica que los dos sean un número
        if (!is_numeric($mayor)){

            // abandona por error
            echo "El primer valor debe ser un número";
            exit;

        // si el segundo no es un número
        } elseif (!is_numeric($menor)){

            // abandona por error
            echo "El segundo valor debe ser un número";
            exit;

        }

        // calculamos el porcentaje y lo formateamos
        return number_format((($menor / $mayor) * 100), 2) + " %";

    }

    /**
     * Método que genera una contraseña aleatoria
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return string contraseña generada
     */
    public function generaPass(){

        // Se define una cadena de caractares desordenada para mejorar
        $cadena = "qrstuvPQRSTDEFGHa6789bcdIJKABCLMNOUVWXYZefghijklmnopwxyz123450";

        //Obtenemos la longitud de la cadena de caracteres
        $longitudCadena=strlen($cadena);

        // Definimos la variable que va a contener la contraseña
        $pass = "";

        // Se define la longitud de la contraseña
        $longitudPass=8;

        //Creamos la contraseña recorriendo la cadena
        for($i=1 ; $i<=$longitudPass ; $i++){

            // Definimos numero aleatorio entre 0 y la longitud de
            // la cadena de caracteres-1
            $pos=rand(0,$longitudCadena-1);

            //Vamos formando la contraseña con cada carácter aleatorio.
            $pass .= substr($cadena,$pos,1);

        }

        // retornamos
        return $pass;

    }

}
?>
