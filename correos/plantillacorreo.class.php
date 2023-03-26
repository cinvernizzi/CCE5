<?php

/*

    Archivo: correo.class.php
    Autor: Claudio Invernizzi
    Fecha: 06/04/2018
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Efimeral
    Licencia: GPL
    Comentarios: Clase que extiende al phpmailer para autocargar la plantilla
                 del grupo y luego reemplazar por el contenido

*/

// inclusión de las clases de correo
require_once ("PHPMailerAutoload.php");

// convención para la nomenclatura de las propiedades, comienzan con una
// letra mayúscula, de tener mas de una palabra no se utilizan separadores
// y la inicial de cada palabra va en mayúscula
// para las variables recibidas como parámetro el criterio es todas en
// minúscula

// convención para la nomenclatura de los metodos, comienzan con set o get
// según asignen un valor o lo lean y luego el nombre del valor a obtener

/**
 * @author Claudio Invernizzi <cinvernizzi@gmail.com>
 * Definición de la clase, esta clase extiende phpmailer para cargar una
 * plantilla de correo
 */
Class Plantilla Extends PHPMailer {

    // definimos las variables
    protected $Plantilla;                  // plantilla de correo

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Constructor de la clase, cargamos la plantilla y definimos
     * por defecto las propiedades del mensaje (servidor de correo
     * origen, etc.)
     */
    public function __construct(){

        // lee el archivo con el mensaje del disco y lo escapa
        $this->Plantilla = file_get_contents("../correos/correo.html");

        // indicamos que usamos smtp
        $this->isSMTP();

        // Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $this->SMTPDebug = 0;

        // Ask for HTML-friendly debug output
        $this->Debugoutput = 'html';

        // Set the hostname of the mail server
        $this->Host = 'SMTP.Office365.com';

        // seteamos el puerto smtp
        $this->Port = 587;

        // configuramos la encripción
        $this->SMTPSecure = 'tls';

        // usamos autenticación
        $this->SMTPAuth = true;

        // usuario de outlook
        $this->Username = "cce.inp@outlook.com.ar";

        // password de gmail
        $this->Password = "pickard47alfatango";

        // from del mensaje
        $this->setFrom('cce.inp@outlook.com.ar', 'Instituto Nacional de Parasitologia');

        // responder a del mensaje
        $this->addReplyTo('cce.inp@outlook.com.ar', 'Instituto Nacional de Parasitologia');

        // el tema del mensaje
        $this->Subject = "Sistema de Control de Calidad Externo";

    }

    /**
     * Método que recibe como parámetro una cadena con el texto a enviar
     * y lo asigna en la variable de clase
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {string} mensaje
     */
    public function setMensaje($mensaje){

        // reemplaza el cuerpo del mensaje
        $mensaje = str_replace("mensaje_correo", $mensaje, $this->Plantilla);

        // cargamos el contenido del texto
        $this->msgHTML($mensaje);

    }

}
?>
