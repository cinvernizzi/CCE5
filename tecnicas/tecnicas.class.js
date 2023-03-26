/*

    Nombre: tecnicas.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 02/11/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla las operaciones del abm de
                 técnicas diagnósticas

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
 *        de técnicas diagnósticas
 */
class Tecnicas {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initTecnicas();

        // cargamos la nómina de técnicas
        this.cargaTecnicas();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa las variables de clase
     */
    initTecnicas(){

        // inicializamos las variables
        this.Id = 0;                   // clave del registro
        this.Tecnica = "";             // nombre de la técnica
        this.Nombre = "";              // nombre completo de la técnica
        this.Inmuno = "";              // si se aplica en pacientes inmunodeprimidos
        this.Defecto = "";             // si se aplica por defecto
        this.Filas = 0;                // cantidad de filas de la plata
        this.Columnas = 0;             // cantidad de columnas de la placa
        this.Fecha = "";               // fecha de alta del registro
        this.Usuario = "";             // usuario que ingresó el registro
        this.ClaveGrilla = 0;          // clave de la grilla

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que precarga la nómina de técnicas en la 
     * matriz pública
     */
    cargaTecnicas(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // inicializamos el vector
        this.nominaTecnicas = "";

        // declaramos la clase
        var clase = this;

        // cargamos la matriz
        $.ajax({
            url: "tecnicas/nominatecnicas.php",
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.lenght != 0){

                    // cargamos la matriz
                    clase.nominaTecnicas = data.slice();

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado desde el menú que carga la definición
     * de la grilla
     */
    verGrillaTecnicas(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // cargamos el formulario
        $("#form_administracion").load("tecnicas/grillatecnicas.html");

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar la definición de la
     * grilla que inicializa los componentes y carga el
     * diccionario de técnicas diagnósticas
     */
    initGrillaTecnicas(){

       // reiniciamos la sesión
       sesion.reiniciar();

       // definimos la clase y asignamos el usuario
       var clase = this;

       // definimos la tabla
       $('#grilla-tecnicas').datagrid({
           title: "Pulse sobre una entrada para editarla",
           toolbar: [{
               iconCls: 'icon-edit',
               handler: function(){clase.formTecnicas();}
           },'-',{
               iconCls: 'icon-help',
               handler: function(){clase.ayudaTecnicas();}
           }],
           loadMsg: 'Cargando ...',
           singleSelect: true,
           onClickCell: function(index,field){
               clase.eventoGrillaTecnicas(index, field);
           },
           remoteSort: false,
           pagination: true,
           data: clase.nominaTecnicas,
           columns:[[
               {field:'idtecnica',title:'Id',width:50,align:'center'},
               {field:'tecnica',title:'Técnica',width:150,sortable:true},
               {field:'nombre',title:'Nombre',width:200,sortable:true},
               {field:'inmuno',title:'Inmuno',width:80,sortable:true,align:'center'},
               {field:'defecto',title:'Defecto',width:80,sortable:true,align:'center'},
               {field:'filas',title:'Filas',width:80,align:'center'},
               {field:'columnas',title:'Cols.',width:80,align:'center'},
               {field:'usuario',title:'Usuario',width:100,align:'center'},
               {field:'fecha',title:'Fecha',width:100,align:'center'},
               {field:'editar',width:50,align:'center'},
               {field:'borrar',width:50,align:'center'}
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
    eventoGrillaTecnicas(index, field){

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
        var row = $('#grilla-tecnicas').datagrid('getRows')[index];

        // si pulsó en editar
        if (field == "editar"){

            // asignamos la clave de la grilla
            this.ClaveGrilla = index;

            // presentamos el registro
            this.getDatosTecnica(index);

        // si pulsó sobre borrar
        } else if (field == "borrar"){

            // eliminamos
            this.confirmaEliminar(row, index);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * Método que recibe como parámetro la clave de la
     * grilla y obtiene los valores del registro
     */
    getDatosTecnica(index){

        // reiniciamos la sesión
        sesion.reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-tecnicas').datagrid('getRows')[index];

        // asignamos en las variables de clase
        this.Id = row.idtecnica;
        this.Tecnica = row.tecnica;
        this.Nombre = row.nombre;
        this.Inmuno = row.inmuno;
        this.Defecto = row.defecto;
        this.Filas = row.filas;
        this.Columnas = row.columnas;
        this.Fecha = row.fecha;
        this.Usuario = row.usuario;

        // cargamos el formulario
        this.formTecnicas();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el formulario emergente de las
     * técnicas diagnósticas
     */
    formTecnicas(){

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

        // definimos el diálogo y lo mostramos
        $('#win-tecnicas').window({
            width:700,
            height:200,
            modal:true,
            title: "Técnicas diagnósticas",
            minimizable: false,
            closable: true,
            href: 'tecnicas/formtecnicas.html',
            loadingMessage: 'Cargando',
            onClose:function(){clase.initTecnicas();},
            border: 'thin'
        });

        // centramos el formulario
        $('#win-tecnicas').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa los componentes del
     * formulario de técnicas diagnósticas
     */
    initFormTecnicas(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // configuramos los componentes
        $('#idtecnica').textbox({});
        $('#tecnicatecnica').textbox({});
        $('#nombretecnica').textbox({});
        $('#inmunotecnica').combobox({});
        $('#defectotecnica').combobox({});
        $('#filastecnica').numberspinner({
            min: 1
         });
         $('#columnastecnica').numberspinner({
            min: 1
         });
        $('#usuariotecnica').textbox({});
        $('#fechatecnica').textbox({});
        $('#btnGrabarTecnica').linkbutton({});
        $('#btnCancelarTecnica').linkbutton({});

        // si está editando
        if (this.Id != 0){

            // cargamos los valores
            $('#idtecnica').textbox('setValue', this.Id);
            $('#tecnicatecnica').textbox('setValue', this.Tecnica);
            $('#nombretecnica').textbox('setValue', this.Nombre);
            $('#inmunotecnica').combobox('setValue', this.Inmuno);
            $('#defectotecnica').combobox('setValue', this.Defecto);
            $('#filastecnica').numberspinner('setValue', this.Filas);
            $('#columnastecnica').numberspinner('setValue', this.Columnas);
            $('#usuariotecnica').textbox('setValue', this.Usuario);
            $('#fechatecnica').textbox('setValue', this.Fecha);

        // si está insertando
        } else {

            // configuramos la fecha y el usuario
            $('#usuariotecnica').textbox('setValue', sessionStorage.getItem("Usuario"));
            $('#fechatecnica').textbox('setValue', fechaActual());

        }

        // fijamos el foco
        $('#tecnicatecnica').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que verifica el formulario antes de
     * enviar los datos al servidor
     */
    verificaTecnica(){

        // si está editando
        if ($('#idtecnica').textbox('getValue') != ""){
            this.Id = $('#idtecnica').textbox('getValue');
        } else {
            this.Id = 0;
        }

        // verifica el nombre de la técnica
        if ($('#tecnicatecnica').textbox('getValue') != ""){
            this.Tecnica = $('#tecnicatecnica').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese la sigla de la técnica");
            return;

        }

        // verifica el nombre completo de la técnica
        if ($('#nombretecnica').textbox('getValue') != ""){
            this.Nombre = $('#nombretecnica').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Indique el nombre completo");
            return;

        }

        // verifica el si se utiliza en inmunodeprimidos
        if ($('#inmunotecnica').combobox('getValue') != ""){
            this.Inmuno = $('#inmunotecnica').combobox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Indique si se utiliza en inmunosuprimidos");
            return;

        }

        // verifica el si se utiliza en todos los casos
        if ($('#defectotecnica').combobox('getValue') != ""){
            this.Defecto = $('#defectotecnica').combobox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Indique si se utiliza en todos los casos");
            return;

        }

        // el número de filas y columnas los permite en blanco
        this.Filas = $('#filastecnica').numberspinner('getValue');
        this.Columnas = $('#columnastecnica').numberspinner('getValue');

        // si está insertando
        if (this.Id == 0){
            this.validaTecnica();
        } else {
            this.grabaTecnica();
        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado en el caso de un alta que verifica
     * que la técnica no esté repetida
     */
    validaTecnica(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que la compañia no esté repetida
        $.ajax({
            url: "tecnicas/validatecnica.php?tecnica="+clase.Tecnica,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // grabamos el registro
                    clase.grabaTecnica();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Técnica ya declarada");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que ejecuta la consulta de grabación
     */
    grabaTecnica(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos el formulario
        var datosTecnica = new FormData();

        // declaramos la clase
        var clase = this;

        // agregamos los elementos
        datosTecnica.append("IdTecnica", this.Id);
        datosTecnica.append("Tecnica", this.Tecnica);
        datosTecnica.append("Nombre", this.Nombre);
        datosTecnica.append("Inmuno", this.Inmuno);
        datosTecnica.append("Defecto", this.Defecto);
        datosTecnica.append("Filas", this.Filas);
        datosTecnica.append("Columnas", this.Columnas);
        datosTecnica.append("IdUsuario", sessionStorage.getItem("Id"));

        // grabamos el registro
        $.ajax({
            url: "tecnicas/grabatecnica.php",
            type: "POST",
            data: datosTecnica,
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si salío todo bien
                if (data.Id != 0){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Registro grabado ...");

                    // si está insertando
                    if (clase.Id == 0){
         
                        // agregamos a la grilla
                        $('#grilla-tecnicas').datagrid('appendRow',{
                            idtecnica: data.Id,
                            tecnica: clase.Tecnica,
                            nombre: clase.Nombre,
                            inmuno: clase.Inmuno,
                            defecto: clase.Defecto,
                            filas: clase.Filas,
                            columnas: clase.Columnas,
                            usuario: sessionStorage.getItem("Usuario"),
                            fecha: fechaActual(),
                            editar: "<img src='imagenes/meditar.png'>",
                            borrar: "<img src='imagenes/borrar.png'>"
                        });

                    // si está editando
                    } else {

                        // actualizamos la grilla
                        $('#grilla-tecnicas').datagrid('updateRow', {
                            index: clase.ClaveGrilla,
                            row: {idtecnica:clase.Id,
                                  tecnica:clase.Tecnica,
                                  nombre:clase.Nombre,
                                  inmuno:clase.Inmuno,
                                  defecto: clase.Defecto,
                                  filas: clase.Filas,
                                  columnas: clase.Columnas,
                                  usuario: sessionStorage.getItem("Usuario"),
                                  fecha: fechaActual(),
                                  editar: "<img src='imagenes/meditar.png'>",
                                  borrar: "<img src='imagenes/borrar.png'>"      
                                }
                            });                        

                    }

                    // recargamos la matriz
                    clase.cargaTecnicas();

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
     * @param {array} fila - fila de la grilla
     * @param {int} index - clave de la grilla
     * Método que verifica que el registro no tenga
     * hijos
     */
    puedeBorrar(fila, index){

        // reiniciamos la sesion
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que no esté repetido
        $.ajax({
            url: "tecnicas/puedeborrar.php?id="+fila.idtecnica,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si puede borrar
                if (data.Registros == 0){

                    // pedimos confirmación
                    clase.confirmaEliminar(fila, index);

                // si encontró registros
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Técnica con registros asociados");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {array} fila - registro de la grilla
     * @param {int} index - clave de la grilla
     * Método que pide confirmación antes de eliminar el
     * registro
     */
    confirmaEliminar(fila, index){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // declaramos las variables
        var mensaje = 'Está seguro que desea eliminar el registro de ';
        mensaje += fila.tecnica + "?";

        // pedimos confirmación
        $.messager.confirm('Eliminar',
                            mensaje,
                            function(r){
                                if (r){
                                    clase.borraTecnica(fila, index);
                                }
                            });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {array} fila - registro de datos
     * @param {int} index - clave de la grilla
     * Método que ejecuta la consulta de eliminación
     */
    borraTecnica(fila, index){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;
        
        // eliminamos el registro
        $.ajax({
            url: "tecnicas/borrar.php?id="+fila.idtecnica,
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

                    // recargamos la matriz
                    clase.cargaTecnicas();

                    // eliminamos la fila
                    $('#grilla-tecnicas').datagrid('deleteRow', index);

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
        $('#win-tecnicas').window('close');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que presenta la ayuda para el abm de técnicas
     * diagnósticas
     */
    ayudaTecnicas(){

        // reiniciamos
        sesion.reiniciar();

        // definimos el diálogo y lo mostramos
        $('#win-tecnicas').window({
            width:750,
            height:650,
            modal:true,
            title: "Ayuda Técnicas Diagnósticas",
            minimizable: false,
            closable: true,
            href: 'tecnicas/ayuda.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-tecnicas').window('center');

    }

}
