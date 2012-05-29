<?php
/**
 * Продукт GameENGINE
 * Сайт: http://lamo2k123/
 * Дата: 20.05.12
 * Время: 8:55
 */

if( !defined( 'GameENGINE' ) ) {

	// @TODO: Переадресация на страницу с ошибками.
	die ( "Hacking attempt!" );
}

class templates {

	private $dirTemplate	= null;
	private $blockData 		= array();
	private $data 			= array();
	private $copyTemplate 	= null;
	private $template 		= null;
	private $parseTime 		= 0;
	private	$result 		= array( 'info' => '', 'vote' => '', 'speedbar' => '', 'content' => '' );

	/*
	 * Конструктор класса.
	 */
	public function __construct() { $this->setDirTemplate(); }

	/*
	 * Получение директории шаблонов.
	 */
	public function getDirTemplate() { return $this->dirTemplate; }

	/*
	 * Установка директории шаблонов.
	 */
	public function setDirTemplate( $dirTemplate = 'Default' ) {
		global $config;

		if( $dirTemplate == 'Default' ) {

			$this->dirTemplate = dirRoot . '/templates/' . $config['template'];

		} else {

			$this->dirTemplate = $dirTemplate;

		}

	}

	/*
	 * Получение результата компиляции.
	 */
	public function getResulte( $sName ) { return $this->result[$sName]; }

	public function set( $name, $value ) {

		if( is_array( $value ) && count( $value ) ) {

			foreach( $value as $sKey => $sValue ) {

				$this->set( $sKey, $sValue );

			}

		} else {

			$this->data[$name] = $value;

		}
	}

	public function setBlock( $name, $value ) {

		if( is_array( $value ) && count( $value ) ) {

			foreach ( $value as $sKey => $sValue ) {
				$this->setBlock( $sKey, $sValue );
			}

		} else {

			$this->blockData[$name] = $value;

		}
	}

	public function loadTemplate( $sName ) {

		$timeBefore = $this->getRealTime();

		if( $sName == '' || !file_exists( $this->dirTemplate . '/' . $sName ) ) {

			// @TODO: Нормальный вывод ошибок.
			die( 'Невозможно загрузить шаблон: ' . str_replace( dirRoot, '', $this->dirTemplate ) . '/' . $sName );

			return false;
		}

		$this->template = file_get_contents( $this->dirTemplate . '/' . $sName );

		if (strpos ( $this->template, "[aviable=" ) !== false) {
			$this->template = preg_replace ( "#\\[aviable=(.+?)\\](.*?)\\[/aviable\\]#ies", "\$this->check_module('\\1', '\\2')", $this->template );
		}

		if (strpos ( $this->template, "[not-aviable=" ) !== false) {
			$this->template = preg_replace ( "#\\[not-aviable=(.+?)\\](.*?)\\[/not-aviable\\]#ies", "\$this->check_module('\\1', '\\2', false)", $this->template );
		}

		if (strpos ( $this->template, "[not-group=" ) !== false) {
			$this->template = preg_replace ( "#\\[not-group=(.+?)\\](.*?)\\[/not-group\\]#ies", "\$this->check_group('\\1', '\\2', false)", $this->template );
		}

		if (strpos ( $this->template, "[group=" ) !== false) {
			$this->template = preg_replace ( "#\\[group=(.+?)\\](.*?)\\[/group\\]#ies", "\$this->check_group('\\1', '\\2')", $this->template );
		}

		if (strpos ( $this->template, "[page-count=" ) !== false) {
			$this->template = preg_replace ( "#\\[page-count=(.+?)\\](.*?)\\[/page-count\\]#ies", "\$this->check_page('\\1', '\\2')", $this->template );
		}


		if (strpos ( $this->template, "[not-page-count=" ) !== false) {
			$this->template = preg_replace ( "#\\[not-page-count=(.+?)\\](.*?)\\[/not-page-count\\]#ies", "\$this->check_page('\\1', '\\2', false)", $this->template );
		}
/*
		if( strpos( $this->template, "{include file=" ) !== false ) {

			$this->template = preg_replace( "#\\{include file=['\"](.+?)['\"]\\}#ies", "\$this->load_file('\\1', 'tpl')", $this->template );

		}
*/
		$this->copyTemplate = $this->template;

		$this->parseTime += $this->getRealTime() - $timeBefore;
		return true;
	}

	public function compile( $tpl, $bPrint = FALSE ) {

		$timeBefore = $this->getRealTime();

		if( count( $this->blockData ) ) {

			foreach ( $this->blockData as $sKey => $sValue ) {

				$findPreg[] = $sKey;
				$replacePreg[] = $sValue;

			}

			$this->copyTemplate = preg_replace( $findPreg, $replacePreg, $this->copyTemplate );
		}

		foreach ( $this->data as $sKey => $sValue ) {

			$find[] = $sKey;
			$replace[] = $sValue;

		}

		$this->copyTemplate = str_replace( $find, $replace, $this->copyTemplate );

		/*
		if( strpos( $this->copy_template, "{include file=" ) !== false ) {

			$this->copy_template = preg_replace( "#\\{include file=['\"](.+?)['\"]\\}#ies", "\$this->load_file('\\1', 'php')", $this->copy_template );

		}*/

		if( isset( $this->result[$tpl] ) ) {

			$this->result[$tpl] .= $this->copyTemplate;

		} else {

			$this->result[$tpl] = $this->copyTemplate;

		}

		$this->lightClear();

		if( $bPrint ) {

			echo $this->result[$tpl];

		}

		$this->parseTime += $this->getRealTime() - $timeBefore;
	}

	public function lightClear() {

		$this->data 		= array();
		$this->blockData 	= array();
		$this->copyTemplate	= $this->template;

	}

