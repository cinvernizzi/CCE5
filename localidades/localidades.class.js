/*

    Nombre: localidades.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 15/11/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla las operaciones del abm de
                 localidades censales

*/

/**
 *
 * Atención, utilizamos el estandard definido en ECMAScript 2015 (ES6)
 *
 */

/*jshint esversion: 6 */

/**
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @class Clase que controla las operaciones del formulario
 *        de localidades censales
 */
class Localidades {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initLocalidades();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa las variables de clase
     */
    initLocalidades(){

        // inicializamos las variables
        this.Id = 0;                    // clave del registro
        this.CodPcia = "";              // clave de la provincia
        this.Localidad = "";            // nombre de la localidad
        this.CodLoc = "";               // código indec de la localidad
        this.Poblacion = 0;             // población de la localidad
        this.Usuario = "";              // usuario de la localidad
        this.Fecha = "";                // fecha de alta del registro

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que carga la definición de la grilla de
     * localidades
     */
    verGrillaLocalidades(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // cargamos el formulario
        $("#form_administracion").load("localidades/grillalocalidades.html");

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario con
     * la definición de la grilla que carga la tabla
     */
     initGrillaLocalidades(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // definimos la clase y asignamos el usuario
        var clase = this;

         // definimos el estilo de la barra de herramientas
         $('#btnAyudaLocalidades').linkbutton({});
         $('#btnNuevaLocalidad').linkbutton({});
         $('#paislocalidad').combobox({
             url:'paises/nominapaises.php',
             valueField:'idpais',
             textField:'pais',
             onSelect: (function(rec){

                // cargamos el combo de provincias
                var url = 'jurisdicciones/nominajurisdicciones.php?idpais='+rec.idpais;
                $('#provincialocalidad').combobox('reload', url);

             })
         });
         $('#provincialocalidad').combobox({
            valueField:'idprovincia',
            textField:'provincia',
            onSelect: (function(rec){
                clase.filtraLocalidades(rec.idprovincia);
            })
        });

        // definimos la tabla
        $('#grilla-localidades').datagrid({
            title: "Localidades Censales",
            loadMsg: 'Cargando ...',
            singleSelect: true,
            onClickCell: function(index,field){
                clase.eventoGrillaLocalidades(index, field);
            },
            url:'localidades/localidadespaginadas.php',
            remoteSort: false,
            pagination: true,
            columns:[[
                {field:'id',title:'Id',width:50,align:'center'},
                {field:'idlocalidad',title:'CodLoc',width:100,sortable:true},
                {field:'localidad',title:'Localidad',width:200,sortable:true},
                {field:'codpcia',hidden:true},
                {field:'poblacion',title:'Población', width:100},
                {field:'usuario',title:'Usuario',width:100,align:'center'},
                {field:'fecha',title:'Fecha',width:100,align:'center'},
                {field:'editar',width:50,align:'center'},
                {field:'borrar',width:50,align:'center'}
            ]]
        });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idprovincia - clave indec de la provincia seleccionada
     * Método llamado al cambiar el valor del select con
     * la nómina de provincias que actualiza la grilla
     * según el valor seleccionado
     */
     filtraLocalidades(idprovincia){

        // reiniciamos la sesión
        sesion.reiniciar();

        // llamamos la rutina definida en el datagrid pasándole
        // los argumentos
        $('#grilla-localidades').datagrid('load',{
            codprov: idprovincia,
        });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * @param {string} field - campo pulsado
     * Método llamado al pulsar sobre la grilla que recibe
     * como parámetro la clave de la grilla y el nombre
     * del campo, según el valor de este, llama el
     * método de edición o el de eliminación
     */
     eventoGrillaLocalidades(index, field){

        // verificamos el nivel de acceso
        if (sessionStorage.getItem("Auxiliares") != "Si" || sessionStorage.getItem("NivelCentral") != "Si"){

            // presenta el mensaje y retorna
            var mensaje = "Usted no tiene permisos para<br>";
            mensaje += "añadir / editar diccionarios<br>";
            mensaje += "del sistema."
            $.messager.alert('Atención',mensaje);
            return;

        }

        // reiniciamos la sesión
        sesion.reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-localidades').datagrid('getRows')[index];

        // si pulsó en editar
        if (field == "editar"){

            // presentamos el registro
            this.getDatosLocalidad(index);

        // si pulsó sobre borrar
        } else if (field == "borrar"){

            // eliminamos
            this.puedeBorrar(row.idlocalidad, row.codpcia);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * Método que recibe como parámetro la clave de la
     * grilla de localidades y asigna en las variables de
     * clase los valores de los campos
     */
     getDatosLocalidad(index){

        // reiniciamos la sesión
        sesion.reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-localidades').datagrid('getRows')[index];

        // asignamos en las variables de clase
        this.Id = row.id;
        this.CodPcia = row.codpcia;
        this.Localidad = row.localidad;
        this.CodLoc = row.idlocalidad;
        this.Poblacion = row.poblacion;
        this.Usuario = row.usuario;
        this.Fecha = row.fecha;

        // cargamos el formulario
        this.formLocalidades();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el layer emergente con el formulario
     * de localidades censales
     */
     formLocalidades(){

        // verificamos el nivel de acceso
        if (sessionStorage.getItem("Auxiliares") != "Si" || sessionStorage.getItem("NivelCentral") != "Si"){

            // presenta el mensaje y retorna
            var mensaje = "Usted no tiene permisos para<br>";
            mensaje += "añadir / editar diccionarios<br>";
            mensaje += "del sistema."
            $.messager.alert('Atención',mensaje);
            return;

        }

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que exista una provincia seleccionada
        if ($('#provincialocalidad').combobox('getValue') == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Seleccione una provincia");
            return;

        // si hay seleccionado
        } else {

            // nos aseguramos de asignarlo
            this.CodPcia = $('#provincialocalidad').combobox('getValue');

        }

        // definimos el diálogo y lo mostramos
        $('#win-localidades').window({
            width:500,
            height:220,
            modal:true,
            title: "Localidades Censales",
            minimizable: false,
            closable: true,
            href: 'localidades/formlocalidades.html',
            loadingMessage: 'Cargando',
            onClose:function(){clase.initLocalidades();},
            border: 'thin'
        });

        // centramos el formulario
        $('#win-localidades').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario de
     * localidades que inicializa sus componentes
     */
     initFormLocalidades(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // configuramos los componentes
        $('#idlocalidad').textbox({});
        $('#nombrelocalidad').textbox({});
        $('#codlocalidad').numberbox({});
        $('#poblacionlocalidad').numberbox({});
        $('#usuariolocalidad').textbox({});
        $('#fechalocalidad').textbox({});
        $('#btnGrabarLocalidad').linkbutton({});
        $('#btnCancelarLocalidad').linkbutton({});

        // si está editando
        if (this.Id != 0){

            // cargamos los valores
            $('#idlocalidad').textbox('setValue', this.Id);
            $('#nombrelocalidad').textbox('setValue', this.Localidad);
            $('#codlocalidad').numberbox('setValue', this.CodLoc);
            $('#poblacionlocalidad').numberbox('setValue', this.Poblacion);
            $('#usuariolocalidad').textbox('setValue', this.Usuario);
            $('#fechalocalidad').textbox('setValue', this.Fecha);

        // si está insertando
        } else {

            // configuramos la fecha y el usuario
            $('#usuariolocalidad').textbox('setValue', sessionStorage.getItem("Usuario"));
            $('#fechalocalidad').textbox('setValue', fechaActual());

        }

        // fijamos el foco
        $('#codlocalidad').textbox('textbox').focus();
        
    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón grabar que
     * verifica el contenido del formulario
     */
     verificaLocalidad(){

        // si está editando
        if ($('#idlocalidad').textbox('getValue') != ""){
            this.Id = $('#idlocalidad').textbox('getValue');
        } else {
            this.Id = 0;
        }

        // verifica el nombre de la localidad
        if ($('#nombrelocalidad').textbox('getValue') != ""){
            this.Localidad = $('#nombrelocalidad').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese la localidad");
            return;

        }

        // verifica el código indec
        if ($('#codlocalidad').numberbox('getValue') != ""){
            this.CodLoc = $('#codlocalidad').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese el código Indec");
            return;

        }

        // si no indicó población graba igual pero avisa
        if ($('#poblacionlocalidad').textbox('getValue') != ""){
            this.Poblacion = $('#poblacionlocalidad').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "No hay datos de población");

        }

        // si está insertando
        if (this.Id == 0){
            this.validaLocalidad();
        } else {
            this.grabaLocalidad();
        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado en caso de un alta que verifica que
     * la localidad no esté repetida
     */
     validaLocalidad(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que la marca no esté repetida
        $.ajax({
            url: "localidades/validalocalidad.php?idprovincia="+clase.CodPcia + "&localidad=" + clase.Localidad,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // grabamos el registro
                    clase.grabaLocalidad();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Localidad ya declarada");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que ejecuta la consulta de grabación
     */
     grabaLocalidad(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos el formulario
        var datosLocalidad = new FormData();

        // declaramos la clase
        var clase = this;

        // agregamos los elementos
        datosLocalidad.append("IdProvincia", this.CodPcia);
        datosLocalidad.append("Localidad", this.Localidad);
        datosLocalidad.append("IdLocalidad", this.CodLoc);
        datosLocalidad.append("Poblacion", this.Poblacion);
        datosLocalidad.append("IdUsuario", sessionStorage.getItem("Id"));
        datosLocalidad.append("Id", this.Id);

        // grabamos el registro
        $.ajax({
            url: "localidades/grabalocalidad.php",
            type: "POST",
            data: datosLocalidad,
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si salío todo bien
                if (data.Resultado){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Registro grabado ...");

                    // recargamos la grilla de la seroteca
                    // pasándole la provincia seleccionada
                    $('#grilla-localidades').datagrid('load',{
                        codprov: $('#provincialocalidad').val(),
                    });

                    // cerramos el layer
                    clase.cerrarEmergente();

                // si hubo algún error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Ha ocurrido un error");

                }

            }

        });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {string} - clave indec de la localidad
     * @param {string} - clave indec de la provincia
     * Método llamado al pulsar el botón eliminar que
     * recibe la clave de la localidad y verifica que
     * no tenga registros hijos
     */
     puedeBorrar(codloc, codpcia){

        // reiniciamos la sesion
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que no esté repetido
        $.ajax({
            url: "localidades/puedeborrar.php?idprovincia="+codpcia+"&idlocalidad="+codloc,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si puede borrar
                if (data.Registros == 0){

                    // pedimos confirmación
                    clase.confirmaEliminar(codloc, codpcia);

                // si encontró registros
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Localidad con registros asociados");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {string} codloc - código indec de la localidad
     * @param {string} codpcia - clave indec de la provincia
     * Método que pide confirmación antes de eliminar el
     * registro
     */
    confirmaEliminar(codloc, codpcia){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // declaramos las variables
        var mensaje = 'Está seguro que desea<br>eliminar el registro?';

        // pedimos confirmación
        $.messager.confirm('Eliminar',
                            mensaje,
                            function(r){
                                if (r){
                                    clase.borraLocalidad(codloc, codpcia);
                                }
                            });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {string} codloc - clave indec de la localidad
     * @param {string} codpcia - clave indec de la provincia
     * Método que ejecuta la consulta de eliminación
     */
    borraLocalidad(codloc, codpcia){

        // reiniciamos la sesión
        sesion.reiniciar();

        // eliminamos el registro
        $.ajax({
            url: "localidades/borrar.php?idprovincia="+codpcia+"&idlocalidad="+codloc,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si está correcto
                if (data.Resultado){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Registro eliminado");

                    // recargamos la grilla
                    $('#grilla-localidades').datagrid('load',{
                        codprov: $('#provincialocalidad').val(),
                    });

                // si hubo un error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Ha ocurrido un error");
                    
                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que presenta la ayuda para el abm de localidades
     * censales
     */
     ayudaLocalidades(){

        // reiniciamos
        sesion.reiniciar();

        // definimos el diálogo y lo mostramos
        $('#win-localidades').window({
            width:850,
            height:500,
            modal:true,
            title: "Ayuda Localidades Censales",
            minimizable: false,
            closable: true,
            href: 'localidades/ayuda.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-localidades').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método genérico que cierra el layer emergente de
     * búsquedas
     */
     cerrarEmergente(){

        // cerramos el layer
        $('#win-localidades').window('close');

    }

}
