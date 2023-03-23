/*

    Nombre: valores.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 14/11/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla las operaciones del abm de
                 valores correctos de las técnicas

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
 *        de valores de tecnicas diagnósticas
 */
 class Valores {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initValores();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa las variables de clase
     */
    initValores(){

        // inicializamos las variables
        this.Id = 0;                      // clave del registro
        this.IdTecnica = 0;               // clave de la técnica
        this.Tecnica = "";                // nombre de la técnica
        this.Valor = "";                  // valor admitido
        this.Usuario = "";                // nombre del usuario
        this.Fecha = "";                  // fecha de alta

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que carga la grilla de valores correctos
     */
     verGrillaValores(){

        // reiniciamos la sesión
        this.Reiniciar();

        // cargamos el formulario
        $("#form_administracion").load("valores/grillavalores.html");

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario con
     * la definición de la grilla que carga la tabla
     */
     initGrillaValores(){

        // reiniciamos la sesión
        this.Reiniciar();

        // definimos la clase y asignamos el usuario
        var clase = this;

         // definimos el estilo de la barra de herramientas
         $('#btnAyudaValores').linkbutton({});
         $('#btnNuevoValor').linkbutton({});
         $('#valortecnica').combobox({
             data:tecnicas.nominaTecnicas,
             valueField:'idtecnica',
             textField:'tecnica',
             onSelect: (function(rec){
                 clase.filtraValores(rec.idtecnica);
             })
         });

        // definimos la tabla
        $('#grilla-valores').datagrid({
            title: "Seleccione la técnica",
            loadMsg: 'Cargando ...',
            singleSelect: true,
            onClickCell: function(index,field){
                clase.eventoGrillaValores(index, field);
            },
            remoteSort: false,
            pagination: true,
            url:'valores/nominavalores.php',
            columns:[[
                {field:'id',title:'Id',width:50,align:'center'},
                {field:'valor',title:'Valor',width:200,sortable:true},
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
    filtraValores(idtecnica){

        // reiniciamos la sesión
        this.Reiniciar();

        // llamamos la rutina definida en el datagrid pasándole
        // los argumentos
        $('#grilla-valores').datagrid('load',{
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
    eventoGrillaValores(index, field){

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
        var row = $('#grilla-valores').datagrid('getRows')[index];

        // si pulsó en editar
        if (field == "editar"){

            // presentamos el registro
            this.getDatosValor(index);

        // si pulsó sobre borrar
        } else if (field == "borrar"){

            // eliminamos
            this.puedeBorrar(row.idtecnica, row.valor, row.id);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * Método que recibe como parámetro la clave de la
     * grilla de valores de reactivos y asigna en las variables de
     * clase los valores de los campos
     */
    getDatosValor(index){

        // reiniciamos la sesión
        this.Reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-valores').datagrid('getRows')[index];

        // asignamos en las variables de clase
        this.Id = row.id;
        this.Valor = row.valor;
        this.IdTecnica = row.idtecnica;
        this.Tecnica = row.tecnica;
        this.Fecha = row.fecha;
        this.Usuario = row.usuario;

        // cargamos el formulario
        this.formValores();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el layer emergente con el formulario
     * de valores de reactivos
     */
     formValores(){

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

        // verificamos que exista una técnica seleccionada
        if ($('#valortecnica').combobox('getValue') == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Debe seleccionar una técnica");
            return;

        // si hay seleccionado
        } else {

            // nos aseguramos de asignarlo
            this.IdTecnica = $('#valortecnica').combobox('getValue');

        }

        // definimos el diálogo y lo mostramos
        $('#win-valores').window({
            width:400,
            height:200,
            modal:true,
            title: "Valores de Corte",
            minimizable: false,
            closable: true,
            href: 'valores/formvalores.html',
            loadingMessage: 'Cargando',
            onClose:function(){clase.initValores();},
            border: 'thin'
        });

        // centramos el formulario
        $('#win-valores').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario de
     * valores que inicializa sus componentes
     */
     initFormValores(){

        // reiniciamos la sesión
        this.Reiniciar();

        // configuramos los componentes
        $('#idvalor').textbox({});
        $('#valorcorte').textbox({});
        $('#usuariovalor').textbox({});
        $('#fechavalor').textbox({});
        $('#btnGrabarValor').linkbutton({});
        $('#btnCancelarValor').linkbutton({});

        // si está editando
        if (this.Id != 0){

            // cargamos los valores
            $('#idvalor').textbox('setValue', this.Id);
            $('#valorcorte').textbox('setValue', this.Valor);
            $('#usuariovalor').textbox('setValue', this.Usuario);
            $('#fechavalor').textbox('setValue', this.Fecha);

        // si está insertando
        } else {

            // configuramos la fecha y el usuario
            $('#usuariovalor').textbox('setValue', sessionStorage.getItem("Usuario"));
            $('#fechavalor').textbox('setValue', fechaActual());

        }

        // fijamos el foco
        $('#valorcorte').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón grabar que
     * verifica el contenido del formulario
     */
     verificaValor(){

        // reiniciamos la sesión
        this.Reiniciar();

        // la clave de la técnica ya la cargamos al
        // definir el formulario

        // si está editando
        if ($('#idvalor').textbox('getValue') != ""){
            this.Id = $('#idvalor').textbox('getValue');
        } else {
            this.Id = 0;
        }

        // verifica el valor de corte
        if ($('#valorcorte').textbox('getValue') != ""){
            this.Valor = $('#valorcorte').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese un valor o una máscara");
            return;

        }

        // si está insertando
        if (this.Id == 0){
            this.validaValor();
        } else {
            this.grabaValor();
        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado en caso de un alta que verifica que
     * el la marca no esté repetida
     */
     validaValor(){

        // reiniciamos la sesión
        this.Reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que el valor no esté repetido
        $.ajax({
            url: "valores/validavalor.php?idtecnica="+clase.IdTecnica + "&valor=" + clase.Valor,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // grabamos el registro
                    clase.grabaValor();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Valor ya declarado");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que ejecuta la consulta de grabación
     */
     grabaValor(){

        // reiniciamos la sesión
        this.Reiniciar();

        // declaramos el formulario
        var datosValor = new FormData();

        // declaramos la clase
        var clase = this;

        // agregamos los elementos
        datosValor.append("IdValor", this.Id);
        datosValor.append("Valor", this.Valor);
        datosValor.append("IdTecnica", this.IdTecnica);
        datosValor.append("IdUsuario", sessionStorage.getItem("Id"));

        // grabamos el registro
        $.ajax({
            url: "valores/grabavalor.php",
            type: "POST",
            data: datosValor,
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
                    // pasándole la marca seleccionada
                    $('#grilla-valores').datagrid('load',{
                        idtecnica: $('#valortecnica').val(),
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
     * @param {int} idvalor - clave del registro
     * @param {int} idtecnica - clave de la técnica
     * @param {string} valor - valor de corte
     * Método llamado al pulsar el botón eliminar que
     * recibe la clave del valor y verifica que
     * no tenga registros hijos
     */
     puedeBorrar(idtecnica, valor, idvalor){

        // reiniciamos la sesion
        this.Reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que no esté repetido
        $.ajax({
            url: "valores/puedeborrar.php?idtecnica="+idtecnica+"&valor="+valor,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si puede borrar
                if (data.Registros == 0){

                    // pedimos confirmación
                    clase.confirmaEliminar(idvalor);

                // si encontró registros
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "El valor tiene registros asociados");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idvalor - clave del registro
     * Método que pide confirmación antes de eliminar el
     * registro
     */
    confirmaEliminar(idvalor){

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
                                    clase.borraValor(idvalor);
                                }
                            });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idvalor - clave del registro
     * Método que ejecuta la consulta de eliminación
     */
    borraValor(idvalor){

        // reiniciamos la sesión
        this.Reiniciar();

        // eliminamos el registro
        $.ajax({
            url: "valores/borrar.php?id="+idvalor,
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
                    $('#grilla-valores').datagrid('load',{
                        idtecnica: $('#valortecnica').val(),
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
        $('#win-valores').window('close');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que presenta la ayuda para el abm de valores
     * de corte
     */
    ayudaValores(){

        // reiniciamos
        this.Reiniciar();

        // definimos el diálogo y lo mostramos
        $('#win-valores').window({
            width:850,
            height:500,
            modal:true,
            title: "Ayuda Valores de Corte",
            minimizable: false,
            closable: true,
            href: 'valores/ayuda.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-valores').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que simplemente reinicia la sesión, lo llamamos
     * desde la clase para evitar los warnings por el
     * espacion de trabajo
     */
    Reiniciar(){

        // reiniciamos la sesión
        sesion.reiniciar();

    }

}
