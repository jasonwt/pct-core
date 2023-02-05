<?php
    declare(strict_types=1);

    namespace pct\core\debugging;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

	use pct\core\debugging\debugstring\DebugString;

    function DebugString() : string {
		return DebugString::DebugString(...func_get_args());
    }

	function DebugPrint() {
		echo DebugString::DebugString(...func_get_args());
    }
?>