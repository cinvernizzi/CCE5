<?php

/**
 *
 * jurisdicciones/jurisdicciones.class.php
 *
 * @package     CCE
 * @subpackage  Jurisdicciones
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
 * Clase que controla las operaciones sobre la tabla de jurisdicciones
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 */
class Jurisdicciones{

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos
    protected $Id;                    // clave del registro
    protected $IdUsuario;             // clave del usuario
    protected $Usuario;               // nombre del usuario
    protected $NombrePais;            // nombre del país
    protected $IdPais;                // clave del país
    protected $IdProvincia;           // clave indec de la provincia
    protected $NombreProvincia;       // nombre de la provincia
    protected $PoblacionProvincia;    // población de la provincia
    protected $FechaAlta;             // fecha de alta del registro
    protected $Link;                  // puntero a la base de datos

    /**
     * Constructor de la clase, establece la conexión con la base
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __construct (){

        // inicializamos las variables
        $this->Id = 0;
        $this->NombrePais = "";
        $this->IdPais = 0;
        $this->IdProvincia = "";
        $this->NombreProvincia = "";
        $this->PoblacionProvincia = 0;
        $this->FechaAlta = "";
        $this->Usuario = "";
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

    // métodos de asignación de variables
    public function setNombrePais(string $pais){
        $this->NombrePais = $pais;
    }
    public function setIdPais(int $idpais){
        $this->IdPais = $idpais;
    }
    public function setNombreProvincia(string $provincia){
        $this->NombreProvincia = $provincia;
    }
    public function setIdProvincia(string $idprovincia){
        $this->IdProvincia = $idprovincia;
    }
    public function setPoblacion(int $poblacion){
        $this->PoblacionProvincia = $poblacion;
    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }

    // métodos de retorno de valores
    public function getId() : int {
        return (int) $this->Id;
    }
    public function getPais() : string {
        return $this->NombrePais;
    }
    public function getIdPais() : int {
        return (int) $this->IdPais;
    }
    public function getIdProvincia() : string {
        return $this->IdProvincia;
    }
    public function getProvincia() : string {
        return $this->NombreProvincia;
    }
    public function getPoblacion() : int {
        return (int) $this->PoblacionProvincia;
    }
    public function getFechaAlta() : string {
        return $this->FechaAlta;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }

    /**
     * Método que recibe la clave de un país y retorna el 
     * número de jurisdicciones que tiene ese país
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idpais - clave del país
     * @return int número de registros
     */
    public function numeroProvincias(int $idpais) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.v_provincias.cod_prov) AS registros 
                     FROM diccionarios.v_provincias
                     WHERE diccionarios.v_provincias.idpais = '$idpais';";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];
        
    }

    /**
     * Método que retorna un vector con la nómina de provincias y sus
     * claves, recibe como parámetro la clave del país
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $pais - clave del país
     * @return array vector con las provincias
     */
    public function listaProvincias(int $pais) : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_provincias.id AS id, 
                            diccionarios.v_provincias.cod_prov AS idprovincia,
                            diccionarios.v_provincias.provincia AS provincia,
                            diccionarios.v_provincias.idpais AS idpais,
                            diccionarios.v_provincias.pais AS nombrepais,
                            diccionarios.v_provincias.poblacion AS poblacion,
                            diccionarios.v_provincias.usuario AS usuario,
                            diccionarios.v_provincias.fecha_alta AS fecha_alta
                     FROM diccionarios.v_provincias
                     WHERE diccionarios.v_provincias.provincia != 'Indeterminado' AND
                           diccionarios.v_provincias.idpais = '$pais'
                     ORDER BY diccionarios.v_provincias.provincia;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // lo pasamos a minúsculas porque según la versión de
        // pdo lo devuelve en mayúsculas o minúsculas
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que retorna un vector con la nómina de provincias y sus
     * claves, recibe como parámetro la clave del país, el offset y 
     * el número de registros a retornar
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $pais - clave del país
     * @param int $desplazamiento - offset 
     * @param int $registros - registros a retornar
     * @return array vector con los registros
     */
    public function listaPaginada(int $pais, int $desplazamiento, int $registros) : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_provincias.id AS id, 
                            diccionarios.v_provincias.cod_prov AS idprovincia,
                            diccionarios.v_provincias.provincia AS nombreprovincia,
                            diccionarios.v_provincias.idpais AS idpais,
                            diccionarios.v_provincias.pais AS nombrepais,
                            diccionarios.v_provincias.poblacion AS poblacion,
                            diccionarios.v_provincias.usuario AS usuario,
                            diccionarios.v_provincias.fecha_alta AS fecha_alta
                     FROM diccionarios.v_provincias
                     WHERE diccionarios.v_provincias.provincia != 'Indeterminado' AND
                           diccionarios.v_provincias.idpais = '$pais'
                     ORDER BY diccionarios.v_provincias.provincia
                     LIMIT $desplazamiento, $registros;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // lo pasamos a minúsculas porque según la versión de
        // pdo lo devuelve en mayúsculas o minúsculas
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro el nombre de una jurisdicción y
     * la busca en la base, retorna el número de registros encontados
     * contrario, usada para evitar registros duplicados en el abm
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $provincia - nombre de la provincia
     * @param int $idpais - clave del país
     * @return int numero de registros encontrados
     */
    public function validaJurisdiccion(string $provincia, int $idpais) : int {

        // compone y ejecuta la consulta
        $consulta = "SELECT COUNT(diccionarios.v_provincias.cod_prov) AS registros
                     FROM diccionarios.v_provincias
                     WHERE diccionarios.v_provincias.provincia = '$provincia' AND
                           diccionarios.v_provincias.idpais = '$idpais'; ";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];

    }

    /**
     * Método que ejecuta la consulta de actualización de las jurisdicciones
     * y retorna la id del registro afectado
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $evento - acción a ejecutar
     * @return boolean resultado de la operación
     */
    public function grabaProvincia(string $evento) : bool {

        // inicializamos
        $resultado = true;

        // si está insertando
        if ($evento == "insertar"){

            // insertamos el registro
            $resultado = $this->nuevaProvincia();

        // si está editando
        } else {

            // editamos el registro
            $resultado = $this->editaProvincia();

        }

        // retorna el resultado de la operación
        return (bool) $resultado;

    }

    /**
     * Método protegido que graba un nuevo registro de provincia
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return boolean - resultado de la operación
     */
    protected function nuevaProvincia() : bool {

        // crea la consulta de inserción
        $consulta = "INSERT INTO diccionarios.provincias
                               (PAIS,
                                NOM_PROV,
                                COD_PROV,
                                POBLACION,
                                USUARIO)
                               VALUES
                               (:pais,
                                :provincia,
                                :cod_prov,
                                :poblacion,
                                :usuario);";

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asigna los valores
        $psInsertar->bindParam(":pais",       $this->IdPais);
        $psInsertar->bindParam(":provincia",  $this->NombreProvincia);
        $psInsertar->bindParam(":cod_prov",   $this->IdProvincia);
        $psInsertar->bindParam(":poblacion",  $this->PoblacionProvincia);
        $psInsertar->bindParam(":usuario",    $this->IdUsuario);

        // ejecuta la consulta
        $resultado = $psInsertar->execute();

        // retornamos el número de registros
        return (bool) $resultado;

    }

    /**
     * Método protegido que edita el registro de la provincia
     * retorna resultado de la operación
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return boolean - resultado de la operación
     */
    protected function editaProvincia() : bool {

        // crea la consulta de edición
        $consulta = "UPDATE diccionarios.provincias SET
                            PAIS = :idpais,
                            NOM_PROV = :provincia,
                            POBLACION = :poblacion,
                            USUARIO = :usuario
                     WHERE diccionarios.provincias.COD_PROV = :cod_prov;";

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asigna los valores
        $psInsertar->bindParam(":idpais",     $this->IdPais);
        $psInsertar->bindParam(":provincia",  $this->NombreProvincia);
        $psInsertar->bindParam(":poblacion",  $this->PoblacionProvincia);
        $psInsertar->bindParam(":usuario",    $this->IdUsuario);
        $psInsertar->bindParam(":cod_prov",   $this->IdProvincia);

        // ejecuta la consulta
        $resultado = $psInsertar->execute();

        // retorna el número de registros
        return (bool) $resultado;

    }

    /**
     * Método que recibe como paràmetro la clave del país y la clave
     * indec de la jurisdicción y verifica que no esté asignado a
     * ningún paciente, institución o laboratorio, retorna el número
     * de registros encontrados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $idprovincia - clave indec de la provincia
     * @return int registros encontrados
     */
    public function puedeBorrar(string $idprovincia) : int {

        // declaramos las variables
        $total = 0;

        // verificamos que no esté ningún laboratorio asignado
        $consulta = "SELECT COUNT(cce.laboratorios.id) AS registros
                     FROM cce.laboratorios
                     WHERE cce.laboratorios.localidad = '$idprovincia'; ";
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        // sumamos al total
        $total += $fila["registros"];

        // verificamos que no esté ningún responsable asignado
        $consulta = "SELECT COUNT(cce.responsables.id) AS registros 
                     FROM cce.responsables 
                     WHERE cce.responsables.localidad = '$idprovincia'; ";

        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        // sumamos al total
        $total += $fila["registros"];

        // retornamos el total
        return (int) $total;

    }

    /**
     * Método que recibe la clave indec y la clave del país y
     * ejecuta la consulta de eliminación
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idpais - clave del país
     * @param string $idprovincia - clave indec de la provincia
     * @return bool resultado de la operación
     */
    public function borraProvincia(int $idpais, string $idprovincia) : bool {

        // componemos la consulta
        $consulta = "DELETE FROM diccionarios.provincias
                     WHERE diccionarios.provincias.cod_prov = :idprovincia AND
                           diccionarios.provincias.pais = :idpais; ";

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asigna los valores
        $psInsertar->bindParam(":idprovincia", $idprovincia);
        $psInsertar->bindParam(":idpais",      $idpais);

        // ejecuta la consulta y retornamos
        $resultado = $psInsertar->execute();
        return (bool) $resultado;

    }

    /**
     * Método que recibe la clave indec de la provincia y la
     * clave del país y asigna en las variables de clase los
     * valores del registro
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idpais - clave del país
     * @param string $idprovincia - clave indec de la provincia
     */
    public function getDatosProvincia(int $idpais, string $idprovincia){

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_provincias.id AS id,
                            diccionarios.v_provincias.idpais AS idpais,
                            diccionarios.v_provincias.pais AS pais,
                            diccionarios.v_provincias.cod_prov AS codprov,
                            diccionarios.v_provincias.provincia AS provincia,
                            diccionarios.v_provincias.poblacion AS poblacion,
                            diccionarios.v_provincias.usuario AS usuario,
                            diccionarios.v_provincias.fecha_alta AS fecha_alta
                     FROM diccionarios.v_provincias
                     WHERE diccionarios.v_provincias.idpais = '$idpais' AND
                           diccionarios.v_provincias.cod_prov = '$idprovincia'; ";
        $resultado = $this->Link->query($consulta);

        // asignamos en la clase
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        $this->Id = $fila["id"];
        $this->NombrePais = $fila["pais"];
        $this->IdPais = $fila["idpais"];
        $this->IdProvincia = $fila["codprov"];
        $this->NombreProvincia = $fila["provincia"];
        $this->PoblacionProvincia = $fila["poblacion"];
        $this->FechaAlta = $fila["fecha_alta"];
        $this->Usuario = $fila["usuario"];

    }

    /**
     * Método que recibe como parámetro una clave indec y verifica
     * que no se encuentre declarado en la base, retorna el número
     * de registros encontrados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $indec - clave indec de la jurisdicción
     * @return int registros encontrados
     */
    public function verificaIndec(String $indec) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.provincias.id) AS registros 
                     FROM diccionarios.provincias 
                     WHERE diccionarios.provincias.cod_prov = '$indec';";

        // ejecutamos la consulta y retornamos
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];
        
    }

}
?>
