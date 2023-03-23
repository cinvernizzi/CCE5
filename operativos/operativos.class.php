<?php

/**
 *
 * Class Operativos | operativos.class.php
 *
 * @package     CCE
 * @subpackage  Operativos
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.4.0 (23/03/2023)
 * @copyright   Copyright (c) 2018, INP
 *
 * Clase con las operaciones sobre la tabla de operativos
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
class Operativos {

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos

    // definición de variables
    protected $Link;                       // puntero a la base de datos

    // declaración de variables de la base de datos
    protected $IdOperativo;                // clave del registro
    protected $OperativoNro;               // número de operativo
    protected $Abierto;                    // indica si está abierto o no
    protected $Tipo;                       // tipo de operativo (serología, pcr, etc.)
    protected $FechaAlta;                  // fecha de alta del registro
    protected $FechaInicio;                // fecha de inicio del operativo
    protected $FechaFin;                   // finalización del operativo
    protected $Reporte;                    // contenido del reporte
    protected $IdUsuario;                  // clave del usuario
    protected $Usuario;                    // nombre del usuario

    /**
     * Constructor de la clase, establece la conexión con la base
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __construct (){

        // nos conectamos a la base de datos
        $this->Link = new Conexion();

        // inicializamos las variables
        $this->IdOperativo = 0;
        $this->OperativoNro = "";
        $this->Abierto = "No";
        $this->Tipo = "";
        $this->FechaInicio = "";
        $this->FechaFin = "";
        $this->Reporte = "";
        $this->IdUsuario = 0;

    }

    /**
     * Destructor de la clase, cierra la conexión con la base
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __destruct(){

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos de asignación
    public function setIdOperativo(int $idoperativo){
        $this->IdOperativo = $idoperativo;
    }
    public function setOperativoNro(string $operativo){
        $this->OperativoNro = $operativo;
    }
    public function setFechaInicio(string $fechainicio){
        $this->FechaInicio = $fechainicio;
    }
    public function setFechaFin(string $fechafin){
        $this->FechaFin = $fechafin;
    }
    public function setAbierto(string $abierto){
        $this->Abierto = $abierto;
    }
    public function setTipo(string $tipo){
        $this->Tipo = $tipo;
    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }

    // métodos de retorno de valores
    public function getIdOperativo() : int {
        return (int) $this->IdOperativo;
    }
    public function getOperativo() : string {
        return $this->OperativoNro;
    }
    public function getAbierto() : string {
        return $this->Abierto;
    }
    public function getFechaAlta() : string {
        return $this->FechaAlta;
    }
    public function getFechaInicio() : string {
        return $this->FechaInicio;
    }
    public function getFechaFin() : string {
        return $this->FechaFin;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }
    public function getTipo() : string {
        return $this->Tipo;
    }

    /**
     * Método que retorna el array completo de los operativos
     * ordenados por fecha, es utilizado tanto en los combos
     * como en la grilla de operativos
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tipo - tipo de operativo a listar
     * @return array
     */
    public function nominaOperativos(string $tipo) : array {

        // componemos la consulta
        $consulta = "SELECT cce.vw_operativos.id AS id_operativo,
                            cce.vw_operativos.operativo AS operativo,
                            cce.vw_operativos.abierto AS abierto,
                            cce.vw_operativos.tipo AS tipo,
                            cce.vw_operativos.inicio AS fecha_inicio,
                            cce.vw_operativos.fin AS fecha_fin,
                            cce.vw_operativos.fecha AS fecha_alta,
                            cce.vw_operativos.usuario AS usuario
                     FROM cce.vw_operativos
                     WHERE cce.vw_operativos.tipo = '$tipo'
                     ORDER BY STR_TO_DATE(cce.vw_operativos.fin, '%d/%m/%Y') DESC;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que retorna un array con la nómina de operativos abiertos,
     * utilizado en la carga de determinaciones
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tipo - tipo de operativo a listar
     * @return array
     */
    public function listaAbiertos(string $tipo) : array {

        // componemos la consulta
        $consulta = "SELECT cce.vw_operativos.id AS id,
                            cce.vw_operativos.operativo AS operativo
                     FROM cce.vw_operativos
                     WHERE cce.vw_operativos.abierto = 'Si' AND 
                           cce.vw_operativos.tipo = '$tipo'
                     ORDER BY STR_TO_DATE(cce.vw_operativos.fin, '%d/%m/%Y') DESC; ";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que retorna un array con la nómina de operativos cerrados
     * utilizado al establecer los resultados de las muestras
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tipo - tipo de operativo a listar
     * @return array
     */
    public function listaCerrados(string $tipo) : array {

        // componemos la consulta
        $consulta = "SELECT cce.vw_operativos.operativo AS operativo
                     FROM cce.vw_operativos
                     WHERE cce.vw_operativos.abierto = 'No' AND 
                           cce.vw_operativos.tipo = '$tipo'
                     ORDER BY STR_TO_DATE(cce.vw_operativos.fin, '%d/%m/%Y') DESC; ";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro la cadena con el número
     * de operativo y retorna la clave del mismo
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $operativo - cadena con el operativo
     * @param string $tipo - tipo de operativo
     * @return int
     */
    public function getClaveOperativo(string $operativo, string $tipo) : int {

        // componemos la consulta
        $consulta = "SELECT cce.vw_operativos.id AS id
                     FROM cce.vw_operativos
                     WHERE cce.vw_operativos.operativo = '$operativo' AND 
                           cce.vw_operativos.tipo = '$tipo';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $registro = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $registro["idoperativo"];

    }

    /**
     * Método que obtiene los datos de un operativo y los asigna a las
     * variables de clase
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idoperativo - clave del registro
     */
    public function getDatosOperativo(int $idoperativo){

        // componemos la consulta
        $consulta =  "SELECT cce.vw_operativos.id AS id,
                             cce.vw_operativos.abierto AS abierto,
                             cce.vw_operativos.tipo AS tipo,
                             cce.vw_operativos.fecha AS fecha_alta,
                             cce.vw_operativos.fin AS fecha_fin,
                             cce.vw_operativos.inicio AS fecha_inicio,
                             cce.vw_operativos.operativo AS operativo,
                             cce.vw_operativos.usuario AS usuario,
                             cce.vw_operativos.idusuario AS idusuario,
                     FROM cce.operativos_chagas INNER JOIN cce.responsables ON cce.operativos_chagas.USUARIO = cce.responsables.ID
                     WHERE cce.operativos_chagas.ID = '$idoperativo'; ";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        // lo asignamos a las variables de clase
        $this->IdOperativo = $fila["id"];
        $this->Abierto = $fila["abierto"];
        $this->Tipo = $fila["tipo"];
        $this->FechaAlta = $fila["fecha_alta"];
        $this->OperativoNro = $fila["operativo"];
        $this->FechaInicio = $fila["fecha_inicio"];
        $this->FechaFin = $fila["fecha_fin"];
        $this->Usuario = $fila["usuario"];

    }

    /**
     * Método que ejecuta la consulta en la base de datos y retorna la
     * id del registro afectado
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return int - clave del registro afectado
     */
    public function grabaOperativo() : int {

        // si está insertando
        if ($this->IdOperativo == 0){
            $this->nuevoOperativo();
        } else {
            $this->editaOperativo();
        }

        // retorna la clave
        return (int) $this->IdOperativo;

    }

    /**
     * Método que ejecuta la consulta de inserción de operativos
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function nuevoOperativo(){

        // compone la consulta de inserción
        $consulta = "INSERT INTO cce.operativos_chagas
                                 (ABIERTO,
                                  TIPO,
                                  FECHA_FIN,
                                  FECHA_INICIO,
                                  OPERATIVO_NRO,
                                  USUARIO)
                                 VALUES
                                 (:abierto,
                                  :tipo,
                                  STR_TO_DATE(:fecha_fin, '%d/%m/%Y'),
                                  STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'),
                                  :operativo_nro,
                                  :idusuario);";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":abierto",       $this->Abierto);
            $psInsertar->bindParam(":tipo",          $this->Tipo);
            $psInsertar->bindParam(":fecha_fin",     $this->FechaFin);
            $psInsertar->bindParam(":fecha_inicio",  $this->FechaInicio);
            $psInsertar->bindParam(":operativo_nro", $this->OperativoNro);
            $psInsertar->bindParam(":idusuario",     $this->IdUsuario);

            // ejecutamos la edición
            $psInsertar->execute();

            // obtiene la id del registro insertado
            $this->IdOperativo = $this->Link->lastInsertId();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje y asignamos
            $this->IdOperativo = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método que ejecuta la consulta de edición de operativos
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function editaOperativo(){

        // compone la consulta de edición
        $consulta = "UPDATE cce.operativos_chagas SET
                            ABIERTO = :abierto,
                            TIPO = :tipo, 
                            FECHA_FIN = STR_TO_DATE(:fecha_fin, '%d/%m/%Y'),
                            FECHA_INICIO = STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'),
                            OPERATIVO_NRO = :operativo_nro,
                            USUARIO = :idusuario
                      WHERE operativos_chagas.ID = :idoperativo;";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":abierto", $this->Abierto);
            $psInsertar->bindParam(":tipo",          $this->Tipo);
            $psInsertar->bindParam(":fecha_fin", $this->FechaFin);
            $psInsertar->bindParam(":fecha_inicio", $this->FechaInicio);
            $psInsertar->bindParam(":operativo_nro", $this->OperativoNro);
            $psInsertar->bindParam(":idusuario", $this->IdUsuario);
            $psInsertar->bindParam(":idoperativo", $this->IdOperativo);

            // ejecutamos la edición
            $psInsertar->execute();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje y asignamos
            $this->IdOperativo = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Metodo que recibe como parámetro la clave del operativo
     * el resultado a establecer y el primer dígito de la
     * muestra y actualiza los resultados correctos, retorna
     * el número de registros afectados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idoperativo - clave con el número de operativo
     * @param string $resultado - cadena con el resultado correcto
     * @param int $muestra - entero con el primer dígito de la muestra
     * @return int
     */
    public function establecerResultados(int $idoperativo, 
                                         string $resultado, 
                                         int $muestra){

        // componemos la consulta
        $consulta = "UPDATE cce.chag_datos SET
                            CORRECTO = :resultado
                    WHERE cce.chag_datos.OPERATIVO = :idoperativo AND
                          INSTR(cce.chag_datos.MUESTRA_NRO, :muestra) = 1;";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":resultado", $resultado);
            $psInsertar->bindParam(":idoperativo", $idoperativo);
            $psInsertar->bindParam(":muestra", $muestra);

            // ejecutamos la edición
            $psInsertar->execute();

            // obtenemos el número de registros modificados
            return $psInsertar->rowCount();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            echo $e->getMessage();

        }

    }

    /**
     * Método que retorna un vector con los años en que se han realizado
     * operativos de control de calidad, y en los que los operativos
     * están cerrados, utilizado para la generación de reportes y
     * certificados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tipo - tipo de operativos
     * @return array
     */
    public function listaAnios(string $tipo) : array {

        // componemos la consulta
        $consulta = "SELECT DISTINCT(RIGHT(cce.vw_operativos.operativo,4)) AS anio
                     FROM cce.vw_operativos
                     WHERE cce.vw_operativos.abierto = 'No' AND 
                           cce.vw_operativos.tipo = '$tipo'
                     ORDER BY RIGHT(cce.vw_operativos.operativo,4) DESC;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // obtenemos el vector y retornamos
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que retorna un vector con los años en que se han realizado
     * operativos de control de calidad, y en los que los operativos
     * están abiertos, utilizado para la eliminación de etiquetas
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tipo - tipo de operativo a listar
     * @return array
     */
    public function listaAniosAbiertos(string $tipo) : array {

        // componemos la consulta
        $consulta = "SELECT DISTINCT(RIGHT(cce.vw_operativos.operativo,4)) AS anio
                     FROM cce.vw_operativos
                     WHERE cce.vw_operativos.abierto = 'Si' AND 
                           cce.vw_operativos.tipo = '$tipo'
                     ORDER BY RIGHT(cce.vw_operativos.operativo,4) DESC;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // lo pasamos a minúsculas porque según la versión de
        // pdo lo devuelve en mayúsculas o minúsculas
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

}
?>
