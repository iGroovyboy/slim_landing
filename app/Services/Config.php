<?php


namespace App\Services;


class Config {
	protected static string $configDir = 'config';
	protected static string $configFilename = 'app.json';

	static $config = [];

	public function __invoke( $filename ) {

	}

	public static function get( $section, $key = null, $default = null ) {
		if ( $key ) {
			return self::$config[ $section ][ $key ] ?? ( $default ?: null );
		}

		return self::$config[ $section ] ?? ( $default ?: null );
	}

	public static function has( $section, $key = null ) {
		return $key
			? isset( self::$config[ $section ][ $key ] )
			: isset( self::$config[ $section ] );
	}

	public static function set( $name, $value ) {
		self::$config[ $name ] = $value;
	}

	protected static function getConfigPath() {
		return ROOT_DIR . DIRECTORY_SEPARATOR . self::$configDir . DIRECTORY_SEPARATOR;
	}

	public static function setRootPath( string $path ) {
		self::$rootPath = $path;
	}

	public static function setConfigDir( string $dir ) {
		self::$configDir = $dir;
	}

	public static function setConfigFilename( string $filename ) {
		self::$configFilename = $filename;
	}

	public static function use( $filename ) {
		self::setConfigFilename( $filename );

		return self::load();
	}

	/**
	 * Save config data to a json file
	 *
	 * @return string|false
	 */
	public static function save() {
		return file_put_contents(
			self::getConfigPath(),
			json_encode( self::$config ),
			LOCK_EX
		);
	}

	/**
	 * Loads config data from a json file
	 *
	 * @param string|null $path
	 *
	 * @return string|false
	 */
	public static function load( string $filename = null ) {

		$path = self::getConfigPath() . $filename;

		$rawFile = file_get_contents( $path );

		if ( $rawFile ) {
			$section                  = pathinfo( $path )['filename'];
			self::$config[ $section ] = json_decode( $rawFile, true );
		}

		return $rawFile;
	}

	public static function loadAll( $dir = null ) {
		$dir  = $dir ?? self::$configDir;
		$path = realpath( ROOT_DIR . DIRECTORY_SEPARATOR . $dir );

		self::setConfigDir( $dir ?? self::$configDir );

		if ( ! is_dir( $path ) ) {
		}
		if ( ! file_exists( $path ) ) {
		}

		$files = array_diff( scandir( $path ), array( '..', '.', 'readme.md' ) );

		foreach ( $files as $file ) {
			self::load( $file );
		}
	}

	public static function clear() {
		self::$config = [];
	}
}