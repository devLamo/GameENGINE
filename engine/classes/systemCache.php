<?php
/**
 * Продукт GameENGINE
 * Сайт: http://lamo2k123/
 * Дата: 20.05.12
 * Время: 3:57
 */

if( !defined( 'GameENGINE' ) ) {

	// @TODO: Переадресация на страницу с ошибками.
	die ( "Hacking attempt!" );
}

class systemCache {

	public function set( $sFile, $sValue ) {

		file_put_contents( dirEngine . '/cache/system/' . $sFile . '.php', serialize( $sValue ) );
		@chmod( dirEngine . '/cache/system/' . $sFile . '.php', 0666 );

	}

	public function get( $sFile ) {

		return unserialize( @file_get_contents( dirEngine . '/cache/system/' . $sFile . '.php' ) );

	}

}
