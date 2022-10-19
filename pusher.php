require __DIR__ . '/vendor/autoload.php';

$pusher = new Pusher\Pusher(
  "local",
  "local",
  "local",
  array('cluster' => 'mt1', 'host' => 'localhost', 'port' => 6001)
);

$pusher->trigger('my-channel', 'my-event', array('message' => 'hello world'));