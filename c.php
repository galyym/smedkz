<?php

echo "start";
$redis = new Redis();
//Connecting to Redis
$redis->connect('127.0.0.1', 6379);
$redis->auth('');

if ($redis->ping()) {
	print_r("ping");
    $redis->set("message", "Hello world");
    $data = $redis->get("message");
	$allKeys = $redis->keys('*');
	print_r($allKeys);
}

?>
