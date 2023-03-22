<?php

/**
 *
 * Class Localidades | localidades/localidades.class.php
 *
 * @package     CCE
 * @subpackage  Localidades
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.5.0 (23/03/2023)
 * @copyright   Copyright (c) 2017, INP
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
 * Clase que controla las operaciones sobre la tabla de localidades
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 */
class Localidades{

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos
    protected $Id;                    // clave del registro
    protected $IdUsuario;             // clave del usuario
    protected $Usuario;               // nombre del usuario
    protected $IdProvincia;           // clave indec de la provincia
    protected $NombreProvincia;       // nombre de la provincia
    protected $FechaAlta;             // fecha de alta del registro
    protected $NombreLocalidad;       // nombre de la lodiccionarios
    protected $IdLocalidad;           // clave indec de la localidad
    protected $PoblacionLocalidad;    // poblacion de la localidad
    protected $Link;                  // puntero a la base de datos

    /**
     * Constructor de la clase, establece la conexión con la base
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __construct (){

        // inicializamos las variables
        $this->Id = 0;
        $this->IdProvincia = "";
        $this->NombreProvincia = "";
        $this->IdLocalidad = "";
        $this->NombreLocalidad = "";
        $this->PoblacionLocalidad = 0;
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
    function __destruct(){

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos de asignación de valores
    public function setNombreProvincia(string $provincia){
        $this->NombreProvincia = $provincia;
    }
    public function setIdProvincia(string $idprovincia){
        $this->IdProvincia = $idprovincia;
    }
    public function setNombreLocalidad(string $localidad){
        $this->NombreLocalidad = $localidad;
    }
    public function setIdLocalidad(string $idlocalidad){
        $this->IdLocalidad = $idlocalidad;
    }
    public function setPoblacionLocalidad(int $poblacion){
        $this->PoblacionLocalidad = $poblacion;
    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }

    // Métodos de retorno de valores
    public function getId() : int {
        return (int) $this->Id;
    }
    public function getIdProvincia() : string {
        return $this->IdProvincia;
    }
    public function getProvincia() : string {
        return $this->NombreProvincia;
    }
    public function getIdLocalidad() : string {
        return $this->IdLocalidad;
    }
    public function getLocalidad() : string {
        return $this->NombreLocalidad;
    }
    public function getPoblacion() : int {
        return (int) $this->PoblacionLocalidad;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }
    public function getFechaAlta() : string {
        return $this->FechaAlta;
    }

    /**
     * Método utilizado en el formulario de pacientes, recibe como parámetro
     * parte del nombre de la localidad y retorna un array con los ciudades
     * coincidentes, el país y la provinvia
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $localidad - nombre de la localidad a buscar
     * @return array vector con los registros coincidentes
     */
    public function nominaLocalidades(string $localidad) : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_localidades.pais AS pais,
                            diccionarios.v_localidades.idpais AS idpais,
                            diccionarios.v_localidades.provincia AS provincia,
                            diccionarios.v_localidades.localidad AS localidad,
                            diccionarios.v_localidades.codloc AS codloc
                     FROM diccionarios.v_localidades
                     WHERE diccionarios.v_localidades.localidad LIKE '%$localidad%'
                     ORDER BY diccionarios.v_localidades.pais,
                              diccionarios.v_localidades.provincia,
                              diccionarios.v_localidades.localidad;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // lo pasamos a minúsculas porque según la versión de
        // pdo lo devuelve en mayúsculas o minúsculas
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe la clave de una provincia y retorna 
     * el número de localidades de esa provincia
     * @author Claudio Invernizzi <cinvernizzi@gmai.com>
     * @param string $codpcia - clave indec de la provincia
     * @return int número de registros
     */
    public function numeroLocalidades(string $codpcia) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.v_localidades.codloc) AS registros 
                     FROM diccionarios.v_localidades
                     WHERE diccionarios.v_localidades.codpcia = '$codpcia';";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];

    }

    /**
     * Método que recibe como parámetro la clave de una provincia 
     * el offset y el número de registros a retornar y devuelve
     * el vector con los datos de las localidades
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $codpcia - clave indec de la provincia
     * @param int $desplazamiento - offset
     * @param int $registros - número de registros a retornar
     * @return array - vector con los registros
     */
    public function listaPaginada(string $codpcia, int $desplazamiento, int $registros) : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_localidades.id AS id, 
                            diccionarios.v_localidades.codloc AS idlocalidad,
                            diccionarios.v_localidades.localidad AS localidad,
                            diccionarios.v_localidades.provincia AS provincia,
                            diccionarios.v_localidades.codpcia AS codpcia,
                            diccionarios.v_localidades.poblacion AS poblacion,
                            diccionarios.v_localidades.usuario AS usuario,
                            diccionarios.v_localidades.fecha_alta AS fecha_alta
                     FROM diccionarios.v_localidades
                     WHERE diccionarios.v_localidades.codpcia = '$codpcia'
                     ORDER BY diccionarios.v_localidades.localidad
                     LIMIT $desplazamiento, $registros;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // lo pasamos a minúsculas porque según la versión de
        // pdo lo devuelve en mayúsculas o minúsculas
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro la id de una provincia
     * y retorna la nómina de las localidades en esa provincia
     * junto con su clave
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $provincia - nombre de la provincia
     * @return array
     */
    public function listaLocalidades(string $provincia) : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_localidades.id AS id,
                            diccionarios.v_localidades.codloc AS idlocalidad,
                            diccionarios.v_localidades.localidad AS localidad,
                            diccionarios.v_localidades.provincia AS provincia,
                            diccionarios.v_localidades.codpcia AS codpcia,
                            diccionarios.v_localidades.poblacion AS poblacion,
                            diccionarios.v_localidades.usuario AS usuario,
                            diccionarios.v_localidades.fecha_alta AS fecha_alta
                     FROM diccionarios.v_localidades
                     WHERE diccionarios.v_localidades.codpcia = '$provincia'
                     ORDER BY diccionarios.v_localidades.localidad; ";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // lo pasamos a minúsculas porque según la versión de
        // pdo lo devuelve en mayúsculas o minúsculas
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetros la clave indec de la provincia y
     * el nombre de la localidad, retorna el número de registros
     * duplicados, usado en el abm de localidad para evitar registros duplicados
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $provincia - nombre de la provincia
     * @param string $localidad - nombre de la localidad
     * @return int numero de registros encontrados
     */
    public function existeLocalidad(string $provincia, string $localidad) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.v_localidades.id) AS registros
                     FROM diccionarios.v_localidades
                     WHERE diccionarios.v_localidades.codpcia = '$provincia' AND
                           diccionarios.v_localidades.localidad = '$localidad';";

        // obtenemos el registro y retornamos
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];

    }

    /**
     * Método que recibe como parámetro la acción a realizar (insertar o
     * editar) compone la consulta y ejecuta sobre la tabla de los diccionarios
     * Retorna el resultado de la operación
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $evento - tipo de evento (inserción / edición)
     * @return boolean resultado de la operacion
     */
    public function grabaLocalidad(string $evento) : bool {

        // inicializamos
        $resultado = true;

        // si está insertando
        if ($evento == "insertar"){
            $resultado = $this->nuevaLocalidad();
        } else {
            $resultado = $this->editaLocalidad();

        }

        // retornamos el estado de la operación
        return (bool) $resultado;

    }

    /**
     * Método protegido que inserta un nuevo registro en la tabla
     * de localidades, retorna el resultado de la operación
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return boolean el resultado de la operación
     */
    protected function nuevaLocalidad() : bool {

        // declaramos las variables
        $resultado = false;

        // compone la consulta de inserción
        $consulta = "INSERT INTO diccionarios.localidades
                            (CODPCIA,
                             NOMLOC,
                             CODLOC,
                             POBLACION,
                             USUARIO)
                            VALUES
                            (:codpcia,
                             :localidad,
                             :codloc,
                             :poblacion,
                             :usuario);";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":codpcia", $this->IdProvincia);
            $psInsertar->bindParam(":localidad", $this->NombreLocalidad);
            $psInsertar->bindParam(":codloc", $this->IdLocalidad);
            $psInsertar->bindParam(":poblacion", $this->PoblacionLocalidad);
            $psInsertar->bindParam(":usuario", $this->IdUsuario);

            // ejecutamos la consulta y asignamos
            $psInsertar->execute();
            $resultado = true;

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            echo $e->getMessage();

        }

        // retornamos
        return (bool) $resultado;

    }

    /**
     * Método protegido que edita el registro de localidades
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return boolean el resultado de la operación
     */
    protected function editaLocalidad() : bool {

        // declaramos las variables
        $resultado = false;

        // compone la consulta de edición
        $consulta = "UPDATE diccionarios.localidades SET
                            CODPCIA = :codpcia,
                            NOMLOC = :localidad,
                            POBLACION = :poblacion,
                            USUARIO = :usuario
                     WHERE diccionarios.localidades.CODLOC = :codloc;";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":codpcia", $this->IdProvincia);
            $psInsertar->bindParam(":localidad", $this->NombreLocalidad);
            $psInsertar->bindParam(":poblacion", $this->PoblacionLocalidad);
            $psInsertar->bindParam(":usuario", $this->IdUsuario);
            $psInsertar->bindParam(":codloc", $this->IdLocalidad);

            // ejecutamos la consulta y asignamos
            $psInsertar->execute();
            $resultado = true;

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            echo $e->getMessage();

        }

        // retornamos
        return (bool) $resultado;

    }

    /**
     * Método que recibe como parámetro la clave indec de la
     * localidad y de la provincia y verifica que no tenga
     * ningún registro relacionado
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $idlocalidad - clave indec de la localidad
     * @return int registros encontrados
     */
    public function puedeBorrar(string $idlocalidad) : int {

        // inicializamos las variables
        $total = 0;
        
        // verificamos sobre la tabla de laboratorios
        $consulta = "SELECT COUNT(cce.laboratorios.id) AS registros 
                     FROM cce.laboratorios 
                     WHERE cce.laboratorios.localidad = '$idlocalidad'; ";
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);        
        
        // incrementamos el contador
        $total += $fila["registros"];
        
        // verificamos la tabla de responsables
        $consulta = "SELECT COUNT(cce.responsables.id) AS registros 
                     FROM cce.responsables
                     WHERE cce.responsables.localidad = '$idlocalidad';";
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);        
        
        // incrementamos el contador
        $total += $fila["registros"];
                
        // retornamos el total de registros
        return (int) $total;
        
    }

    /**
     * Método que ejecuta la consulta de eliminación
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $idprovincia - clave indec de la provincia
     * @param string $idlocalidad - clave indec de la localidad
     * @return bool - resultado de la operación
     */
    public function borraLocalidad(string $idprovincia, string $idlocalidad) : bool {
        
        // declaramos las variables
        $resultado = false;

        // componemos la consulta
        $consulta = "DELETE FROM diccionarios.localidades 
                     WHERE diccionarios.localidades.codloc = :localidad AND 
                           diccionarios.localidades.codpcia = :provincia; ";

        // capturamos el error
        try {

            // asignamos la consulta y ejecutamos
            $psInsertar = $this->Link->prepare($consulta);
            $psInsertar->bindParam(":localidad", $idlocalidad);
            $psInsertar->bindParam(":provincia", $idprovincia);
            $psInsertar->execute();
            $resultado = true;

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            echo $e->getMessage();

        }

        // retornamos
        return (bool) $resultado;

    }
    
    /**
     * Método que recibe la clave de provincia y localidad y 
     * asigna en las variables de clase los valores del registro
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $idprovincia - clave indec de la provincia
     * @param string $idlocalidad - clave indec de la localidad
     */
    public function getDatosLocalidad(string $idprovincia, string $idlocalidad){
        
        // componemos la consulta
        $consulta = "SELECT diccionarios.v_localidades.id AS id,
                            diccionarios.v_localidades.codpcia AS codpcia, 
                            diccionarios.v_localidades.provincia AS provincia, 
                            diccionarios.v_localidades.codloc AS codloc, 
                            diccionarios.v_localidades.localidad AS localidad,
                            diccionarios.v_localidades.poblacion AS poblacion,
                            diccionarios.v_localidades.usuario AS usuario, 
                            diccionarios.v_localidades.fecha_alta AS fecha_alta
                     FROM diccionarios.v_localidades
                     WHERE diccionarios.v_localidades.codpcia = '$idprovincia' AND 
                           diccionarios.v_localidades.codloc = '$idlocalidad'; ";
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);        

        // asignamos en la clase
        $this->Id = $fila["id"];
        $this->IdProvincia = $fila["codpcia"];
        $this->NombreProvincia = $fila["provincia"];
        $this->IdLocalidad = $fila["codloc"];
        $this->NombreLocalidad = $fila["localidad"];
        $this->PoblacionLocalidad = $fila["poblacion"];
        $this->Usuario = $fila["usuario"];
        $this->FechaAlta = $fila["fecha_alta"];
        
    }

    /**
     * Método que recibe como parámetro la clave de una provincia
     * y el nombre de la localidad y verifica que no esté repetida
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $idprovincia - clave indec de la provincia
     * @param string $localidad - nombre de la localidad
     */
    public function validaLocalidad(string $idprovincia, string $localidad) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.v_localidades.id) AS registros 
                     FROM diccionarios.v_localidades 
                     WHERE diccionarios.v_localidades.codpcia = '$idprovincia' AND 
                           diccionarios.v_localidades.localidad = '$localidad';";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);        
        return (int) $fila["registros"];

    }

}
?>
