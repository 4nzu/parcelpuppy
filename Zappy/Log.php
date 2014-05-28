<?php

class Log {

    public static function write($msg) {
        $debug = debug_backtrace();
        $h = fopen(LOG_FILE."_".date('Ymd').".log", "at");
        fwrite($h, date('Ymd H:i:s')." ".$debug[0]['file'].":".$debug[0]['line']." ".$msg."\n");
        fclose($h);
    }

	public static function write_same_line($msg) {
        $debug = debug_backtrace();
        $h = fopen(LOG_FILE."_".date('Ymd').".log", "at");
        fwrite($h, date('Ymd H:i:s')." ".$debug[0]['file'].":".$debug[0]['line']." ".$msg);
        fclose($h);
    }

	public static function progress() {
        $debug = debug_backtrace();
        $h = fopen(LOG_FILE."_".date('Ymd').".log", "at");
        fwrite($h, '.');
        fclose($h);
    }

	public static function progress_done() {
        $debug = debug_backtrace();
        $h = fopen(LOG_FILE."_".date('Ymd').".log", "at");
        fwrite($h, "\n");
        fclose($h);
    }

}
