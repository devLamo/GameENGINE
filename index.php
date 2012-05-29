<?php
/**
 * Продукт GameENGINE
 * Сайт: http://lamo2k123/
 * Дата: 19.05.12
 * Время: 23:41
 */

define( 'GameENGINE', true );
define( 'dirRoot', dirname( __FILE__ ) );
define( 'dirEngine', dirRoot . '/engine' );

require_once( dirEngine . '/init.php' );


$oTpl->loadTemplate( 'layout.html' );

$oTpl->set( '{headTitle}', 'Мой текст тайтла' );
$oTpl->set( '{baseUrl}', 'http://localhost/' );
$oTpl->set( '{metaKeywords}', 'html, css, php' );
$oTpl->set( '{metaDescription}', 'Описание' );

$oTpl->set( '{login}', $oTpl->getResulte( 'login' ) );

$oTpl->compile( 'layout', TRUE );
