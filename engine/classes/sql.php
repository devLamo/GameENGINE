<?php
/**
 * Продукт GameENGINE
 * Сайт: http://lamo2k123/
 * Дата: 20.05.12
 * Время: 2:38
 */

if( !defined( 'GameENGINE' ) ) {

	// @TODO: Переадресация на страницу с ошибками.
	die ( "Hacking attempt!" );
}

class sql {

	private $connectID 		= FALSE;
	private $queryID 		= FALSE;
	private $queryNum 		= 0;
	private $error 			= NULL;
	private $errorNum 		= 0;
	private $mysqliVersion 	= NULL;
	private $timeTaken 		= 0;


	/*
	 * Соединение с базой данных.
	 */
	private function connect( $sDataBaseUser, $sDataBasePassword, $sDataBaseName, $sDataBaseHost = 'localhost', $sDataBasePort = '3306', $bShowError = TRUE ) {

		$this->connectID = @mysqli_connect( $sDataBaseHost, $sDataBaseUser, $sDataBasePassword, $sDataBaseName, $sDataBasePort );

		if( !$this->connectID ) {
			if( $bShowError ) {
				$this->display_error( mysqli_connect_error(), '1' );
			} else {
				return false;
			}
		}

		$this->mysqliVersion = mysqli_get_server_info( $this->connectID );

		if( !defined( 'COLLATE' ) ) {
			define( 'COLLATE', 'utf8' );
		}

		mysqli_query( $this->connectID, "SET NAMES '" . COLLATE . "'" );

		return true;
	}

	public function query( $sQuery, $bShowError = TRUE ) {
		global $configDB;

		$timeBefore = $this->getRealTime();

		if( !$this->connectID ) {
			$this->connect( $configDB['user'], $configDB['password'], $configDB['name'], $configDB['host'], $configDB['port'] );
		}

		if( !( $this->queryID = mysqli_query( $this->connectID, $sQuery ) ) ) {

			$this->error = mysqli_error( $this->connectID );
			$this->errorNum = mysqli_errno( $this->connectID );

			if( $bShowError ) {
				$this->display_error( $this->error, $this->errorNum, $sQuery );
			}

		}

		$this->timeTaken += $this->getRealTime() - $timeBefore;

		$this->queryNum ++;

		return $this->queryID;
	}

	public function getRow( $queryID = '' ) {

		if( $queryID == '') {
			$queryID = $this->queryID;
		}

		return mysqli_fetch_assoc( $queryID );
	}

	public function get_affected_rows() {

		return mysqli_affected_rows( $this->connectID );

	}

	public function get_array( $queryID = '') {

		if( $queryID == '' ) {
			$queryID = $this->queryID;
		}

		return mysqli_fetch_array( $queryID );
	}

	public function superQuery( $query ) {

		$this->query( $query );
		$data = $this->get_row();
		$this->free();

		return $data;

	}

	public function numRows( $queryID = '') {

		if( $queryID == '') {
			$queryID = $this->queryID;
		}

		return mysqli_num_rows( $queryID );
	}

	public function insertID() {

		return mysqli_insert_id( $this->connectID );

	}

	public function getResultFields( $queryID = '' ) {

		if( $$queryID == '') {
			$$queryID = $this->$queryID;
		}

		while( $field = mysqli_fetch_field( $queryID ) ) {
			$fields[] = $field;
		}

		return $fields;
	}

	public function safesql( $source ) {
		global $configDB;

		if( !$this->connectID ) {
			$this->connect( $configDB['user'], $configDB['password'], $configDB['name'], $configDB['host'], $configDB['port'] );
		}

		if( $this->connectID ) {
			return mysqli_real_escape_string( $this->connectID, $source );
		} else {
			return addslashes( $source );
		}

	}

	public function free( $queryID = '' ) {

		if( $queryID == '') {
			$queryID = $this->queryID;
		}

		@mysqli_free_result( $queryID );
	}

	public function close() { @mysqli_close( $this->connectID ); }

	public function getRealTime() {

		list( $seconds, $microSeconds ) = explode( ' ', microtime() );
		return ( (float)$seconds + (float)$microSeconds );

	}

	// @TODO: Привести в порядок.
	public function display_error($error, $error_num, $query = '')
	{
		if($query) {
			// Safify query
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
			$query_str = "$query";
		}

		echo '<?xml version="1.0" encoding="iso-8859-1"?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<title>MySQL Fatal Error</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
		<style type="text/css">
		<!--
		body {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 10px;
			font-style: normal;
			color: #000000;
		}
		-->
		</style>
		</head>
		<body>
			<font size="4">MySQL Error!</font>
			<br />------------------------<br />
			<br />

			<u>The Error returned was:</u>
			<br />
				<strong>'.$error.'</strong>

			<br /><br />
			</strong><u>Error Number:</u>
			<br />
				<strong>'.$error_num.'</strong>
			<br />
				<br />

			<textarea name="" rows="10" cols="52" wrap="virtual">'.$query_str.'</textarea><br />

		</body>
		</html>';

		exit();
	}

}