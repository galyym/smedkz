<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use App\Classes\Socket\Pusher;
use React\EventLoop\Loop as ReactLoop;
use React\ZMQ\Context as ReactContex;
use React\Socket\SocketServer as ReactServer;
use Ratchet\Wamp\WampServer;

class PushServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socketpush:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $loop = ReactLoop::get();

        $pusher = new Pusher;

        $context = new ReactContex($loop);

        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:8000');
        $pull->on('message', [$pusher, 'broadcast']);

        //Поднимаем сервер который будет отдавать информацию подписавшимся на нее клиентам
        $webSock = new ReactServer("0.0.0.0:8888", [], $loop);
//        $webSock->listen(8080, '0.0.0.0');

        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $this->info("Run handle");

        $loop->run();
    }
}
