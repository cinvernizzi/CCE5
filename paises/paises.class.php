<?php

/**
 *
 * Class Paises | paises/paises.class.php
 *
 * @package     Diagnostico
 * @subpackage  Paises
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.1.0 (22/10/2017)
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
 *
 * Esta clase controla las operaciones de la tabla auxiliar de países
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 *
 */
class Paises{

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos
    protected $IdUsuario;             // clave del usuario
    protected $Usuario;               // nombre del usuario
    protected $FechaAlta;             // fecha de alta del registro
    protected $IdPais;                // clave del país
    protected $NombrePais;            // nombre del país
    protected $Link;                  // puntero a la base de datos

    /**
     * Constructor de la clase, establece la conexión con la base
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __construct (){

        // inicializamos las variables
        $this->IdPais = 0;
        $this->NombrePais = "";
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
    public function setIdPais(int $idpais){
        $this->IdPais = $idpais;
    }
    public function setNombrePais(string $nombrepais){
        $this->NombrePais = $nombrepais;

    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }

    // métodos de retorno de valores
    public function getIdPais() : int {
        return (int) $this->IdPais;
    }
    public function getPais() : string {
        return $this->NombrePais;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }
    public function getFechaAlta() : string {
        return $this->FechaAlta;
    }

    /**
     * Método que retorna el número de países registrados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return int número de registros
     */
    public function numeroPaises() : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.v_paises.id) AS registros
                     FROM diccionarios.v_paises; ";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];

    }

    /**
     * Método que retorna un vector con la nómina de países de la base
     * de datos de diccionarios
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return array vector con la nomina
     */
    public function listaPaises() : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_paises.pais AS pais,
                            diccionarios.v_paises.id AS idpais,
                            diccionarios.v_paises.usuario AS usuario,
                            diccionarios.v_paises.fecha_alta AS fecha_alta
                     FROM diccionarios.v_paises
                     WHERE diccionarios.v_paises.pais <> 'NO DECLARADA'
                     ORDER BY diccionarios.v_paises.pais;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetros el offset y el número
     * de registros a utilizar y retorna el vector con la
     * nómina de países
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $desplazamiento - offset
     * @param int $registros - número de filas a retornar
     */
    public function listaPaginada(int $desplazamiento, int $registros) : array {

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_paises.pais AS pais,
                            diccionarios.v_paises.id AS idpais,
                            diccionarios.v_paises.usuario AS usuario,
                            diccionarios.v_paises.fecha_alta AS fecha_alta
                     FROM diccionarios.v_paises
                     WHERE diccionarios.v_paises.pais <> 'NO DECLARADA'
                     ORDER BY diccionarios.v_paises.pais
                     LIMIT $desplazamiento, $registros;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro el nombre de un país y retorna
     * verdadero si ya está declarado en la base, utilizado para evitar
     * el ingreso de registros duplicados
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $pais - nombre del país
     * @return int registros encontrados
     */
    public function existePais(string $pais) : int {

        // componemos y ejecutamos la consulta
        $consulta = "SELECT COUNT(diccionarios.paises.ID) AS registros
                     FROM diccionarios.paises
                     WHERE diccionarios.paises.NOMBRE = '$pais';";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];

    }

    /**
     * Método que según el caso realiza la consulta de inserción o edición
     * en la tabla de paises, retorna la id del registro o el resultado
     * de la operación
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return int . clave del registro insertado / editado
     */
    public function grabaPais() : int {

        // si está insertando
        if ($this->IdPais == 0){

            // insertamos el registro
            $this->nuevoPais();

            // si está editando
        } else {

            // editamos el registro
            $this->editaPais();

        }

        // retornamos la id del registro
        return (int) $this->IdPais;

    }

    /**
     * Método protegido que inserta un nuevo registro en la tabla de países
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function nuevoPais(){

        // compone la consulta de inserción
        $consulta = "INSERT INTO diccionarios.paises
                            (NOMBRE,
                             USUARIO)
                            VALUES
                            (:pais,
                             :usuario);";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":pais", $this->NombrePais);
            $psInsertar->bindParam(":usuario", $this->IdUsuario);

            // ejecutamos la consulta
            $psInsertar->execute();

            // obtenemos la clave del registro
            $this->IdPais = $this->Link->lastInsertId();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            $this->IdPais = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método protegido que edita el registro de la tabla de países
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function editaPais(){

        // compone la consulta de actualización
        $consulta = "UPDATE diccionarios.paises SET
                            NOMBRE = :pais,
                            USUARIO = :usuario
                     WHERE diccionarios.paises.ID = :id;";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":pais", $this->NombrePais);
            $psInsertar->bindParam(":usuario", $this->IdUsuario);
            $psInsertar->bindParam(":id", $this->IdPais);

            // ejecutamos la consulta
            $psInsertar->execute();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            $this->IdPais = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método que recibe como parámetro la clave de un registro
     * y verifica que no esté asignado a ninguna otra tabla
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idpais - clave del registro
     * @return int número de registros encontrados
     */
    public function puedeBorrar(int $idpais) : int {

        // ahora consultamos la tabla de laboratorios
        $consulta = "SELECT COUNT(cce.vw_laboratorios.id) AS registros
                     FROM cce.v_laboratorios
                     WHERE cce.v_laboratorios.idpais = '$idpais'; ";
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        // retornamos el total de registros encontrados
        return (int) $fila["registros"];

    }

    /**
     * Método que recibe la clave de un registro y ejecuta la
     * consulta de eliminación
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idpais - clave del registro
     * @return bool resultado de la operación
     */
    public function borraPais(int $idpais) : bool {

        // declaramos las variables
        $resultado = false;

        // componemos la consulta
        $consulta = "DELETE FROM diccionarios.paises
                     WHERE diccionarios.paises.id = :id; ";

        // capturamos el error
        try {

            // asignamos la consulta y ejecutamos
            $psInsertar = $this->Link->prepare($consulta);
            $psInsertar->bindParam(":id", $idpais);
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
     * Método que recibe la clave de un registro y asigna los
     * valores en las variables de clase
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idpais - clave del registro
     */
    public function getDatosPais(int $idpais){

        // componemos la consulta
        $consulta = "SELECT diccionarios.v_paises.id AS id,
                            diccionarios.v_paises.pais AS pais,
                            diccionarios.v_paises.fecha_alta AS fecha_alta,
                            diccionarios.v_paises.usuario AS usuario
                     FROM diagnostico.v_paises
                     WHERE diccionarios.v_paises.id = '$idpais'; ";
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y asignamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        $this->IdPais = $fila["id"];
        $this->NombrePais = $fila["pais"];
        $this->FechaAlta = $fila["fecha_alta"];
        $this->Usuario = $fila["usuario"];

    }

    /**
     * Método que recibe como parámetro el nombre del país
     * y verifica que no esté repetido, retorna el número
     * de registros encontrados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $pais - nombre del país
     * @return int número de registros
     */
    public function validaPais(string $pais) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(diccionarios.v_paises.id) AS registros
                     FROM diagnostico.v_paises
                     WHERE diccionarios.v_paises.pais = '$pais'; ";
        $resultado= $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $fila["registros"];

    }

}
?>
