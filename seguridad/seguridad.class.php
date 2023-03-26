<?php

/**
 *
 * Class Seguridad | seguridad/seguridad.class.php
 *
 * @package     CCE
 * @subpackage  Seguridad
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     v.5.0 (23/03/2023)
 * @copyright   Copyright (c) 2018, INP
 *
 * Clae que contiene los métodos para la gestión de usuarios y
 * contraseñas del sistema
 *
*/

// declaramos el tipeado estricto
declare (strict_types=1);

// inclusión de archivos
require_once ("../clases/conexion.class.php");
require_once ("../clases/herramientas.class.php");

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
class Seguridad {

    // declaración de variables de la clase, las declaramos
    // como protected para que puedan ser heredadas pero
    // para asignarle el valor debemos crear los métodos
    protected $Link;                  // puntero a la base de datos
    protected $Id;                    // clave del registro
    protected $Usuario;               // nombre del usuario
    protected $Password;              // contraseña de acceso sin encriptar
    protected $NuevoPass;             // nuevo password del usuario
    protected $Jurisdiccion;          // jurisdicción del usuario
    protected $CodProv;               // código indec de la jurisdicción
    protected $Pais;                  // nombre del pais del usuario
    protected $IdPais;                // clave del país del usuario
    protected $NivelCentral;          // indica si es de nivel central
    protected $Responsable;           // indica si es responsable
    protected $Leish;                 // si es responsable de leismania
    protected $IdLaboratorio;         // laboratorio autorizado si no es
                                      // responsable
    /**
     * Constructor de la clase, establece la conexión con la base
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __construct (){

        // nos conectamos a la base de datos
        $this->Link = new Conexion();

        // inicializamos las variables
        $this->Id = 0;
        $this->Usuario = "";
        $this->Password = "";
        $this->NuevoPass = "";
        $this->Jurisdiccion = "";
        $this->CodProv = "";
        $this->Pais = "";
        $this->IdPais = 0;
        $this->NivelCentral = "";
        $this->Responsable = "";
        $this->Leish = "";
        $this->IdLaboratorio = 0;

    }

    /**
     * Destructor de la clase, cierra la conexión con la base
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     */
    function __destruct(){

        // elimina el enlace a la base
        $this->Link = null;

    }

    // métodos públicos de asignación de variables
    public function setId(int $id){
        $this->Id = $id;
    }
    public function setUsuario(string $usuario){
        $this->Usuario = $usuario;
    }
    public function setPassword(string $password){
        $this->Password = $password;
    }
    public function setNuevoPass(string $nuevopass){
        $this->NuevoPass = $nuevopass;
    }

    // métodos que retornan los valores
    public function getId() : int {
        return (int) $this->Id;
    }
    public function getNivelCentral() : string {
        return $this->NivelCentral;
    }
    public function getResponsable() : string {
        return $this->Responsable;
    }
    public function getLeish() : string {
        return $this->Leish;
    }
    public function getUsuario() : string {
        return $this->Usuario;
    }
    public function getJurisdiccion() : string {
        return $this->Jurisdiccion;
    }
    public function getCodProv() : string {
        return $this->CodProv;
    }
    public function getPais() : string {
        return $this->Pais;
    }
    public function getIdLaboratorio() : ?int {
        return $this->IdLaboratorio;
    }

    /**
     * Método que efectúa el cambio de contraseña en la base de datos
     * de usuarios, asume que ya fueron inicializadas las variables
     * de clase, verifica si los valores son correctos y actualiza
     * en la base de datos
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return integer 0 si hubo error, de otra forma la clave del 
     *         registro
     */
    public function nuevoPassword() : int {

        // encriptamos el nuevo password usando la función de mysql Y
        // componemos la consulta de actualización
        $consulta = "UPDATE cce.responsables SET
                            cce.responsables.password = MD5(:nuevopass)
                     WHERE cce.responsables.id = :id;";

        // capturamos el error
        try {

            // asignamos la consulta
            $psInsertar = $this->Link->prepare($consulta);

            // asignamos los valores
            $psInsertar->bindParam(":nuevopass", $this->NuevoPass);            
            $psInsertar->bindParam(":id", $this->Id);

            // ejecutamos la consulta y obtenemos la clave
            $psInsertar->execute();

        // si hubo un error
        } catch (PDOException $e) {

            // mostramos el mensaje y asignamos la clave
            $this->Id = 0;
            echo $e->getMessage();

        }

        // retornamos
        return (int) $this->Id;

    }

