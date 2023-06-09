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
        this.Jurisdiccion = "";            // nombre de la jurisdicción
        this.CodProv = "";                 // clave indec de la provincia
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

        // creamos el div
        $("#form_usuarios").append("<div id='win-seguridad'></div>");

        // definimos el diálogo y lo mostramos
        $('#win-seguridad').window({
            width:500,
            height:390,
            modal:true,
            minimizable: false,
            maximizable: false,
            collapsible: false,
            title: "Ingreso al Sistema",
            closable: false,
            href: 'seguridad/ingreso.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-seguridad').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario de 
     * ingreso que configura los componentes
     */
    initFormIngreso(){

        // declaramos la clase
        var clase = this;
        
        // el nombre de usuario
        $('#loginusuario').textbox({
            prompt:'Usuario',
            iconCls:'icon-man',
            iconAlign:'right',
            width: '120px'
        });

        // la contraseña
        $('#loginpassword').passwordbox({
            prompt: 'Contraseña',
            showEye: true,
            width: '120px'
        });

        // el botón aceptar
        $('#btnIngreso').linkbutton({
            iconCls: 'icon-confirmar',
            width: '100px',
            height: '30px',
            text: 'Ingresar'
        });

        // asociamos el evento key down del nombre de usuario
        $('#loginusuario').textbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){
                $('#loginpassword').passwordbox('textbox').focus();
            }
        });	

        // asociamos el evento key down del password
        $('#loginpassword').passwordbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){
                clase.verificaIngreso();
            }
        });	

        // fijamos el foco
        $('#loginusuario').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón aceptar que 
     * verifica el formulario de ingreso
     */
    verificaIngreso(){

        // verificamos los datos del formulario
        var usuario = $('#loginusuario').textbox('getValue');
        var password = $('#loginpassword').passwordbox('getValue');

        // si no ingresó el usuario
        if (usuario == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese su nombre de Usuario !!!");

        // si no ingresó la contraseña
        } else if (password == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Debe ingresar su contraseña !!!");

        // si está correcto
        } else {

            // verificamos las credenciales
            this.validaIngreso(usuario, password);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {string} usuario - el nombre de usuario
     * @param {string} password - la contraseña ingresada
     * Método llamado luego de verificar el formulario de 
     * ingreso que verifica los datos en el servidor
     */
    validaIngreso(usuario, password){

        // declaramos la clase
        var clase = this;

        // lo llamamos asincrónico
        $.ajax({
        url: "seguridad/validar.php?usuario="+usuario+"&password="+password,
        type: "GET",
        cahe: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data) {

            // si encontró el usuario
            if (data.Resultado){

                // asignamos en la sesión
                clase.asignaIngreso(data);

            // si hubo un error
            } else {

                // presenta el mensaje
                Mensaje("Error", "Atención", "Hay un error en las credenciales");

            }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {array} datos - vector con los datos de ingreso
     * Método que recibe el array con los datos del ingreso
     * y los asigna a las variables de clase
     */
    asignaIngreso(datos){

        this.Id = datos.Id;
        this.ResponsableChagas = datos.Responsable;
        this.ResponsableLeish = datos.Leish;
        this.IdLaboratorio = datos.IdLaboratorio;
        this.Jurisdiccion = datos.Jurisdiccion;
        this.CodProv = datos.CodProv;
        this.Activo = "Si";
        this.Usuario = datos.Usuario;
        this.NivelCentral = datos.NivelCentral;

        // si no es de nivel central ocultamos el tabulador
        // de administración
        if (this.NivelCentral != "Si"){
            $('#tabulador').tabs('close', "Administración");
        }

        // cargamos los datos del usuario en el formulario

        // cerramos el layer
        this.cerrarEmergente();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el enlace de recuperación 
     * de contrasña, cierra el layer de ingreso y luego 
     * presenta el formulario de ingreso de correo
     */
    recuperaPassword(){

        // destruimos el layer
        this.cerrarEmergente();

        // creamos el div
        $("#form_usuarios").append("<div id='win-seguridad'></div>");

        // pedimos ingrese el mail
        $('#win-seguridad').window({
            width:400,
            height:250,
            modal:true,
            title: "Recuperar Contraseña",
            minimizable: false,
            closable: false,
            href: 'seguridad/recuperar.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-usuarios').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón confirmar del 
     * formulario de recuperación de contraseñá que 
     * verifica que esté completado correctamente
     */
    verificaRecuperacion(){

      // obtenemos el valor
      var mail = $('#recuperamail').textbox('getValue');

      // si no ingresó el mail
      if (mail == ""){

          // presenta el mensaje
          Mensaje("Error", "Atención", "Debe ingresar un mail válido");

      // verifica el mail
      } else if (!echeck(mail)){

          // presenta el mensaje
          Mensaje("Error", "Atención", "El mail parece incorrecto");

      // si llegó hasta aquí
      } else {

          // enviamos el mail
          this.enviaRecuperacion(mail);

      }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {string} mail - dirección de correo
     * Método llamado luego de verificar el formulario de 
     * recuperación que envía los datos al servidor, el 
     * que reinicia la contraseña y envía el mail en caso
     * que el correo de recuperación sea correcto o 
     * retorna falso en caso que el mail no se encuentra
     */
    enviaRecuperacion(mail){

        // definimos la clase
        var clase = this;

        // verificamos que el mail exista
        $.ajax({
            url: "seguridad/recuperamail.php?mail="+mail,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si encontró el usuario
                if (data.Resultado){

                    // enviamos el correo
                    clase.enviaCorreo(data.Usuario, data.Password, mail);

                    // cerramos el diálogo
                    clase.cierraRecuperacion();

                // si hubo un error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "El correo no está registrado");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {string} usuario - nombre del usuario
     * @param {string} password - password generado
     * @param {string} mail - correo del usuario
     * Método llamado luego de recuperar la contraseña
     * que envía el correo al usuario informando de
     * sus credenciales de acceso
     */
    enviaCorreo(usuario, password, mail){

        // enviamos el correo
        $.ajax({
            url: "seguridad/enviamail.php?mail="+mail+"&usuario="+usuario+"&password="+password,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si pudo enviar
                if (data.Resultado){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Se ha enviado un correo a " + mail);

                // si hubo un error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Ocurrió un error al enviar el correo");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón cancelar en la 
     * recuperación de contraseña, o al enviar el correo 
     * con los datos, cierra el formulario emergente y 
     * recarga el pedido de contraseña
     */
    cierraRecuperacion(){

        // cerramos el layer 
        this.cerrarEmergente();

        // pedimos usuario y pass
        this.verFormIngreso();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el diálogo emergente pidiendo el
     * nuevo password
     */
    nuevoPassword(){

        // reiniciamos la sesión
        sesion.reiniciar();
 
        // creamos el div
        $("#form_usuarios").append("<div id='win-seguridad'></div>");
        
        // definimos el diálogo y lo mostramos
        $('#win-seguridad').window({
            width:400,
            height:290,
            modal:true,
            title: "Nuevo Password",
            minimizable: false,
            closable: true,
            href: 'seguridad/password.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-seguridad').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al cargar el formulario de cambio de 
     * contraseña que configura los eventos del mismo
     */
    initFormPassword(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // definimos la clase
        var clase = this;

        // configuramos los elementos del formulario
        $('#passwordactual').textbox({});
        $('#passwordnuevo').textbox({});
        $('#passwordverifica').textbox({});        
        $('#btnNuevoPassword').linkbutton({});
        $('#btnCancelaPassword').linkbutton({});

        // asociamos el evento key down de la contraseña actual
        $('#passwordactual').textbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){
                $('#passwordnuevo').textbox('textbox').focus();
            }
        });	

        // asociamos el evento key down de la nueva contraseña
        $('#passwordnuevo').textbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){
                $('#passwordverifica').textbox('textbox').focus();
            }
        });	
        
        // asociamos el evento key down de la repetición
        $('#passwordverifica').textbox('textbox').bind('keydown', function(e){
            if (e.keyCode == 13){
                clase.cambiarPassword();
            }
        });	

        // fijamos el foco en el campo 
        $('#passwordactual').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que verifica el formulario de cambio de
     * contraseña antes de enviarlo al servidor
     */
    cambiarPassword(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // verificamos si indicó el password actual
        if ($('#passwordactual').textbox('getValue') == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Debe ingresar su contraseña");
            return;

        }

        // verificamos si ingresó el nuevo password
        if ($('#passwordnuevo').textbox('getValue') == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese el nuevo password");
            return;

        }

        // verificamos si coincide
        if ($('#passwordnuevo').textbox('getValue') != $('#passwordverifica').textbox('getValue')){

            // presenta el mensaje
            Mensaje("Error", "Atención", "La contraseña y la verificación<br>no coinciden");
            return;

        }

        // verificamos si el password actual coincide
        this.validaPassword();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que verifica que en el cambio de contraseña
     * el password actual coincida con el declarado en
     * la base, en caso de coincidir actualiza
     */
    validaPassword(){

        // reiniciamos la sesion
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // obtenemos el password actual
        var passwordactual = $('#passwordactual').textbox('getValue');

        // validamos que sea correcto
        $.ajax({
            url: "seguridad/validapassword.php?idusuario="+this.Id+"&password="+passwordactual,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si coincide
                if (data.Resultado != 0){

                    // asignamos en la sesión
                    clase.grabaPassword();

                // si hubo un error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "El password actual no coincide");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que actualiza el password de ingreso
     */
    grabaPassword(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // obtenemos el usuario y el nuevo pass
        var password = $('#passwordnuevo').textbox('getValue');

        // actualizamos el password
        $.ajax({
            url: "seguridad/grabapassword.php?idusuario="+this.Id+"&password="+password,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si pudo grabar
                if (data.Resultado){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Contraseña actualizada");

                    // cierra el diálogo
                    clase.cerrarEmergente();

                // si hubo un error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Ha ocurrido un error");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método genérico que cierra el formulario emergente
     */
    cerrarEmergente(){

        // destruimos el layer
        $('#win-seguridad').window('destroy');

    }

    /**
     * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que cierra la sesión de usuario
     */
    salir(){

        // reinicia las variables de clase
        this.initSeguridad();

    }

}