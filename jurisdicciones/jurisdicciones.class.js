/*

    Nombre: jurisdicciones.class.js
    Autor: Lic. Claudio Invernizzi
    Fecha: 17/11/2021
    E-Mail: cinvernizzi@gmail.com
    Proyecto: Diagnóstico
    Producido en: INP
    Licencia: GPL
    Comentarios: Clase que controla las operaciones del abm de
                 jurisdicciones

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
 *        jurisdicciones
 */
class Jurisdicciones {

    // constructor de la clase
    constructor(){

        // inicializamos las variables
        this.initJurisdicciones();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que inicializa las variables de clase
     */
    initJurisdicciones(){

        // inicializamos las varibles
        this.Id = 0;                  // clave del registro
        this.Pais = "";               // nombre del país
        this.IdPais = 0;              // clave del país
        this.Provincia = "";          // nombre de la provincia
        this.CodProv = "";            // clave indec de la provincia
        this.Poblacion = 0;           // población de la provincia
        this.Usuario = "";            // nombre del usuario
        this.Fecha = "";              // fecha de alta del registro

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado desde el menú que carga la definición
     * de la grilla
     */
     verGrillaJurisdicciones(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // cargamos el formulario
        $("#form_administracion").load("jurisdicciones/grillajurisdicciones.html");

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario con
     * la definición de la grilla que carga la tabla
     */
     initGrillaJurisdicciones(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // definimos la clase y asignamos el usuario
        var clase = this;

         // definimos el estilo de la barra de herramientas
         $('#btnAyudaJurisdicciones').linkbutton({});
         $('#btnNuevaJurisdiccion').linkbutton({});
         $('#paisjurisdiccion').combobox({
             url:'paises/nominapaises.php',
             valueField:'idpais',
             textField:'pais',
             onSelect: (function(rec){
                clase.filtraJurisdicciones(rec.idpais);
             })
         });

        // definimos la tabla
        $('#grilla-jurisdicciones').datagrid({
            title: "Jurisdicciones Nacionales",
            loadMsg: 'Cargando ...',
            singleSelect: true,
            onClickCell: function(index,field){
                clase.eventoGrillaJurisdicciones(index, field);
            },
            url:'jurisdicciones/jurisdiccionespaginadas.php',
            remoteSort: false,
            pagination: true,
            columns:[[
                {field:'id',title:'Id',width:50,align:'center'},
                {field:'idprovincia',title:'CodProv',width:100,sortable:true},
                {field:'provincia',title:'Provincia',width:200,sortable:true},
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
     * @param {int} idpais - clave del país seleccionado
     * Método llamado al cambiar el valor del select con
     * la nómina de paises que actualiza la grilla
     * según el valor seleccionado
     */
     filtraJurisdicciones(idpais){

        // reiniciamos la sesión
        sesion.reiniciar();

        // llamamos la rutina definida en el datagrid pasándole
        // los argumentos
        $('#grilla-jurisdicciones').datagrid('load',{
            idpais: idpais,
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
     eventoGrillaJurisdicciones(index, field){

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
        var row = $('#grilla-jurisdicciones').datagrid('getRows')[index];

        // si pulsó en editar
        if (field == "editar"){

            // presentamos el registro
            this.getDatosJurisdiccion(index);

        // si pulsó sobre borrar
        } else if (field == "borrar"){

            // obtenemos la clave del país
            var idpais = $('#paisjurisdiccion').combobox('getValue');

            // verificamos si puede borrar
            this.puedeBorrar(idpais, row.idprovincia);

        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} index - clave de la grilla
     * Método que recibe como parámetro la clave de la
     * grilla de localidades y asigna en las variables de
     * clase los valores de los campos
     */
     getDatosJurisdiccion(index){

        // reiniciamos la sesión
        sesion.reiniciar();

        // obtenemos la fila seleccionada
        var row = $('#grilla-jurisdicciones').datagrid('getRows')[index];

        // asignamos en las variables de clase
        this.Id = row.id;
        this.CodProv = row.idprovincia;
        this.Provincia = row.provincia;
        this.Poblacion = row.poblacion;
        this.Usuario = row.usuario;
        this.Fecha = row.fecha;

        // cargamos el formulario
        this.formJurisdicciones();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que abre el layer emergente con el formulario
     * de jurisdicciones
     */
     formJurisdicciones(){

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

        // verificamos que exista un país seleccionado
        if ($('#paisjurisdiccion').combobox('getValue') == ""){

            // presenta el mensaje
            Mensaje("Error", "Atención", "Debe seleccionar un país");
            return;

        // si hay seleccionado
        } else {

            // nos aseguramos de asignarlo
            this.IdPais = $('#paisjurisdiccion').combobox('getValue');

        }

        // definimos el diálogo y lo mostramos
        $('#win-jurisdicciones').window({
            width:500,
            height:220,
            modal:true,
            title: "Jurisdicciones Nacionales",
            minimizable: false,
            closable: true,
            href: 'jurisdicciones/formjurisdicciones.html',
            loadingMessage: 'Cargando',
            onClose:function(){clase.initJurisdicciones();},
            border: 'thin'
        });

        // centramos el formulario
        $('#win-jurisdicciones').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de cargar el formulario de
     * jurisdicciones que inicializa sus componentes
     */
     initFormJurisdicciones(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // configuramos los componentes
        $('#idjurisdiccion').textbox({});
        $('#nombrejurisdiccion').textbox({});
        $('#codjurisdiccion').numberbox({});
        $('#poblacionjurisdiccion').numberbox({});
        $('#usuariojurisdiccion').textbox({});
        $('#fechajurisdiccion').textbox({});
        $('#btnGrabarJurisdiccion').linkbutton({});
        $('#btnCancelarJurisdiccion').linkbutton({});

        // si está editando
        if (this.Id != 0){

            // cargamos los valores
            $('#idjurisdiccion').textbox('setValue', this.Id);
            $('#nombrejurisdiccion').textbox('setValue', this.Provincia);
            $('#codjurisdiccion').numberbox('setValue', this.CodProv);
            $('#poblacionjurisdiccion').numberbox('setValue', this.Poblacion);
            $('#usuariojurisdiccion').textbox('setValue', this.Usuario);
            $('#fechajurisdiccion').textbox('setValue', this.Fecha);

        // si está insertando
        } else {

            // configuramos la fecha y el usuario
            $('#usuariojurisdiccion').textbox('setValue', sessionStorage.getItem("Usuario"));
            $('#fechajurisdiccion').textbox('setValue', fechaActual());

        }

        // fijamos el foco
        $('#codjurisdiccion').textbox('textbox').focus();

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado al pulsar el botón grabar que
     * verifica el contenido del formulario
     */
     verificaJurisdiccion(){

        // si está editando
        if ($('#idjurisdiccion').textbox('getValue') != ""){
            this.Id = $('#idjurisdiccion').textbox('getValue');
        } else {
            this.Id = 0;
        }

        // verifica el nombre de la localidad
        if ($('#nombrejurisdiccion').textbox('getValue') != ""){
            this.Provincia = $('#nombrejurisdiccion').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese el nombre de la provincia");
            return;

        }

        // verifica el código indec
        if ($('#codjurisdiccion').numberbox('getValue') != ""){
            this.CodProv = $('#codjurisdiccion').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "Ingrese el código Indec");
            return;

        }

        // si no indicó población graba igual pero avisa
        if ($('#poblacionjurisdiccion').textbox('getValue') != ""){
            this.Poblacion = $('#poblacionjurisdiccion').textbox('getValue');
        } else {

            // presenta el mensaje
            Mensaje("Error", "Atención", "No hay datos de población");

        }

        // si está insertando
        if (this.Id == 0){
            this.validaJurisdiccion();
        } else {
            this.grabaJurisdiccion();
        }

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado en caso de un alta que verifica que
     * la jurisdicción no esté repetida
     */
     validaJurisdiccion(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que la marca no esté repetida
        $.ajax({
            url: "jurisdicciones/validajurisdiccion.php?provincia="+clase.Provincia + "&idpais=" + clase.IdPais,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // verificamos la clave indec
                    clase.verificaIndec();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Esa jurisdicción ya existe");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método llamado luego de verificar la jurisdicción que 
     * verifica que no se encuentre repetida la clave indec
     */
    verificaIndec(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que no esté repetida la clave
        $.ajax({
            url: "jurisdicciones/verificaindec.php?clave="+this.CodProv,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si dió correcto
                if (data.Registros == 0){

                    // grabamos el registro
                    clase.grabaJurisdiccion();

                // si está repetida
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "La Clave Indec está en uso");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método que ejecuta la consulta de grabación
     */
     grabaJurisdiccion(){

        // reiniciamos la sesión
        sesion.reiniciar();

        // declaramos el formulario
        var datosJurisdiccion = new FormData();

        // declaramos la clase
        var clase = this;

        // agregamos los elementos
        datosJurisdiccion.append("CodProv", this.CodProv);
        datosJurisdiccion.append("Provincia", this.Provincia);
        datosJurisdiccion.append("IdPais", this.IdPais);
        datosJurisdiccion.append("Poblacion", this.Poblacion);
        datosJurisdiccion.append("IdUsuario", sessionStorage.getItem("Id"));
        datosJurisdiccion.append("Id", this.Id);

        // grabamos el registro
        $.ajax({
            url: "jurisdicciones/grabajurisdiccion.php",
            type: "POST",
            data: datosJurisdiccion,
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si salío todo bien
                if (data.Resultado){

                    // presenta el mensaje
                    Mensaje("Info", "Atención", "Registro grabado");

                    // recargamos la grilla de la seroteca
                    // pasándole la provincia seleccionada
                    $('#grilla-jurisdicciones').datagrid('load',{
                        idpais: $('#paisjurisdiccion').val(),
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
     * @param {int} - clave del pais
     * @param {string} - clave indec de la provincia
     * Método llamado al pulsar el botón eliminar que
     * recibe la clave de la provincia y verifica que
     * no tenga registros hijos
     */
     puedeBorrar(idpais, codpcia){

        // reiniciamos la sesion
        sesion.reiniciar();

        // declaramos la clase
        var clase = this;

        // verificamos que no esté repetido
        $.ajax({
            url: "jurisdicciones/puedeborrar.php?idpais="+idpais+"&idprovincia="+codpcia,
            type: "GET",
            cahe: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {

                // si puede borrar
                if (data.Registros == 0){

                    // pedimos confirmación
                    clase.confirmaEliminar(idpais, codpcia);

                // si encontró registros
                } else {

                    // presenta el mensaje
                    Mensaje("Error", "Atención", "Provincia con registros asociados");

                }

        }});

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idpais - clave del país
     * @param {string} codpcia - clave indec de la provincia
     * Método que pide confirmación antes de eliminar el
     * registro
     */
    confirmaEliminar(idpais, codpcia){

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
                                    clase.borraJurisdiccion(idpais, codpcia);
                                }
                            });

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * @param {int} idpais - clave del país
     * @param {string} codpcia - clave indec de la provincia
     * Método que ejecuta la consulta de eliminación
     */
    borraLocalidad(idpais, codpcia){

        // reiniciamos la sesión
        sesion.reiniciar();

        // eliminamos el registro
        $.ajax({
            url: "jurisdicciones/borrar.php?idpais="+idpais+"&idprovincia="+codpcia,
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
                    $('#grilla-jurisdicciones').datagrid('load',{
                        idpais: $('#paisjurisdiccion').val(),
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
     * Método que presenta la ayuda para el abm de jurisdicciones
     */
     ayudaJurisdicciones(){

        // reiniciamos
        sesion.reiniciar();

        // definimos el diálogo y lo mostramos
        $('#win-jurisdiccionews').window({
            width:850,
            height:500,
            modal:true,
            title: "Ayuda Jurisdicciones Nacionales",
            minimizable: false,
            closable: true,
            href: 'jurisdicciones/ayuda.html',
            loadingMessage: 'Cargando',
            border: 'thin'
        });

        // centramos el formulario
        $('#win-jurisdicciones').window('center');

    }

    /**
     * @author Claudio Invernizzi <cinvernizzi@gmail.com>
     * Método genérico que cierra el layer emergente de
     * búsquedas
     */
     cerrarEmergente(){

        // cerramos el layer
        $('#win-jurisdicciones').window('close');

    }

}
