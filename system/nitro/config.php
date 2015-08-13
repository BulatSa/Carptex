<?php 
define('DS', DIRECTORY_SEPARATOR);
define('NITRO_FOLDER',DIR_SYSTEM.'nitro'.DS);
define('NITRO_DBCACHE_FOLDER',DIR_SYSTEM.'nitro'.DS.'cache'.DS.'dbcache'.DS);
define('NITRO_PAGECACHE_FOLDER',DIR_SYSTEM.'nitro'.DS.'cache'.DS.'pagecache'.DS);
define('NITRO_PERSISTENCE',DIR_SYSTEM.'nitro'.DS.'data'.DS.'persistence.tpl');
define('NITRO_MODE',0); // 0 - Production; 1 - Debug mode;
define('NITROCACHE_TIME',86400);
define('NITRO_IGNORE_AJAX_REQUESTS',TRUE);
define('NITRO_IGNORE_POST_REQUESTS',TRUE);
?>