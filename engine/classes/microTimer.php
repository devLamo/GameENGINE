<?php
/**
 * Продукт GameENGINE
 * Сайт: http://lamo2k123/
 * Дата: 20.05.12
 * Время: 0:19
 */

if( !defined( 'GameENGINE' ) ) {

	// @TODO: Переадресация на страницу с ошибками.
	die ( "Hacking attempt!" );
}

class microTimer {

	private $startTime = NULL;
	private $stopTime = NULL;

	public function start() {

		$microTime = explode( ' ', microtime() );
		$microTime = $microTime[1] + $microTime[0];

		$this->startTime = $microTime;

	}

	public function stop() {

		$microTime = explode( ' ', microtime() );
		$microTime = $microTime[1] + $microTime[0];

		$this->stopTime = $microTime;

	}

	public function getStartTime() {

		return $this->startTime;

	}

	public function getStopTime() {

		return $this->stopTime;

	}

	public function getTotalTime() {

		return round( ( $this->stopTime - $this->startTime ), 5 );

	}

}
