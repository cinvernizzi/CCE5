<?php

/**
 *
 * Class Conexion | clases/conexion.class.php
 *
 * @package     Diagnostico
 * @subpackage  Clases
 * @author      Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 * @version     1.0 (11/05/2016)
 * @copyright   Copyright (c) 2017, INP
 *
 */

/**
 * Clase que sobrecarga el constructor de PDO
 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
 */
class Conexion extends PDO {

	// definimos el estado del servidor
	private $estado = "Desarrollo";

	// definimos las variables de clase
	private $tipo_de_base   = 'mysql';
	private $host           = 'localhost';
	private $nombre_de_base = 'diagnostico';
	private $usuario;
	private $contrasena;

	/**
	 * Constructor de la clase, sobrecarga el constructor
	 * de PDO y establece la conexión con la base
	 * @author Lic. Claudio Invernizzi <cinvernizzi@gmail.com>
	 */
	public function __construct() {

		// según el estado del servidor
		if ($this->estado == "Desarrollo") {

			// activamos la depuración
			ini_set('display_errors', 'On');
			error_reporting(E_ALL);

			// conexión para el servidor de desarrollo
			$this->usuario    = 'root';
			$this->contrasena = 'gamaeco';

		// si está en producción
		} else {

			// desactivamos la depuración
			ini_set('display_errors', 'Off');
			error_reporting(E_ALL&~E_DEPRECATED&~E_STRICT);

			// conexión para el servidor de producción
			$this->usuario    = 'claudefatala';
			$this->contrasena = 'pickard47alfatango';

		}

		// Sobreescribo el método constructor de la clase PDO
		// y utilizamos la captura de errores al mismo tiempo
                // fijamos que retorna en minusculas, el emulate
		// prepares a false disminuye ligeramente el rendimiento
		// pero mejora la captura de errores y mejora la
		// seguridad (mas difícil para la inyección sql)
		try {

			// establecemos las opciones de la conexión
			$options = array(PDO::ATTR_PERSISTENT => true, 
				             PDO::ATTR_EMULATE_PREPARES => false,
                             PDO::ATTR_CASE => PDO::CASE_LOWER,
				             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
				             PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");

			// llamamos al constructor del padre
			parent::__construct($this->tipo_de_base.':host='.$this->host.';dbname='.$this->nombre_de_base, $this->usuario, $this->contrasena, $options);

		// si hubo algún error lo presentamos
		} catch (PDOException $e) {

			// presenta el error
			echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: '.$e->getMessage();
			exit;

		}

	}

}
?>
