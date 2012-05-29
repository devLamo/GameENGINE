<?php
/**
 * Продукт GameENGINE
 * Сайт: http://lamo2k123/
 * Дата: 20.05.12
 * Время: 18:54
 */

if( !defined( 'GameENGINE' ) ) {

	// @TODO: Переадресация на страницу с ошибками.
	die ( "Hacking attempt!" );
}

class language {

	private $aLanguage;

	public function __construct() {
		global $config;

		if( isset( $config['lang'] ) ) {
			$loadLang = $config['lang'];
		} else {
			$loadLang = 'Russian';
		}

		if( file_exists( dirRoot . '/language/' . $loadLang . '/site.lng' ) ) {

			$this->aLanguage = include_once( dirRoot . '/language/' . $loadLang . '/site.lng' );

		} else {

			// @TODO: Переадресация на страницу с ошибками.
			die ( 'Файл языков не найден!' );

		}

	}

	public function get( $sKey ) {

		return $this->aLanguage[$sKey];

	}

}
