/*

    Nombre: seguridad.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 24/03/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla el acceso de los usuarios 
                 autorizados

*/

/**
 *
 * Atención, utilizamos el estandard definido en ECMAScript 2015 (ES6)
 *
 */

/*jshint esversion: 6 */

/**
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @class Clase que controla el acceso de los usuarios 
 *        autorizados
 */
class Seguridad {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initSeguridad();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa las variables de clase y 
     * luego abre el formulario de acceso
     */
    initSeguridad(){

        // inicializamos las variables
        this.Id = 0;                       // clave del usuario
        this.ResponsableChagas = "No";     // si es responsable jurisdiccional de chagas
        this.ResponsableLeish = "No";      // si es responsable jurisdiccional de leismania
        this.Laboratorio = 0;              // clave del laboratorio al que pertenece
        this.Activo = "No";                // indica si está activo
        this.Usuario = "";                 // nombre de usuario
        this.NivelCentral = "No";          // si pertenece al nivel central

        // ahora mostramos el formulario de ingreso
        this.verFormIngreso();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el layer emergente con el formulario
     * de ingreso
     */
    verFormIngreso(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario de 
     * ingreso que configura los componentes
     */
    initFormIngreso(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón aceptar que 
     * verifica el formulario de ingreso
     */
    verificaIngreso(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de verificar el formulario de 
     * ingreso que verifica los datos en el servidor
     */
    validaIngreso(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {array} datos - vector con los datos de ingreso
     * Método que recibe el array con los datos del ingreso
     * y los asigna a las variables de clase
     */
    asignaIngreso(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el enlace de recuperación 
     * de contrasña, cierra el layer de ingreso y luego 
     * presenta el formulario de ingreso de correo
     */
    recuperaPassword(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que configura el formulario de recuperación 
     * de contraseña
     */
    initFormRecuperacion(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón confirmar del 
     * formulario de recuperación de contraseñá que 
     * verifica que esté completado correctamente
     */
    verificaRecuperacion(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de verificar el formulario de 
     * recuperación que envía los datos al servidor, el 
     * que reinicia la contraseña y envía el mail en caso
     * que el correo de recuperación sea correcto o 
     * retorna falso en caso que el mail no se encuentra
     */
    enviaRecuperacion(){

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método genérico que cierra el formulario emergente
     */
    cerrarEmergente(){

    }
    
}