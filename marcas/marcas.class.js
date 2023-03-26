/*

    Nombre: marcas.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 03/11/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla las operaciones del abm de
                 marcas de reactivos

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
 *        de marcas de reactivos
 */
 class Marcas {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initMarcas();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa las variables de clase
     */
    initMarcas(){

        // inicializamos las variables
        this.Id = 0;                   // clave del registro
        this.Marca = "";               // nombre de la marca
        this.Tecnica = "";             // nombre de la técnica
        this.IdTecnica = 0;            // clave de la técnica
        this.Usuario = "";             // nombre del usuario
        this.Fecha = "";               // fecha de alta del registro

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que carga la grilla de marcas de reactivos
     */
    verGrillaMarcas(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // cargamos el formulario
        $("#form_administracion").load("marcas/grillamarcas.html");

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario con
     * la definición de la grilla que carga la tabla
     */
    initGrillaMarcas(){

       // reiniciamos la sesión
       sesion.reiniciar();

       // definimos la clase y asignamos el usuario
       var clase = this;

        // definimos el estilo de la barra de herramientas
        $('#btnAyudaMarcas').linkbutton({});
        $('#btnNuevaMarca').linkbutton({});
        $('#marcatecnica').combobox({
            data:tecnicas.nominaTecnicas,
            valueField:'idtecnica',
            textField:'tecnica',
            onSelect: (function(rec){
                clase.filtraMarcas(rec.idtecnica);
            })
        });

       // definimos la tabla
       $('#grilla-marcas').datagrid({
           title: "Pulse sobre una entrada para editarla",
           loadMsg: 'Cargando ...',
           singleSelect: true,
           onClickCell: function(index,field){
               clase.eventoGrillaMarcas(index, field);
           },
           remoteSort: false,
           pagination: true,
           url:'marcas/nominamarcas.php',
           columns:[[
               {field:'id',title:'Id',width:50,align:'center'},
               {field:'marca',title:'Marca',width:200,sortable:true},
               {field:'tecnica',title:'Técnica',width:150,sortable:true},
               {field:'idtecnica',hidden:true},
               {field:'usuario',title:'Usuario',width:100,align:'center'},
               {field:'fecha',title:'Fecha',width:100,align:'center'},
               {field:'editar',width:50,align:'center'},
               {field:'borrar',width:50,align:'center'}
           ]]
       });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idtecnica - clave de la técnica seleccionada
     * Método llamado al cambiar el valor del select con
     * la nómina de técnicas que actualiza la grilla
     * según la técnica seleccionada
     */
     filtraMarcas(idtecnica){

        // reiniciamos la sesión
        sesion.reiniciar();

        // llamamos la rutina definida en el datagrid pasándole
        // los argumentos
        $('#grilla-marcas').datagrid('load',{
            idtecnica: idtecnica,
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
    eventoGrillaMarcas(index, field){

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
        var row = $('#grilla-marcas').datagrid('getRows')[index];

        // si pulsó en editar
        if (field == "editar"){

            // presentamos el registro
            this.getDatosMarca(index);

        // si pulsó sobre borrar
        } else if (field == "borrar"){

            // eliminamos
            this.puedeBorrar(row.id);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * Método que recibe como parámetro la clave de la
     * grilla de marcas de reactivos y asigna en las variables de
     * clase los valores de los campos
     */
    getDatosMarca(index){

        // reiniciamos la sesión
        sesion.reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-marcas').datagrid('getRows')[index];

        // asignamos en las variables de clase
        this.Id = row.id;
        this.Marca = row.marca;
        this.IdTecnica = row.idtecnica;
        this.Tecnica = row.tecnica;
        this.Fecha = row.fecha;
        this.Usuario = row.usuario;

        // cargamos el formulario
        this.formMarcas();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el layer emergente con el formulario
     * de marcas de reactivos
     */
    formMarcas(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que exista una técnica seleccionada
        if ($('#marcatecnica').combobox('getValue') == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Debe seleccionar la técnica");
            return;

        // si hay seleccionado
        } else {

            // nos aseguramos de asignarlo
            this.IdTecnica = $('#marcatecnica').combobox('getValue');

        }

        // definimos el diálogo y lo mostramos
        $('#win-marcas').window({
            width:400,
            height:200,
            modal:true,
            title: "Marcas de Reactivos",
            minimizable: false,
            closable: true,
            href: 'marcas/formmarcas.html',
            loadingMessage: 'Cargando',
            onClose:function(){clase.initMarcas();},
            border: 'thin'
        });

        // centramos el formulario
        $('#win-marcas').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario de
     * marcas que inicializa sus componentes
     */
    initFormMarcas(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // configuramos los componentes
        $('#idmarca').textbox({});
        $('#nombremarca').textbox({});
        $('#usuariomarca').textbox({});
        $('#fechamarca').textbox({});
        $('#btnGrabarMarca').linkbutton({});
        $('#btnCancelarMarca').linkbutton({});

        // si está editando
        if (this.Id != 0){

            // cargamos los valores
            $('#idmarca').textbox('setValue', this.Id);
            $('#nombremarca').textbox('setValue', this.Marca);
            $('#usuariomarca').textbox('setValue', this.Usuario);
            $('#fechamarca').textbox('setValue', this.Fecha);

        // si está insertando
        } else {

            // configuramos la fecha y el usuario
            $('#usuariomarca').textbox('setValue', sessionStorage.getItem("Usuario"));
            $('#fechamarca').textbox('setValue', fechaActual());

        }

        // fijamos el foco
        $('#nombremarca').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón grabar que
     * verifica el contenido del formulario
     */
    verificaMarca(){

        // si está editando
        if ($('#idmarca').textbox('getValue') != ""){
            this.Id = $('#idmarca').textbox('getValue');
        } else {
            this.Id = 0;
        }

        // verifica el nombre de la marca
        if ($('#nombremarca').textbox('getValue') != ""){
            this.Marca = $('#nombremarca').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese el nombre de la marca");
            return;

        }

        // si está insertando
        if (this.Id == 0){
            this.validaMarca();
        } else {
            this.grabaMarca();
        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado en caso de un alta que verifica que
     * el la marca no esté repetida
     */
    validaMarca(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que la marca no esté repetida
        $.ajax({
            url: "marcas/validamarca.php?idtecnica="+clase.IdTecnica + "&marca=" + clase.Marca,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // grabamos el registro
                    clase.grabaMarca();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Marca ya existente");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que ejecuta la consulta de grabación
     */
    grabaMarca(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos el formulario
        var datosMarca = new FormData();

        // declaramos la clase
        var clase = this;

        // agregamos los elementos
        datosMarca.append("IdMarca", this.Id);
        datosMarca.append("Marca", this.Marca);
        datosMarca.append("IdTecnica", this.IdTecnica);
        datosMarca.append("IdUsuario", sessionStorage.getItem("Id"));

        // grabamos el registro
        $.ajax({
            url: "marcas/grabamarca.php",
            type: "POST",
            data: datosMarca,
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
                    // pasándole la marca seleccionada
                    $('#grilla-marcas').datagrid('load',{
                        idtecnica: $('#marcatecnica').val(),
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
     * @param {int} idmarca - clave del registro
     * Método llamado al pulsar el botón eliminar que
     * recibe la clave de la marca y verifica que
     * no tenga registros hijos
     */
    puedeBorrar(idmarca){

        // reiniciamos la sesion
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que no esté repetido
        $.ajax({
            url: "marcas/puedeborrar.php?id="+idmarca,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si puede borrar
                if (data.Registros == 0){

                    // pedimos confirmación
                    clase.confirmaEliminar(idmarca);

                // si encontró registros
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Marca con registros asociados");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idmarca - clave del registro
     * Método que pide confirmación antes de eliminar el
     * registro
     */
    confirmaEliminar(idmarca){

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
                                    clase.borraMarca(idmarca);
                                }
                            });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idmarca - clave del registro
     * Método que ejecuta la consulta de eliminación
     */
    borraMarca(idmarca){

        // reiniciamos la sesión
        sesion.reiniciar();

        // eliminamos el registro
        $.ajax({
            url: "marcas/borrar.php?id="+idmarca,
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
                    $('#grilla-marcas').datagrid('load',{
                        idtecnica: $('#marcatecnica').val(),
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
     * Método genérico que cierra el layer emergente de
     * búsquedas
     */
     cerrarEmergente(){

        // cerramos el layer
        $('#win-marcas').window('close');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que presenta la ayuda para el abm de marcas
     * de reactivos
     */
    ayudaMarcas(){

        // reiniciamos
        sesion.reiniciar();

        // definimos el diálogo y lo mostramos
        $('#win-marcas').window({
            width:850,
            height:500,
            modal:true,
            title: "Ayuda Marcas de Reactivos",
            minimizable: false,
            closable: true,
            href: 'marcas/ayuda.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-marcas').window('center');

    }

}
