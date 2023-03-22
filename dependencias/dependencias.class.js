/*

    Nombre: dependencias.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 24/10/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla las operaciones de las
                 dependencias de los laboratorios y las
                 instituciones

*/

/**
 *
 * Atención, utilizamos el estandard definido en ECMAScript 2015 (ES6)
 *
 */

/*jshint esversion: 6 */

/**
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @class Clase que controla las operaciones de las dependencias
 *        de los laboratorios y las instituciones
 */
class Dependencias {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initDependencias();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado desde el constructor que inicializa las
     * variables de clase
     */
    initDependencias(){

        // inicializamos las variables
        this.Id = 0;                 // clave del registro
        this.Dependencia = "";       // nombre de la dependencia
        this.Descripcion = "";       // abreviatura de la dependencia
        this.Usuario = "";           // nombre del usuario
        this.Fecha = "";             // fecha de alta

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que carga en el contenedor la definición de
     * la grilla de dependencias
     */
    verGrillaDependencias(){

        // reiniciamos la sesión
        this.Reiniciar();

        // cargamos el formulario
        $("#form_administracion").load("dependencias/grilladependencias.html");

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al cargar la grilla que inicializa
     * la tabla y carga las dependencias registradas
     */
    initGrillaDependencias(){

       // reiniciamos la sesión
       this.Reiniciar();

       // definimos la clase y asignamos el usuario
       var clase = this;

       // definimos la tabla
       $('#grilla-dependencias').datagrid({
           title: "Pulse sobre una entrada para editarla",
           toolbar: [{
               iconCls: 'icon-edit',
               handler: function(){clase.formDependencias();}
           },'-',{
               iconCls: 'icon-help',
               handler: function(){clase.ayudaDependencia();}
           }],
           loadMsg: 'Cargando ...',
           singleSelect: true,
           onClickCell: function(index,field){
               clase.eventoGrillaDependencias(index, field);
           },
           remoteSort: false,
           pagination: true,
           url:'dependencias/nominadependencias.php',
           columns:[[
               {field:'iddependencia',title:'Id',width:50,align:'center'},
               {field:'dependencia',title:'Dependencia',width:150,sortable:true},
               {field:'descripcion',title:'Abr.',width:100,sortable:true},
               {field:'usuario',title:'Usuario',width:100,align:'center'},
               {field:'fecha',title:'Fecha',width:100,align:'center'},
               {field:'editar',width:50,align:'center'},
               {field:'borrar',width:50,align:'center'}
           ]]
       });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la fila de la grilla
     * @param {string} field - nombre del campo pulsado
     * Método llamado al pulsar sobre la tabla, obtiene
     * los datos de la fila pulsada y según el campo
     * llama el método de edición o eliminación
     */
    eventoGrillaDependencias(index, field){

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
        this.Reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-dependencias').datagrid('getRows')[index];

        // si pulsó en editar
        if (field == "editar"){

            // presentamos el registro
            this.getDatosDependencia(index);


        // si pulsó sobre borrar
        } else if (field == "borrar"){

            // eliminamos
            this.puedeBorrar(row.iddependencia);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la fila
     * Método que recibe la clave de la fila de la grilla
     * y obtiene los valores y los asigna a las variables
     * de clase
     */
    getDatosDependencia(index){

        // reiniciamos la sesión
        this.Reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-dependencias').datagrid('getRows')[index];

        // asignamos en las variables de clase
        this.Id = row.iddependencia;
        this.Dependencia = row.dependencia;
        this.Descripcion = row.descripcion;
        this.Usuario = row.usuario;
        this.Fecha = row.fecha;

        // cargamos el formulario de departamentos
        this.formDependencias();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que carga en el layer emergente la definición
     * del formulario de dependencias
     */
    formDependencias(){

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
        this.Reiniciar();

        // declaramos la clase
        var clase = this;

        // definimos el diálogo y lo mostramos
        $('#win-dependencias').window({
            width:550,
            height:200,
            modal:true,
            title: "Diccionario de Dependencias",
            minimizable: false,
            closable: true,
            href: 'dependencias/formdependencias.html',
            loadingMessage: 'Cargando',
            onClose:function(){clase.initDependencias();},
            border: 'thin'
        });

        // centramos el formulario
        $('#win-dependencias').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario de
     * dependencias que inicializa sus componentes
     */
    initFormDependencias(){

        // reiniciamos la sesión
        this.Reiniciar();

        // configuramos los componentes
        $('#iddependencia').textbox({});
        $('#nombredependencia').textbox({});
        $('#descripciondependencia').textbox({});
        $('#usuariodependencia').textbox({});
        $('#fechadependencia').textbox({});
        $('#btnGrabarDependencia').linkbutton({});
        $('#btnCancelarDependencia').linkbutton({});

        // si está editando
        if (this.Id != 0){

            // cargamos los valores
            $('#iddependencia').textbox('setValue', this.Id);
            $('#nombredependencia').textbox('setValue', this.Dependencia);
            $('#descripciondependencia').textbox('setValue', this.Descripcion);
            $('#usuariodependencia').textbox('setValue', this.Usuario);
            $('#fechadependencia').textbox('setValue', this.Fecha);

        // si está insertando
        } else {

            // configuramos la fecha y el usuario
            $('#usuariodependencia').textbox('setValue', sessionStorage.getItem("Usuario"));
            $('#fechadependencia').textbox('setValue', fechaActual());

        }

        // fijamos el foco
        $('#nombredependencia').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón grabar que
     * verifica el formulario de dependencias
     */
    verificaDependencia(){

        // reiniciamos la sesión
        this.Reiniciar();

        // si está editando
        if ($('#iddependencia').textbox('getValue') != ""){
            this.Id = $('#iddependencia').textbox('getValue');
        } else {
            this.Id = 0;
        }

        // verifica el nombre del departamento
        if ($('#nombredependencia').textbox('getValue') != ""){
            this.Dependencia = $('#nombredependencia').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese el nombre de la dependencia");
            return;

        }

        // verifica la abreviatura
        if ($('#descripciondependencia').textbox('getValue') != ""){
            this.Descripcion = $('#descripciondependencia').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese la sigla de la dependencia");
            return;

        }

        // si está insertando
        if (this.Id == 0){
            this.validaDependencia();
        } else {
            this.grabaDependencia();
        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado en el caso de un alta que verifica
     * que la dependencia no esté declarada
     */
    validaDependencia(){

        // reiniciamos la sesión
        this.Reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que la compañia no esté repetida
        $.ajax({
            url: "dependencias/validadependencia.php?dependencia="+clase.Dependencia,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // grabamos el registro
                    clase.grabaDependencia();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Esa dependencia ya está declarada");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que ejecuta la consulta de grabación
     */
    grabaDependencia(){

        // reiniciamos la sesión
        this.Reiniciar();

        // declaramos la clase y el formulario
        var datosDependencia = new FormData();
        var clase = this;

        // asignamos en el formulario
        datosDependencia.append("Id", this.Id);
        datosDependencia.append("Dependencia", this.Dependencia);
        datosDependencia.append("Descripcion", this.Descripcion);
        datosDependencia.append("IdUsuario", sessionStorage.getItem("Id"));

        // grabamos el registro
        $.ajax({
            url: "dependencias/grabadependencia.php",
            type: "POST",
            data: datosDependencia,
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si salío todo bien
                if (data.Id != 0){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Registro grabado ...");

                    // recargamos la grilla de la seroteca
                    $('#grilla-dependencias').datagrid('reload');

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
     * @param {int} iddependencia - clave del registro
     * Método que verifica que la dependencia no tenga
     * registros hijos antes de eliminar
     */
    puedeBorrar(iddependencia){

        // reiniciamos la sesion
        this.Reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que no esté repetido
        $.ajax({
            url: "dependencias/puedeborrar.php?id="+iddependencia,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si puede borrar
                if (data.Registros == 0){

                    // pedimos confirmación
                    clase.confirmaEliminar(iddependencia);

                // si encontró registros
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "La dependencia tiene registros asociados");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} iddependencia - clave del registro
     * Método que pide confirmación antes de eliminar
     * el registro
     */
    confirmaEliminar(iddependencia){

        // reiniciamos la sesión
        this.Reiniciar();

        // declaramos la clase
        var clase = this;

        // declaramos las variables
        var mensaje = 'Está seguro que desea<br>eliminar el registro?';

        // pedimos confirmación
        $.messager.confirm('Eliminar',
                            mensaje,
                            function(r){
                                if (r){
                                    clase.borraDependencia(iddependencia);
                                }
                            });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} iddependencia - clave del registro
     * Método que ejecuta la consulta de eliminación
     */
    borraDependencia(iddependencia){

        // reiniciamos la sesión
        this.Reiniciar();

        // eliminamos el registro
        $.ajax({
            url: "dependencias/borrar.php?id="+iddependencia,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si está correcto
                if (data.Resultado){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Registro eliminado ...");

                    // cerramos el emergente
                    clase.cerrarEmergente();

                    // recargamos la grilla
                    $('#grilla-dependencias').datagrid('reload');

                // si hubo un error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Ha ocurrido un error");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que presenta la ayuda emergente
     */
    ayudaDependencia(){

        // reiniciamos
        this.Reiniciar();

        // definimos el diálogo y lo mostramos
        $('#win-dependencias').window({
            width:850,
            height:650,
            modal:true,
            title: "Ayuda Dependencias",
            minimizable: false,
            closable: true,
            href: 'dependencias/ayuda.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-dependencias').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que cierra el formulario emergente
     */
     cerrarEmergente(){

        // cerramos el layer
        $('#win-dependencias').window('close');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que reinicia la sesión
     */
    Reiniciar(){

        // reiniciamos la sesión
        sesion.reiniciar();

    }

}
