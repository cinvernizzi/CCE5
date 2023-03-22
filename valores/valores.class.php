<?php

/**
 *
 * Class Valores | valores/valores.class.php
 *
 * @package     CCE
 * @subpackage  Valores
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.4.0 (01/03/2018)
 * @copyright   Copyright (c) 2018, INP
 *
 * Cláse que contiene las operaciones sobre la base de datos
 * de valores aceptados para cada técnica
 *
*/

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
    protected $IdTecnicaValor;           // clave de la técnica
    protected $Valor;                    // valor aceptado
    protected $IdUsuario;                // clave del usuario

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
        $this->IdTecnicaValor = 0;
        $this->Valor = "";

        // iniciamos sesión
        session_start();

        // si existe la sesión
        if(isset($_SESSION["ID"])){

            // obtenemos la id del usuario
            $this->IdUsuario = $_SESSION["ID"];

        }

        // cerramos sesión
        session_write_close();

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
    public function setIdValor($idvalor){
        $this->IdValor = $idvalor;
    }
    public function setIdTecnicaValor($idtecnicavalor){

        // verifica que sea un número
        if (!is_numeric($idtecnicavalor)){

            // abandona por error
            echo "La clave de la técnica debe ser un número";
            exit;

        // si está correcto
        } else {

            // lo asigna
            $this->IdTecnicaValor = $idtecnicavalor;

        }

    }
    public function setValor($valor){
        $this->Valor = $valor;
    }

    /**
     * Método que recibe el nombre de una técnica y retorna el array
     * de valores aceptados
     * @param int tecnica - clave de la técnica
     * @return array
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    public function nominaValores($tecnica){

        // armamos la consulta
        $consulta = "SELECT cce.valores_tecnicas.VALOR AS valor_tecnica,
                            cce.valores_tecnicas.ID AS id_valor,
                            cce.valores_tecnicas.TECNICA AS id_tecnica,
                            DATE_FORMAT(cce.valores_tecnicas.FECHA_ALTA, '%d/%m/%Y') AS fecha_alta,
                            cce.responsables.USUARIO AS usuario
                     FROM cce.valores_tecnicas INNER JOIN cce.tecnicas ON cce.valores_tecnicas.TECNICA = cce.tecnicas.ID
                                               INNER JOIN cce.responsables ON cce.valores_tecnicas.USUARIO = cce.responsables.ID
                     WHERE cce.tecnicas.ID = '$tecnica';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que graba los datos del valor aceptado de la
     * técnica en la base de datos, retorna la clave del
     * registro afectado
     * @return int
     */
    public function grabaValores(){

        // si no recibió la clave
        if ($this->IdValor == 0){
            $this->nuevoValor();
        } else {
            $this->editaValor();
        }

        // retornamos la id
        return $this->IdValor;

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
                                (:idtecnicavalor,
                                 :idusuario,
                                 :valor);";

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asignamos los parámetros de la consulta
        $psInsertar->bindParam(":idtecnicavalor", $this->IdTecnicaValor);
        $psInsertar->bindParam(":idusuario", $this->IdUsuario);
        $psInsertar->bindParam(":valor", $this->Valor);

        // ejecutamos la edición
        $resultado = $psInsertar->execute();

        // si salió todo bien
        if ($resultado){

            // obtiene la id del registro insertado
            $this->IdValor = $this->Link->lastInsertId();

        // si hubo un error
        } else {

            // inicializa la clave y retorna el error
            $this->IdValor = 0;
            echo $resultado;

        }

    }

    /**
     * Método que edita el registro de la tabla de valores
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    protected function editaValor(){

        // compone la consulta de edición
        $consulta = "UPDATE cce.valores_tecnicas SET
                            TECNICA = :idtecnicavalor,
                            USUARIO = :idusuario,
                            VALOR = :valor
                     WHERE cce.valores_tecnicas.ID = :idvalor;";

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asignamos los parámetros de la consulta
        $psInsertar->bindParam(":idtecnicavalor", $this->IdTecnicaValor);
        $psInsertar->bindParam(":idusuario", $this->IdUsuario);
        $psInsertar->bindParam(":valor", $this->Valor);
        $psInsertar->bindParam(":idvalor", $this->IdValor);

        // ejecutamos la edición
        $resultado = $psInsertar->execute();

        // si hubo un error
        if(!$resultado){

            // inicializamos la clave y retornamos el error
            $this->IdValor = 0;
            echo $resultado;

        }

    }

    /**
     * Método que recibe como parámetro la clave de una técnica y el
     * valor correcto, verifica si ese valor ya está declarado, y
     * retorna el número de registros
     * @param string $tecnica - nombre de la técnica
     * @param string $valor - valor a declarar
     * @return int
     */
    public function verificaValor($tecnica, $valor){

        // componemos la consulta
        $consulta = "SELECT COUNT(cce.valores_tecnicas.ID) AS registros
                     FROM cce.valores_tecnicas
                     WHERE cce.valores_tecnicas.tecnica = '$tecnica' AND
                           cce.valores_tecnicas.valor = '$valor';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        $fila = $resultado->fetch(PDO::FETCH_ASSOC);

        // obtenemos el registro y retornamos el vector
        return $fila["registros"];

    }

}
?>
