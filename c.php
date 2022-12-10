<?php

echo "start";
$redis = new Redis();
//Connecting to Redis
$redis->connect('127.0.0.1', 6379);
$redis->auth('');

if ($redis->ping()) {
	print_r("ping");
//    $redis->set("message", "Hello world");
    $data = $redis->get("laravel_database_KZAS12010001");
	$allKeys = $redis->keys('*');
	print_r($data);
	print_r($allKeys);
}

?>
