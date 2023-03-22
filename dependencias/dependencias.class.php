<?php

/**
 *
 * Class Dependencias | dependencias/dependencias.class.php
 *
 * @package     Diagnostico
 * @subpackage  Dependencias
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.1.0 (09/12/2016)
 * @copyright   Copyright (c) 2017, INP
 *
 */

// declaramos el tipeado estricto
declare (strict_types=1);

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
 * Clase que controla las operaciones sobre el diccionario de
 * dependencias de las instituciones
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 */
class Dependencias {

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos
    protected $IdDependencia;         // clave de la dependencia
    protected $Dependencia;           // nombre de la dependencia
    protected $Descripcion;           // abreviatura de la dependencia
    protected $IdUsuario;             // clave del usuario
    protected $Usuario;               // nombre del usuario
    protected $FechaAlta;             // fecha de alta del registro
    protected $Link;                  // puntero a la base de datos

    /**
     * Constructor de la clase, establece la conexión con la base
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __construct() {

        // inicializamos las variables
        $this->IdDependencia = 0;
        $this->Dependencia   = "";
        $this->Descripcion = "";
        $this->Usuario = "";
        $this->FechaAlta = "";
        $this->IdUsuario = 0;

        // nos conectamos a la base de datos
        $this->Link = new Conexion();

    }

    /**
     * Destructor de la clase, cierra la conexión con la base
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __destruct() {

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos de asignación de variables
    public function setIdDependencia(int $iddependencia) {
        $this->IdDependencia = $iddependencia;
    }
    public function setDependencia(string $dependencia) {
        $this->Dependencia = $dependencia;
    }
    public function setDescripcion(string $descripcion){
        $this->Descripcion = $descripcion;
    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }

    // métodos de retorno de valores
    public function getIdDependencia() : int {
        return $this->IdDependencia;
    }
    public function getDependencia() : string {
        return $this->Dependencia;
    }
    public function getDescripcion() : string {
        return $this->Descripcion;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }
    public function getFechaAlta() : string {
        return $this->FechaAlta;
    }

    /**
     * Método que retorna la nómina de dependencias de las instituciones
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return array
     */
    public function listaDependencias() : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_dependencias.dependencia AS dependencia,
                            diccionarios.v_dependencias.id AS iddependencia,
                            diccionarios.v_dependencias.descripcion AS descripcion,
                            diccionarios.v_dependencias.usuario AS usuario,
                            diccionarios.v_dependencias.fecha AS fecha
                     FROM diccionarios.v_dependencias; ";
        $resultado = $this->Link->query($consulta);

        // lo pasamos a minúsculas porque según la versión de
        // pdo lo devuelve en mayúsculas o minúsculas
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro el nombre de una dependencia,
     * retorna el nùmero de registros encontrados, utilizado en el abm
     * de dependencias para evitar registros duplicados
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $dependencia - clave de la dependencia
     * @return int $registros
     */
    public function validaDependencia(string $dependencia) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.dependencias.id_dependencia) AS registros
                     FROM diccionarios.dependencias
                     WHERE diccionarios.dependencias.dependencia = '$dependencia'; ";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];

    }

    /**
     * Método que ejecuta la consulta de actualización o inserción en la
     * tabla de dependencias, retorna la clave del registro afectado
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return int - clave del registro insertado / editado
     */
    public function grabaDependencia() : int {

        // si está dando altas
        if ($this->IdDependencia == 0) {

            // insertamos el registro
            $this->nuevaDependencia();

        // si está editando
        } else {

            // editamos el registro
            $this->editaDependencia();

        }

        // retornamos la id del registro afectado
        return (int) $this->IdDependencia;

    }

    /**
     * Método que inserta una nueva dependencia en la tabla
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function nuevaDependencia() {

        // componemos la consulta de inserción
        $consulta = "INSERT INTO diccionarios.dependencias
                            (dependencia,
                             des_abr,
                             id_usuario)
                            VALUES
                            (:dependencia,
                             :descripcion,
                             :idusuario); ";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":dependencia", $this->Dependencia);
            $psInsertar->bindParam(":descripcion", $this->Descripcion);
            $psInsertar->bindParam(":idusuario",   $this->IdUsuario);

            // ejecutamos la consulta
            $psInsertar->execute();

            // obtenemos la clave del registro
            $this->IdDependencia = $this->Link->lastInsertId();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje y asignamos la clave
            $this->IdDependencia = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método protegido que edita el registro de dependencias
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function editaDependencia() {

        // componemos la consulta de edición
        $consulta = "UPDATE diccionarios.dependencias SET
                            dependencia = :dependencia,
                            des_abr = :descripcion,
                            id_usuario = :idusuario
                     WHERE diccionarios.dependencias.id_dependencia = :id";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":dependencia", $this->Dependencia);
            $psInsertar->bindParam(":descripcion", $this->Descripcion);
            $psInsertar->bindParam(":idusuario",   $this->IdUsuario);
            $psInsertar->bindParam(":id",          $this->IdDependencia);

            // ejecutamos la consulta
            $psInsertar->execute();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje y asignamos la clave
            $this->IdDependencia = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método que recibe como parámetro la clave de una dependencia }
     * y verifica que esta no esté asignada a un laboratorio o a
     * una institución asistencial, retorna el número de registros
     * encontrados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $iddependencia - clave del registro
     * @return int $registros - registros encontrados
     */
    public function puedeBorrar(int $iddependencia) : int {

        // inicializamos las variables
        $registros = 0;

        // verificamos si hay algÃºn laboratorio
        $consulta = "SELECT COUNT(cce.laboratorios.id) AS laboratorios
                     FROM cce.laboratorios
                     WHERE cce.laboratorios.iddependencia = '$iddependencia';";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y verificamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        $registros += $fila["laboratorios"];

        // retornamos el número de registros
        return (int) $registros;

    }

    /**
     * Método que recibe como parámetro la clave de un registro y
     * ejecuta la consulta de eliminación
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $iddependencia - clave del registro
     * @return bool resultado de la operación
     */
    public function borraDependencia(int $iddependencia) : bool {

        // declaramos las variables
        $resultado = false;

        // componemos la consulta
        $consulta = "DELETE FROM diccionarios.dependencias
                     WHERE diccionarios.dependencias.id_dependencia = :id; ";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":id", $iddependencia);

            // ejecutamos la consulta y retornamos
            $psInsertar->execute();
            $resultado = true;

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje y asignamos la clave
            echo $e->getMessage();

        }

        // retornamos
        return (bool) $resultado;

    }

    /**
     * Método que recibe como parámetro la clave de un registro
     * y asigna los valores del mismo a las variables de clase
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $iddependencia - clave del registro
     */
    public function getDatosDependencia(int $iddependencia){

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_dependencias.id AS id,
                            diccionarios.v_dependencias.dependencia AS dependencia,
                            diccionarios.v_dependencias.descripcion AS descripcion,
                            diccionarios.v_dependencias.usuario AS usuario,
                            diccionarios.v_dependencias.fecha AS fecha
                     FROM diccionarios.v_dependencias
                     WHERE diccionarios.v_dependencias.id = '$iddependencia'; ";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y asignamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        $this->IdDependencia = $fila["id"];
        $this->Dependencia = $fila["dependencia"];
        $this->Descripcion = $fila["descripcion"];
        $this->Usuario = $fila["usuario"];
        $this->FechaAlta = $fila["fecha"];

    }

}
?>