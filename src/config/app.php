<?php
$dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_PATH', ($dir === '' || $dir === '/') ? '/' : $dir . '/');
