<?php

namespace bbit\contao\util\log;

class Logger {

	const LOG_ERROR = 'system/logs/error_';

	const LOG_REPORTING = 'system/logs/reporting_';

	public static function log($msg, $log = null) {
		if($msg instanceof \Exception) {
			$msg = static::logMessageFor($msg);
			$log === null && $log = static::LOG_ERROR;
		} else {
			$log === null && $log = static::LOG_REPORTING;
		}

		$msg = PHP_EOL . '[' . date('Y-m-d\\TH:i:s') . '] ' . ltrim($msg, PHP_EOL);

		static $date;
		$date || $date = date('Y-m-d');
		file_put_contents(TL_ROOT . '/' . $log . $date . '.log', $msg, FILE_APPEND);
	}

	public static function logMessageFor(\Exception $e, $printPrev = true) {
		$msg .= PHP_EOL . "\t" . $e->getFile() . '@L' . $e->getLine() . ': ';
		$e->getCode() && $msg .= '(' . $e->getCode() . ') ';
		$msg .= $e->getMessage();
		$msg .= PHP_EOL . "\t\t" . preg_replace('@(?:\r\n?|\n)@', PHP_EOL . "\t\t", $e->getTraceAsString());
		$printPrev && $prev = $e->getPrevious();
		$prev && $msg .= PHP_EOL . "\t" . 'Caused by:' . static::logMessageFor($prev);
		return $msg;
	}

}
