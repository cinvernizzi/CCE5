<?php

/**
 *
 * Class Técnicas | tecnicas/tecnicas.class.php
 *
 * @package     CCE
 * @subpackage  Técnicas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.5.0 (23/03/2023)
 * @copyright   Copyright (c) 2018, INP
 *
 * Cláse que contiene las operaciones sobre la base de datos
 * de técnicas
 *
*/

// declaramos el tipeado estricto
declare(strict_types=1);

// inclusión de archivos
require_once ("../clases/conexion.class.php");

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
class Tecnicas {

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos
    protected $IdTecnica;                // clave del registro
    protected $Tecnica;                  // nombre de la técnica
    protected $IdUsuario;                // clave del usuario
    protected $Usuario;                  // nombre del usuario

    // definición de variables
    protected $Link;                     // puntero a la base de datos

    // constructor de la clase
    function __construct (){

        // nos conectamos a la base de datos
        $this->Link = new Conexion();

        // inicializamos las variables de clase
        $this->IdTecnica = 0;
        $this->Tecnica = "";
        $this->IdUsuario = 0;
        $this->Usuario = "";

    }

    // destructor de la clase
    function __destruct(){

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos de asignación de valores
    public function setIdTecnica(int $idtecnica){
        $this->IdTecnica = $idtecnica;
    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }
    public function setTecnica(string $tecnica){
        $this->Tecnica = $tecnica;
    }

    // métodos de retorno de valores
    public function getIdTecnica() : int {
        return (int) $this->IdTecnica;
    }
    public function getIdUsuario() : int {
        return (int) $this->IdUsuario;
    }
    public function getTecnica() : string {
        return $this->Tecnica;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }

    /**
     * Método que retorna la id y el nombre de cada una de las
     * técnicas, utilizado en los combos y en las grillas de
     * abm de técnicas
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return array
     */
    public function nominaTecnicas() : array {

        // componemos la consulta
        $consulta = "SELECT cce.tecnicas.TECNICA AS tecnica,
                            cce.tecnicas.ID AS id_tecnica,
                            DATE_FORMAT(cce.tecnicas.FECHA_ALTA, '%d/%m/%Y') AS fecha_alta,
                            cce.responsables.USUARIO AS usuario
                     FROM cce.tecnicas INNER JOIN cce.responsables ON cce.tecnicas.USUARIO = cce.responsables.ID;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro la cadena con el
     * nombre de la técnica y retorna su clave
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tecnica - nombre de la técnica
     * @return int
     */
    public function getClaveTecnica($tecnica){

        // componemos la consulta
        $consulta = "SELECT cce.tecnicas.ID AS idtecnica
                     FROM cce.tecnicas
                     WHERE cce.tecnicas.TECNICA = '$tecnica';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $registro = $resultado->fetch(PDO::FETCH_ASSOC);
        return $registro["idtecnica"];

    }

    /**
     * Método que graba los datos del registro en la base de datos
     * retorna la clave del registro afectado
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return int
     */
    public function grabaTecnica(){

        // si es un alta
        if ($this->IdTecnica == 0) {
            $this->nuevaTecnica();
        } else {
            $this->editaTecnica();
        }

        // retornamos la id
        return $this->IdTecnica;

    }

    /**
     * Método que inserta un nuevo registro en la tabla de técnicas
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function nuevaTecnica(){

        // compone la consulta de inserción
        $consulta = "INSERT INTO cce.tecnicas
                            (TECNICA,
                            USUARIO)
                            VALUES
                            (:tecnica,
                            :idusuario);";

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asignamos los parámetros de la consulta
        $psInsertar->bindParam(":tecnica", $this->Tecnica);
        $psInsertar->bindParam(":idusuario", $this->IdUsuario);

        // ejecutamos la edición
        $resultado = $psInsertar->execute();

        // si salió todo bien
        if ($resultado){

            // obtiene la id del registro insertado
            $this->IdTecnica = $this->Link->lastInsertId();

        // si hubo un error
        } else {

            // inicializa la clave y muestra el error
            $this->IdTecnica = 0;
            echo $resultado;

        }

    }

    /**
     * Método que edita el registro de la tabla de técnicas
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function editaTecnica(){

        // compone la consulta de actualización
        $consulta = "UPDATE cce.tecnicas SET
                            TECNICA = :tecnica,
                            USUARIO = :idusuario
                     WHERE tecnicas.ID = :idtecnica;";

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asignamos los parámetros de la consulta
        $psInsertar->bindParam(":tecnica", $this->Tecnica);
        $psInsertar->bindParam(":idusuario", $this->IdUsuario);
        $psInsertar->bindParam(":idtecnica", $this->IdTecnica);

        // ejecutamos la edición
        $resultado = $psInsertar->execute();

        // si hubo un error
        if (!$resultado){

            // inicializa la clave y muestra el error
            $this->IdTecnica = 0;
            echo $resultado;

        }

    }

    /**
     * Método que recibe como parámetro el nombre de una técnica, retorna
     * el número de registros encontrados, usado en el alta de técnicas
     * para evitar repeticiones
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tecnica - nombre de la técnica
     * @return int
     */
    public function verificaTecnica($tecnica){

        // componemos la consulta
        $consulta = "SELECT COUNT(*) AS registros
                     FROM cce.tecnicas
                     WHERE cce.tecnicas.TECNICA = '$tecnica';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $registro = $resultado->fetch(PDO::FETCH_ASSOC);
        return $registro["registros"];

    }

}