	public function middleClear() {

		$this->data 		= array ();
		$this->blockData 	= array ();
		$this->copyTemplate	= NULL;
		$this->template 	= NULL;

	}

	public function getRealTime() {

		list ( $seconds, $microSeconds ) = explode( ' ', microtime() );
		return ( ( float )$seconds + ( float )$microSeconds );

	}
/*
	public $dirTemplate = NULL;
	var $template = null;
	var
	var


	var $allow_php_include = true;

	var $template_parse_time = 0;







	function load_file( $name, $include_file = "tpl" ) {
		global $db, $is_logged, $member_id, $cat_info, $config, $user_group, $category_id, $_TIME, $lang, $smartphone_detected, $dle_module;

		$name = str_replace( '..', '', $name );

		$url = @parse_url ($name);
		$type = explode( ".", $url['path'] );
		$type = strtolower( end( $type ) );

		if ($type == "tpl") {

			return $this->sub_load_template( $name );

		}

		if ($include_file == "php") {

			if ( !$this->allow_php_include ) return;

			if ($type != "php") return "To connect permitted only files with the extension: .tpl or .php";

			if ($url['path']{0} == "/" )
				$file_path = dirname (ROOT_DIR.$url['path']);
			else
				$file_path = dirname (ROOT_DIR."/".$url['path']);

			$file_name = pathinfo($url['path']);
			$file_name = $file_name['basename'];

			if ( stristr ( php_uname( "s" ) , "windows" ) === false )
				$chmod_value = @decoct(@fileperms($file_path)) % 1000;

			if ( stristr ( dirname ($url['path']) , "uploads" ) !== false )
				return "Include files from directory /uploads/ is denied";

			if ( stristr ( dirname ($url['path']) , "templates" ) !== false )
				return "Include files from directory /templates/ is denied";

			if ($chmod_value == 777 ) return "File {$url['path']} is in the folder, which is available to write (CHMOD 777). For security purposes the connection files from these folders is impossible. Change the permissions on the folder that it had no rights to the write.";

			if ( !file_exists($file_path."/".$file_name) ) return "File {$url['path']} not found.";

			if ( $url['query'] ) {

				parse_str( $url['query'] );

			}

			ob_start();
			$tpl = new dle_template( );
			$tpl->dir = TEMPLATE_DIR;
			include $file_path."/".$file_name;
			return ob_get_clean();

		}

		return '{include file="'.$name.'"}';


	}

	function sub_load_template( $tpl_name ) {

		$tpl_name = totranslit( $tpl_name );

		if( $tpl_name == '' || ! file_exists( $this->dir . "/" . $tpl_name ) ) {
			return "Отсутствует файл шаблона: " . $tpl_name ;
			return false;
		}
		$template = file_get_contents( $this->dir . "/" . $tpl_name );

		if (strpos ( $template, "[aviable=" ) !== false) {
			$template = preg_replace ( "#\\[aviable=(.+?)\\](.*?)\\[/aviable\\]#ies", "\$this->check_module('\\1', '\\2')", $template );
		}

		if (strpos ( $template, "[not-aviable=" ) !== false) {
			$template = preg_replace ( "#\\[not-aviable=(.+?)\\](.*?)\\[/not-aviable\\]#ies", "\$this->check_module('\\1', '\\2', false)", $template );
		}

		if (strpos ( $template, "[not-group=" ) !== false) {
			$template = preg_replace ( "#\\[not-group=(.+?)\\](.*?)\\[/not-group\\]#ies", "\$this->check_group('\\1', '\\2', false)", $template );
		}

		if (strpos ( $template, "[group=" ) !== false) {
			$template = preg_replace ( "#\\[group=(.+?)\\](.*?)\\[/group\\]#ies", "\$this->check_group('\\1', '\\2')", $template );
		}

		if (strpos ( $this->template, "[page-count=" ) !== false) {
			$template = preg_replace ( "#\\[page-count=(.+?)\\](.*?)\\[/page-count\\]#ies", "\$this->check_page('\\1', '\\2')", $template );
		}


		if (strpos ( $this->template, "[not-page-count=" ) !== false) {
			$template = preg_replace ( "#\\[not-page-count=(.+?)\\](.*?)\\[/not-page-count\\]#ies", "\$this->check_page('\\1', '\\2', false)", $template );
		}

		return $template;
	}

	function check_module($aviable, $block, $action = true) {
		global $dle_module;

		$aviable = explode( '|', $aviable );

		$block = str_replace( '\"', '"', $block );

		if( $action ) {

			if( ! (in_array( $dle_module, $aviable )) and ($aviable[0] != "global") ) return "";
			else return $block;

		} else {

			if( (in_array( $dle_module, $aviable )) ) return "";
			else return $block;

		}

	}

	function check_group($groups, $block, $action = true) {
		global $member_id;

		$groups = explode( ',', $groups );

		if( $action ) {

			if( ! in_array( $member_id['user_group'], $groups ) ) return "";

		} else {

			if( in_array( $member_id['user_group'], $groups ) ) return "";

		}

		$block = str_replace( '\"', '"', $block );

		return $block;

	}

	function check_page($pages, $block, $action = true) {

		$pages = explode( ',', $pages );
		$page = intval($_GET['cstart']);

		if ( $page < 1 ) $page = 1;

		if( $action ) {

			if( !in_array( $page, $pages ) ) return "";

		} else {

			if( in_array( $page, $pages ) ) return "";

		}

		$block = str_replace( '\"', '"', $block );

		return $block;

	}





	function global_clear() {

		$this->data = array ();
		$this->block_data = array ();
		$this->result = array ();
		$this->copy_template = null;
		$this->template = null;

	}





*/

}
