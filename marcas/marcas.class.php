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
    protected $IdUsuario;              // clave del usuario

    // constructor de la clase
    function __construct (){

        // nos conectamos a la base de datos
        $this->Link = new Conexion();

        // inicializamos las variables de clase
        $this->IdMarca = 0;
        $this->Marca = "";
        $this->IdTecnica = 0;

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

    // destructor de la clase
    function __destruct(){

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos de asignación de variables
    public function setIdMarca($idmarca){

        // verifica que sea un número
        if (!is_numeric($idmarca)){

            // cierra con error
            echo "La clave de la marca debe ser un número";
            exit;

        // si es correcto
        } else {

            // asigna en la clase
            $this->IdMarca = $idmarca;

        }

    }
    public function setIdTecnica($idtecnica){

        // verifica que sea un número
        if (!is_numeric($idtecnica)){

            // cierra con error
            echo "La clave de la técnica debe ser un número";
            exit;

        // si está correcto
        } else {

            // lo asigna
            $this->IdTecnica = $idtecnica;

        }

    }
    public function setMarca($marca){
        $this->Marca = $marca;
    }

    /**
     * Método que retorna el listado de las marcas, recibe como parámetro
     * la id de la técnica, retorna la id de la marca, que
     * es utilizada en el abm de marcas
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param int $tecnica - la id de una técnica
     * @return array
     */
    public function nominaMarcas($tecnica){

        // componemos la consulta
        $consulta = "SELECT cce.marcas_chagas.MARCA AS marca,
                            cce.marcas_chagas.ID AS id_marca,
                            cce.marcas_chagas.TECNICA AS id_tecnica,
                            DATE_FORMAT(cce.marcas_chagas.FECHA_ALTA, '%d/%m/%Y') AS fecha_alta,
                            cce.responsables.USUARIO AS usuario
                     FROM cce.marcas_chagas INNER JOIN cce.responsables ON cce.marcas_chagas.USUARIO = cce.responsables.ID
                     WHERE cce.marcas_chagas.TECNICA = '$tecnica'
                     ORDER BY cce.marcas_chagas.MARCA;";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // retornamos el vector
        return $resultado->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * Método que retorna la clave de una marca, recibe como parámetro
     * la id de la técnica y la cadena con la marca
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $tecnica - nombre de la técnica
     * @param string $marca - nombre de la marca
     * @return int
     */
    public function getClaveMarca($tecnica, $marca){

        // componemos la consulta
        $consulta = "SELECT cce.marcas_chagas.ID AS id_marca
                     FROM cce.marcas_chagas
                     WHERE cce.marcas_chagas.TECNICA = '$tecnica' AND
                           cce.marcas_chagas.MARCA = '$marca'; ";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // si hubo registros (porque también lo utilizamos para verificar
        // si una marca no se encuentra repetida)
        if ($resultado->rowCount() != 0){

            // lo pasamos a minúsculas porque según la versión de
            // pdo lo devuelve en mayúsculas o minúsculas
            $registro = $resultado->fetch(PDO::FETCH_ASSOC);

            // retornamos el vector
            return $registro["id_marca"];

        // si no hubo registros
        } else {

            // retorna falso
            return false;

        }

    }

    /**
     * Función que actualiza la base de datos de marcas, retorna la
     * clave del registro afectado
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return int
     */
    public function grabaMarca(){

        // si està insertando
        if ($this->IdMarca == 0){
            $this->nuevaMarca();
        } else {
            $this->editaMarca();
        }

        // retorna la id del registro
        return $this->IdMarca;

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

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asignamos los parámetros de la consulta
        $psInsertar->bindParam(":marca", $this->Marca);
        $psInsertar->bindParam(":idtecnica", $this->IdTecnica);
        $psInsertar->bindParam(":idusuario", $this->IdUsuario);

        // ejecutamos la edición
        $resultado = $psInsertar->execute();

        // si salió todo bien
        if ($resultado){

            // obtiene la id del registro insertado
            $this->IdMarca = $this->Link->lastInsertId();

        // si hubo un error
        } else {

            // inicializa la clave y retorna el error
            $this->IdMarca = 0;
            echo $resultado;

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

        // asignamos la consulta
        $psInsertar = $this->Link->prepare($consulta);

        // asignamos los parámetros de la consulta
        $psInsertar->bindParam(":marca", $this->Marca);
        $psInsertar->bindParam(":idtecnica", $this->IdTecnica);
        $psInsertar->bindParam(":idusuario", $this->IdUsuario);
        $psInsertar->bindParam(":idmarca", $this->IdMarca);

        // ejecutamos la edición
        $resultado = $psInsertar->execute();

        // si hubo un error
        if (!$resultado){

            // inicializamos la clave y retornamos el error
            $this->IdMarca = 0;
            echo $resultado;

        }

    }

}
