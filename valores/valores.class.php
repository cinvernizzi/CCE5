<?php

/**
 *
 * Class Valores | valores/valores.class.php
 *
 * @package     CCE
 * @subpackage  Valores
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.5.0 (23/03/2023)
 * @copyright   Copyright (c) 2018, INP
 *
 * Cláse que contiene las operaciones sobre la base de datos
 * de valores aceptados para cada técnica
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
class Valores {

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos

    // variables de la tabla de valores aceptados
    protected $IdValor;                  // clave del registro
    protected $IdTecnica;                // clave de la técnica
    protected $Tecnica;                  // nombre de la técnica
    protected $Valor;                    // valor aceptado
    protected $Usuario;                  // nombre del usuario
    protected $IdUsuario;                // clave del usuario
    protected $Fecha;                    // fecha de alta

    // definición de variables
    protected $Link;                     // puntero a la base de datos

    /**
     * Constructor de la clase, establece la conexión con la base
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __construct (){

        // nos conectamos a la base de datos
        $this->Link = new Conexion();

        // inicializamos las variables de clase
        $this->IdValor = 0;
        $this->IdTecnica = 0;
        $this->Valor = "";
        $this->Usuario = "";
        $this->IdUsuario = 0;
        $this->Fecha = "";

    }

    /**
     * Destructor de la clase, cierra el puntero a la base
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __destruct(){

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos públicos de asignación de valores
    public function setIdValor(int $idvalor){
        $this->IdValor = $idvalor;
    }
    public function setIdTecnica(int $idtecnica){
        $this->IdTecnica = $idtecnica;
   }
    public function setValor(string $valor){
        $this->Valor = $valor;
    }
    public function setIdUsuario(int $idusuario){
        $this->IdUsuario = $idusuario;
    }

    // métodos de retorno de valores
    public function getIdValor() : int {
        return (int) $this->IdValor;
    }
    public function getIdTecnica() : int {
        return (int) $this->IdTecnica;
    }
    public function getTecnica() : string {
        return $this->Tecnica;
    }
    public function getValor() : string {
        return $this->Valor;
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
     * Método que recibe el nombre de una técnica y retorna el array
     * de valores aceptados
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int tecnica - clave de la técnica
     * @return array
     */
    public function nominaValores(int $tecnica) : array {

        // armamos la consulta
        $consulta = "SELECT cce.vw_valores.valor AS valor,
                            cce.vw_valores.id AS id,
                            cce.vw_valores.tecnica AS tecnica,
                            cce.vw_valores.idtecnica AS idtecnica,
                            cce.vw_valores.fecha AS fecha,
                            cce.vw_valores.usuario AS usuario,
                            cce.vw_valores.idusuario AS idusuario
                     FROM cce.vw_valores
                     WHERE cce.vw_valores.idtecnica = '$tecnica';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que recibe como parámetro la clave de un registro y 
     * asigna en las variables de clase los valores de la base
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idvalor - clave del registro
     */
    public function getDatosValor(int $idvalor){

        // armamos la consulta
        $consulta = "SELECT cce.vw_valores.valor AS valor,
                            cce.vw_valores.id AS id,
                            cce.vw_valores.tecnica AS tecnica,
                            cce.vw_valores.idtecnica AS idtecnica,
                            cce.vw_valores.fecha AS fecha,
                            cce.vw_valores.usuario AS usuario,
                            cce.vw_valores.idusuario AS idusuario
                     FROM cce.vw_valores
                     WHERE cce.vw_valores.id = '$idvalor';";

        // ejecutamos la consulta y asignamos
        $resultado = $this->Link->query($consulta);
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        $this->IdValor = $fila["id"];
        $this->Valor = $fila["valor"];
        $this->Tecnica = $fila["tecnica"];
        $this->IdTecnica = $fila["idtecnica"];
        $this->Fecha = $fila ["fecha"];
        $this->Usuario = $fila["usuario"];

    }

    /**
     * Método que graba los datos del valor aceptado de la
     * técnica en la base de datos, retorna la clave del
     * registro afectado
     * @return int
     */
    public function grabaValores() : int {

        // si no recibió la clave
        if ($this->IdValor == 0){
            $this->nuevoValor();
        } else {
            $this->editaValor();
        }

        // retornamos la id
        return (int) $this->IdValor;

    }

    /**
     * Método que inserta un nuevo registro en la tabla de valores
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function nuevoValor(){

        // compone la consulta de inserción
        $consulta = "INSERT INTO cce.valores_tecnicas
                                (TECNICA,
                                 USUARIO,
                                 VALOR)
                                VALUES
                                (:idtecnica,
                                 :idusuario,
                                 :valor);";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":idtecnica", $this->IdTecnica);
            $psInsertar->bindParam(":idusuario", $this->IdUsuario);
            $psInsertar->bindParam(":valor",     $this->Valor);

            // ejecutamos la edición
            $psInsertar->execute();

            // obtiene la id del registro insertado
            $this->IdValor = $this->Link->lastInsertId();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            $this->IdValor = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método que edita el registro de la tabla de valores
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function editaValor(){

        // compone la consulta de edición
        $consulta = "UPDATE cce.valores_tecnicas SET
                            TECNICA = :idtecnica,
                            USUARIO = :idusuario,
                            VALOR = :valor
                     WHERE cce.valores_tecnicas.ID = :idvalor;";

        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":idtecnica", $this->IdTecnica);
            $psInsertar->bindParam(":idusuario", $this->IdUsuario);
            $psInsertar->bindParam(":valor",     $this->Valor);
            $psInsertar->bindParam(":idvalor",   $this->IdValor);

            // ejecutamos la edición
            $psInsertar->execute();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje
            $this->IdValor = 0;
            echo $e->getMessage();

        }

    }

    /**
     * Método que recibe como parámetro la clave de una técnica y el
     * valor correcto, verifica si ese valor ya está declarado, y
     * retorna el número de registros
     * @param int $tecnica - clave de la técnica
     * @param string $valor - valor a declarar
     * @return int
     */
    public function verificaValor(int $tecnica, string $valor) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(cce.valores_tecnicas.ID) AS registros
                     FROM cce.valores_tecnicas
                     WHERE cce.valores_tecnicas.tecnica = '$tecnica' AND
                           cce.valores_tecnicas.valor = '$valor';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el registro
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);
        return $fila["registros"];

    }

    /**
     * Método que recibe como parámetro la clave de un registro
     * y ejecuta la consulta de eliminación, retorna el resultado
     * de la operación
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $idvalor - clave del registro
     * @return bool resultado de la operación
     */
    public function borraValor(int $idvalor) : bool {

        // declaramos las variables
        $resultado = false;

        // componemos la consulta
        $consulta = "DELETE FROM cce.valores_tecnicas 
                     WHERE cce.valores_tecnicas.id = :id;";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los parámetros de la consulta
            $psInsertar->bindParam(":id", $idvalor);

            // ejecutamos la edición
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
     * Método que recibe como parámetro la clave de un valor y 
     * verifica si puede eliminar el registro, retorna la cantidad
     * de registros encontrados en las determinaciones
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int - clave de la técnica
     * @param string - valor a eliminar
     * @return int registros encontrados
     */
    public function puedeBorrar(int $idtecnica, string $valor) : int {

        // componemos la consulta
        $consulta = "SELECT COUNT(cce.chag_datos.id) AS registro1s 
                     FROM cce.chag_datos
                     WHERE cce.chag_datos.valor_corte = '$valor' AND 
                           cce.chag_datos.tecnica = '$idtecnica';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // obtenemos el registro y retornamos
        $registro = $resultado->fetch(PDO::FETCH_ASSOC);
        return (int) $registro["registros"];

    }

}
?>
