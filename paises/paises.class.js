/*

    Nombre: paises.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 16/11/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla las operaciones del abm de
                 países

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
 *        de países
 */
class Paises {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initPaises();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa las variables de clase
     */
    initPaises(){

        // declaramos las variables
        this.Id = 0;                 // clave del registro
        this.Pais = "";              // nombre del país
        this.Usuario = "";           // nombre del usuario
        this.Fecha = "";             // fecha de alta

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado desde el menú que carga la definición
     * de la grilla
     */
     verGrillaPaises(){

        // reiniciamos la sesión
        this.Reiniciar();

        // cargamos el formulario
        $("#form_administracion").load("paises/grillapaises.html");

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar la página que
     * configura el formulario y carga la nómina de
     * países
     */
     initGrillaPaises(){

        // reiniciamos la sesión
        this.Reiniciar();

        // definimos la clase
        var clase = this;

        // definimos la tabla, como tenemos un formato a dos
        // columnas el método php debe retornar los valores
        // en una estructura coincidente
        $('#grilla-paises').datagrid({
            title: "Pulse sobre un país para editarlo",
            toolbar: [{
                iconCls: 'icon-edit',
                handler: function(){clase.formPaises();}
            },'-',{
                iconCls: 'icon-help',
                handler: function(){clase.ayudaPaises();}
            }],
            loadMsg: 'Cargando ...',
            singleSelect: true,
            onClickCell: function(index,field){
                clase.eventoGrillaPais(index, field);
            },
            remoteSort: false,
            url:'paises/listapaises.php',
            columns:[[
                {field:'id',title:'Id',width:50,align:'center'},
                {field:'pais',title:'País',width:200,sortable:true},
                {field:'usuario',title:'Usuario',width:100,align:'center'},
                {field:'fecha',title:'Fecha',width:100,align:'center'},
                {field:'editar', width:50, align:'center'},
                {field:'borrar', width:50, align:'center'},
                {field:'id1',title:'Id',width:50,align:'center'},
                {field:'pais1',title:'País',width:200,sortable:true},
                {field:'usuario1',title:'Usuario',width:100,align:'center'},
                {field:'fecha1',title:'Fecha',width:100,align:'center'},
                {field:'editar1', width:50, align:'center'},
                {field:'borrar1', width:50, align:'center'}
            ]]
        });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * @param {string} field - nombre del campo
     * Método llamado al pulsar sobre la grilla que recibe
     * la clave del registro y el nombre del campo pulsado
     * según este último valor, llama el método de
     * edición o de eliminación
     */
     eventoGrillaPais(index, field){

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

        // declaramos las variables
        var id;

        // obtenemos la fila seleccionada
        var row = $('#grilla-paises').datagrid('getRows')[index];

        // si pulsó en editar
        if (field == "editar" || field == "editar1"){

            // presentamos el registro
            this.getDatosPais(index, field);

        // si pulsó sobre borrar
        } else if (field == "borrar" || field == "borrar1"){

            // si es la primer columna
            if (field == "borrar"){
                id = row.id;
            } else {
                id = row.id1;
            }

            // eliminamos
            this.confirmaEliminar(id);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * @param {string} field - campo pulsado
     * Método que recibe como parámetro la clave de la
     * grilla y obtiene los valores del registro
     */
     getDatosPais(index, field){

        // reiniciamos la sesión
        this.Reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-paises').datagrid('getRows')[index];

        // si se pulsó sobre la primer columna
        if (field == "editar"){

            // asignamos en las variables de clase
            this.Id = row.id;
            this.Pais = row.pais;
            this.Fecha = row.fecha;
            this.Usuario = row.usuario;

        // entonces
        } else {

            // asignamos la segunda columna
            this.Id = row.id1;
            this.Pais = row.pais1;
            this.Fecha = row.fecha1;
            this.Usuario = row.usuario1;

        }

        // cargamos el formulario
        this.formPaises();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el formulario emergente de los
     * paises
     */
     formPaises(){

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
        $('#win-paises').window({
            width:450,
            height:200,
            modal:true,
            title: "Nómina de Países",
            minimizable: false,
            closable: true,
            href: 'paises/formpaises.html',
            loadingMessage: 'Cargando',
            onClose:function(){clase.initPaises();},
            border: 'thin'
        });

        // centramos el formulario
        $('#win-paises').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al cargar el formulario de países
     * que inicializa sus valores
     */
     initFormPaises(){

        // reiniciamos la sesión
        this.Reiniciar();

        // configuramos el formulario
        $('#btnGrabarPais').linkbutton({});
        $('#btnCancelarPais').linkbutton({});
        $('#idpais').textbox({});
        $('#nombrepais').textbox({});
        $('#usuariopais').textbox({});
        $('#altapais').textbox({});

        // si está editando
        if (this.Id != 0){

            // asignamos los valores del formulario
            $('#idpais').textbox('setValue', this.Id);
            $('#nombrepais').textbox('setValue', this.Pais);
            $('#usuariopais').textbox('setValue', this.Usuario);
            $('#altapais').textbox('setValue', this.Fecha);

        // si está insertando un registro
        } else {

            // fijamos el usuario y la fecha
            $('#usuariopais').textbox('setValue', sessionStorage.getItem("Usuario"));
            $('#altapais').textbox('setValue', fechaActual());

        }

        // fijamos el foco
        $('#nombrepais').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que verifica el formulario antes de
     * enviar los datos al servidor
     */
     verificaPais(){

        // si está editando
        if ($('#idpais').textbox('getValue') != ""){
            this.Id = $('#idpais').textbox('getValue');
        } else {
            this.Id = 0;
        }

        // verifica el nombre del órgano
        if ($('#nombrepais').textbox('getValue') != ""){
            this.Pais = $('#nombrepais').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese el nombre del país");
            return;

        }

        // si está insertando
        if (this.Id == 0){
            this.validaPais();
        } else {
            this.grabaPais();
        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado en el caso de un alta que verifica
     * que el país no esté repetido
     */
     validaPais(){

        // reiniciamos la sesión
        this.Reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que la compañia no esté repetida
        $.ajax({
            url: "paises/validapais.php?pais="+clase.Pais,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // grabamos el registro
                    clase.grabaPais();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "País ya declarado");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que ejecuta la consulta de grabación
     */
     grabaPais(){

        // reiniciamos la sesión
        this.Reiniciar();

        // declaramos el formulario
        var datosPais = new FormData();

        // declaramos la clase
        var clase = this;

        // agregamos los elementos
        datosPais.append("IdPais", this.Id);
        datosPais.append("Pais", this.Pais);
        datosPais.append("IdUsuario", sessionStorage.getItem("Id"));

        // grabamos el registro
        $.ajax({
            url: "paises/grabapais.php",
            type: "POST",
            data: datosPais,
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si salío todo bien
                if (data.Id != 0){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Registro grabado");

                    // recargamos la grilla de la seroteca
                    $('#grilla-paises').datagrid('reload');

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
     * @param {int} index - clave de la grilla
     * @param {string} field - nombre del campo
     * Método que verifica que el registro no tenga
     * hijos
     */
     puedeBorrar(index, field){

        // reiniciamos la sesion
        this.Reiniciar();

        // declaramos la clase
        var clase = this;
        var id;

        // obtenemos la fila seleccionada
        var row = $('#grilla-paises').datagrid('getRows')[index];
        if (field == 'borrar'){
            id = row.id;
        } else {
            id = row.id1;
        }

        // obtenemos la clave del registro
        // verificamos que no esté repetido
        $.ajax({
            url: "paises/puedeborrar.php?id="+id,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si puede borrar
                if (data.Registros == 0){

                    // pedimos confirmación
                    clase.confirmaEliminar(id);

                // si encontró registros
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "País con registros asociados");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idpais - clave del registro
     * Método que pide confirmación antes de eliminar el
     * registro
     */
     confirmaEliminar(idpais){

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
                                    clase.borraPais(idpais);
                                }
                            });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idpais - clave del registro
     * Método que ejecuta la consulta de eliminación
     */
     borraOrgano(idpais){

        // reiniciamos la sesión
        this.Reiniciar();

        // eliminamos el registro
        $.ajax({
            url: "paises/borrar.php?id="+idpais,
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
                    $('#grilla-paises').datagrid('reload');

                // si hubo un error
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Ha ocurrido un eror");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que presenta la ayuda para el abm de países
     */
    ayudaPaises(){

        // reiniciamos
        this.Reiniciar();

        // definimos el diálogo y lo mostramos
        $('#win-paises').window({
            width:850,
            height:650,
            modal:true,
            title: "Ayuda Países",
            minimizable: false,
            closable: true,
            href: 'paises/ayuda.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-paises').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método genérico que cierra el layer emergente de
     * búsquedas
     */
    cerrarEmergente(){

        // cerramos el layer
        $('#win-paises').window('close');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que simplemente reinicia la sesión, lo llamamos
     * desde la clase para evitar los warnings por el
     * espacio de trabajo
     */
     Reiniciar(){

        // reiniciamos la sesión
        sesion.reiniciar();

    }

}
