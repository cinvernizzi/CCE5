<?php

/**
 *
 * Class Marcas | marcas/marcas.class.php
 *
 * @package     CCE
 * @subpackage  Marcas
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.4.0 (09/08/2018)
 * @copyright   Copyright (c) 2018, INP
 *
 * Cláse que contiene las operaciones sobre la base de datos
 * de marcas de reactivos
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
 * definición de la clase
 * @author Claudio Invernizzi <cinvernizzi@gmail.com>
 */
class Marcas {

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos
    protected $Link;                  // puntero a la base de datos

    // definición de las variables de la base de datos
    protected $IdMarca;                // clave de la marca
    protected $Marca;                  // nombre de la marca
    protected $IdTecnica;              // clave de la técnica
    protected $Tecnica;                // nombre de la técnica
    protected $IdUsuario;              // clave del usuario
    protected $Usuario;                // nombre del usuario
    protected $Fecha;                  // fecha de alta

    // constructor de la clase
    function __construct (){

        // nos conectamos a la base de datos
        $this->Link = new Conexion();

        // inicializamos las variables de clase
        $this->IdMarca = 0;
        $this->Marca = "";
        $this->IdTecnica = 0;
        $this->Tecnica = "";
        $this->IdUsuario = 0;
        $this->Usuario = "";
        $this->Fecha = "";

    }

    // destructor de la clase
    function __destruct(){

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos de asignación de variables
    public function setIdMarca(int $idmarca){
        $this->IdMarca = $idmarca;
    }
    public function setIdTecnica(int $idtecnica){
        $this->IdTecnica = $idtecnica;
    }
    public function setMarca(string $marca){
        $this->Marca = $marca;
    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }

    // métodos de retorno de valores
    public function getIdMarca() : int {
        return (int) $this->IdMarca;
    }
    public function getIdTecnica() : int {
        return (int) $this->IdTecnica;
    }
    public function getTecnica() : string {
        return $this->Tecnica;
    }
    public function getMarca() : string {
        return $this->Marca;
    }
    public function getIdUsuario() : int {
        return (int) $this->IdUsuario;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }
    public function getFecha() : string {
        return $this->Fecha;
    }

    /**
     * Método que retorna el listado de las marcas, recibe como parámetro
     * la id de la técnica, retorna la id de la marca, que
     * es utilizada en el abm de marcas
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $tecnica - la id de una técnica
     * @return array
     */
    public function nominaMarcas(int $tecnica) : array {

        // componemos la consulta
        $consulta = "SELECT cce.vw_marcas.marca AS marca,
                            cce.vw_marcas.id AS id,
                            cce.vw_marcas.idtecnica AS idtecnica,
                            cce.vw_marcas.tecnica AS tecnica,
                            cce.vw_marcas.fecha AS fecha,
                            cce.vw_marcas.usuario AS usuario,
                            cce.vw_marcas.idusuario AS idusuario,
                            cce.vw_marcas.fecha AS fecha
                     FROM cce.vw_marcas
                     WHERE cce.vw_marcas.idtecnica = '$tecnica'
                     ORDER BY cce.vw_marcas.marca;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro la clave de una marca y 
     * asigna en las variables de clase los datos del registro
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idmarca - clave del registro
     */
    public function getDatosMarca(int $idmarca){

        // componemos la consulta
        $consulta = "SELECT cce.vw_marcas.marca AS marca,
                            cce.vw_marcas.id AS id,
                            cce.vw_marcas.idtecnica AS idtecnica,
                            cce.vw_marcas.tecnica AS tecnica,
                            cce.vw_marcas.usuario AS usuario,
                            cce.vw_marcas.idusuario AS idusuario,
                            cce.vw_marcas.fecha AS fecha
                     FROM cce.vw_marcas
                     WHERE cce.vw_marcas.id = '$idmarca'
                     ORDER BY cce.vw_marcas.marca;";

        // ejecutamos la consulta y asignamos
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        $this->Marca = $fila["marca"];
        $this->IdMarca = $fila["id"];
        $this->IdTecnica = $fila["idtecnica"];
        $this->Tecnica = $fila["tecnica"];
        $this->Fecha = $fila["fecha"];
        $this->Usuario = $fila["usuario"];

    }

    /**
     * Método que retorna la clave de una marca, recibe como parámetro
     * la id de la técnica y la cadena con la marca
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tecnica - nombre de la técnica
     * @param string $marca - nombre de la marca
     * @return int
     */
    public function getClaveMarca(int $tecnica, string $marca) : int {

        // inicializamos las variables
        $clave = 0;

        // componemos la consulta
        $consulta = "SELECT cce.vw_marcas.id AS id
                     FROM cce.vw_marcas
                     WHERE cce.vw_marcas.idtecnica = '$tecnica' AND
                           cce.vw_marcas.marca = '$marca'; ";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // si hubo registros (porque también lo utilizamos para verificar
        // si una marca no se encuentra repetida)
        if ($resultado->rowCount() != 0){

            // lo pasamos a minúsculas porque según la versión de
            // pdo lo devuelve en mayúsculas o minúsculas
            $registro = $resultado->fetch(PDO::FETCH_ASSOC);

            // retornamos el vector
            $clave = $registro["id"];

        // si no hubo registros
        } else {

            // retorna falso
            $clave = 0;

        }

        // retornamos
        return (int) $clave;

    }

    /**
     * Función que actualiza la base de datos de marcas, retorna la
     * clave del registro afectado
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return int
     */
    public function grabaMarca() : int {

        // si està insertando
        if ($this->IdMarca == 0){
            $this->nuevaMarca();
        } else {
            $this->editaMarca();
        }

        // retorna la id del registro
        return (int) $this->IdMarca;

    }

    /**
     * Método que inserta un nuevo registro en la tabla de marcas
     */
    protected function nuevaMarca(){

        // compone la consulta de inserción
        $consulta = "INSERT INTO cce.marcas_chagas
                            (MARCA,
                             TECNICA,
                             USUARIO)
                            VALUES
                            (:marca,
                             :idtecnica,
                             :idusuario); ";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":marca", $this->Marca);
            $psInsertar->bindParam(":idtecnica", $this->IdTecnica);
            $psInsertar->bindParam(":idusuario", $this->IdUsuario);

            // ejecutamos la edición
            $psInsertar->execute();

            // obtiene la id del registro insertado
            $this->IdMarca = $this->Link->lastInsertId();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            $this->IdMarca = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método protegido que edita el registro de marcas
     */
    protected function editaMarca(){

        // compone la consulta de edición
        $consulta = "UPDATE cce.marcas_chagas SET
                            MARCA = :marca,
                            TECNICA = :idtecnica,
                            USUARIO = :idusuario
                     WHERE cce.marcas_chagas.ID = :idmarca; ";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":marca", $this->Marca);
            $psInsertar->bindParam(":idtecnica", $this->IdTecnica);
            $psInsertar->bindParam(":idusuario", $this->IdUsuario);
            $psInsertar->bindParam(":idmarca", $this->IdMarca);

            // ejecutamos la edición
            $psInsertar->execute();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            $this->IdMarca = 0;
            echo $e->getMessage();

        }

    }

}
?>
