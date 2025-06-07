<?php

Autoloader::add_core_namespace('MyAuth');

Autoloader::add_classes(array(
	'MyAuth\\Auth_Login_Simpleauth'    => __DIR__.'/classes/auth/login/simpleauth.php',
));

/* End of file bootstrap.php */