    /**
     * Método que verifica que exista el usuario en la base y que la
     * contraseña de ingreso sea correcta, en cuyo caso retorna
     * verdadero
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @return boolean
     */
    public function Validar(){

        // buscamos el usuario en la base controlando que coincida la
        // contraseña encriptada y que esté activo
        $consulta = "SELECT cce.vw_responsables.id AS id,
                            cce.vw_responsables.usuario AS usuario,
                            cce.vw_responsables.PASSWORD AS password,
                            cce.vw_responsables.responsable_chagas AS responsable,
                            cce.vw_responsables.responsable_leish AS leish,
                            cce.vw_responsables.idlaboratorio AS idlaboratorio,
                            cce.vw_responsables.provincia AS provincia,
                            cce.vw_responsables.idprovincia AS cod_provincia,
                            cce.vw_responsables.pais AS pais,
                            cce.vw_responsables.nivelcentral AS nivel_central
                     FROM cce.vw_responsables
                     WHERE cce.vw_responsables.USUARIO = '$this->Usuario' AND
                           cce.vw_responsables.PASSWORD = MD5('$this->Password') AND
                           cce.vw_responsables.ACTIVO = 'Si';";
        $resultado = $this->Link->query($consulta);

        // si encontró registros
        if ($resultado->rowCount() != 0){

            // obtenemos el vector
            $registro = $resultado->fetch(PDO::FETCH_ASSOC);

            // obtenemos el password de la base
            $this->Id = $registro["id"];
            $this->Usuario = $registro["usuario"];
            $this->Responsable = $registro["responsable"];
            $this->Leish = $registro["leish"];
            $this->IdLaboratorio = $registro["idlaboratorio"];
            $this->Jurisdiccion = $registro["provincia"];
            $this->CodProv = $registro["cod_provincia"];
            $this->Pais = $registro["pais"];
            $this->NivelCentral = $registro["nivel_central"];

            // retornamos verdadero
            return true;

        // si no encontró al usuario
        } else {

            // retorna falso
            return false;

        }

    }

    /**
     * Método que recibe como parámetro el mail de un usuario
     * verifica si está registrado y en ese caso actualiza
     * la contraseña de acceso, retorna el resutado de la 
     * operación y las credenciales de acceso
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $mail - correo del usuario 
     * @return array - vector con las credenciales
     */
    public function recuperaMail(string $mail) : array {
     
        // verificamos si el mail existe
        $resultado = $this->getIdCorreo($mail);

        // si encontró y está activo 
        if ($resultado["Resultado"] && $resultado["Activo"] == "Si"){

            // creamos una contraseña aleatoria
            $herramientas = new Herramientas();
            $contrasenia = $herramientas->generaPass();

            // asignamos en la clase
            $this->Id = (int) $resultado["IdUsuario"];
            $this->NuevoPass = $contrasenia;

            // actualizamos la contraseña
            $this->nuevoPassword();

            // retornamos el array
            return array("Resultado" => true,
                         "Usuario" => $resultado["Usuario"],
                         "Password" => $contrasenia);

        // si no encontró
        } else {
            return array("Resultado" => false);
        }

    }

   /**
     * Método que recibe una dirección de correo (la cual es única)
     * retorna el vector con los datos del usuario para enviarlos
     * por mail
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param string $mail - correo a buscar en la base
     * @return array vector con los datos
     */
    public function getIdCorreo(string $mail) : array {
       
        // componemos la consulta, que exista el mail ya lo
        // verificamos antes
        $consulta = "SELECT cce.vw_responsables.id AS id,
                            cce.vw_responsables.responsable AS responsable,
                            cce.vw_responsables.institucion AS institucion,
                            cce.vw_responsables.activo AS activo,
                            cce.vw_responsables.usuario AS usuario
                     FROM cce.vw_responsables
                     WHERE cce.vw_responsables.mail = '$mail';";

        // ejecutamos la consulta
        $resultado = $this->Link->query($consulta);

        // si hay registros
        if ($resultado->rowCount() != 0){

            // obtenemos el registro
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);

            // retornamos el vector
            return array("Resultado" => true,
                         "IdUsuario" => $fila["id"],
                         "Nombre" => $fila["responsable"],
                         "Institucion" => $fila["institucion"],
                         "Activo" => $fila["activo"],
                         "Usuario" => $fila["usuario"]);

        // si no encontró registros
        } else {
            return array("Resultado" => false);
        }

    }    

}
?>
