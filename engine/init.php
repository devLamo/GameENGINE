<?php
/**
 * Продукт GameENGINE
 * Сайт: http://lamo2k123/
 * Дата: 19.05.12
 * Время: 23:44
 */

if( !defined( 'GameENGINE' ) ) {

	// @TODO: Переадресация на страницу с ошибками.
	die ( "Hacking attempt!" );
}

require_once( dirEngine . '/classes/SplClassLoader.php' );

$oClassesLoader = new SplClassLoader( NULL, dirEngine . '/classes' );
$oClassesLoader->register();

$oTimer = new microTimer();
$oTimer->start();

require_once( dirEngine . '/data/config.php' );
require_once( dirEngine . '/data/configDB.php' );


$oSql = new sql();
$oSystemCache = new systemCache();


/*
 * Получение информации по группам пользователей.
 */
$aUserGroup = $oSystemCache->get( 'userGroup' );

if( !$aUserGroup ) {

	$oSql->query( "SELECT * FROM `" . $configDB['prefix'] . "_usergroups` ORDER BY `id` ASC" );

	while( $aRow = $oSql->getRow() ) {

		$aUserGroup[$aRow['id']] = array();

		foreach( $aRow as $sKey => $sValue ) {

			$aUserGroup[$aRow['id']][$sKey] = stripslashes( $sValue );

		}

	}

	$oSystemCache->set( 'userGroup', $aUserGroup );
	$oSql->free();
}

/*
 * Получение информации по забаненным пользователям.
 */
$aUserBanned = $oSystemCache->get( 'userBanned' );

if( !$aUserBanned ) {

	$oSql->query( "SELECT * FROM `" . $configDB['prefix'] . "_banned`" );

	while( $aRow = $oSql->getRow() ) {

		if( $aRow['users_id'] ) {

			$aUserBanned['users_id'][$aRow['users_id']] = array (
				'users_id' 	=> $aRow['users_id'],
				'descr' 	=> stripslashes( $aRow['descr'] ),
				'date' 		=> $aRow['date']
			);

		} else {

			if( count( explode( '.', $aRow['ip'] ) ) == 4 ) {

				$aUserBanned['ip'][$aRow['ip']] = array(
					'ip' 	=> $row['ip'],
					'descr'	=> stripslashes( $aRow['descr'] ),
					'date' 	=> $aRow['date']
				);

			}
			elseif( strpos( $aRow['ip'], '@' ) !== FLASE ) {

				$aUserBanned['email'][$aRow['ip']] = array(
					'email'	=> $aRow['ip'],
					'descr'	=> stripslashes( $aRow['descr'] ),
					'date' 	=> $aRow['date']
				);
			}
			else {

				$aUserBanned['name'][$aRow['ip']] = array (
					'name' 	=> $aRow['ip'],
					'descr'	=> stripslashes( $aRow['descr'] ),
					'date' 	=> $aRow['date']
				);

			}

		}

	}

	$oSystemCache->set( 'userBanned', $aUserBanned );
	$oSql->free();
}

/*
 * Получение информации по новостным категориям.
 */
$aNewsCategory = $oSystemCache->get( 'newsCategory' );

if( !$aNewsCategory ) {

	$oSql->query ( "SELECT * FROM `" . $configDB['prefix'] . "_category` ORDER BY `posi` ASC" );

	while( $aRow = $oSql->getRow() ) {

		$aNewsCategory[$aRow['id']] = array();

		foreach( $aRow as $sKey => $sValue ) {
			$aNewsCategory[$aRow['id']][$sKey] = stripslashes( $sValue );
		}

	}

	$oSystemCache->set( 'newsCategory', $aNewsCategory );
	$oSql->free();
}

$oLang = new language();

$oTpl = new templates();

$oTpl->loadTemplate( 'login.html' );

$oTpl->set( '{authMethod}', ( $config['authMethod'] == 'email' ) ? $oLang->get( 'authEmail' ) : $oLang->get( 'authLogin' ) );

$oTpl->set( '{urlRegistration}', $config['urlHome'] . 'index.php?do=Registration' );
$oTpl->set( '{urlRecoveryPassword}', $config['urlHome'] . 'index.php?do=RecoveryPassword' );
$oTpl->set( '{urlLogout}', $config['urlHome'] . 'index.php?action=Logout' );
$oTpl->set( '{urlAdmin}', $config['urlHome'] . $config['adminPath'] );

$oTpl->set( '{login}', $member_id['name'] );

$oTpl->compile( 'login' );
$oTpl->middleClear();
























