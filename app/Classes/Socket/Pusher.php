<?php

namespace App\Classes\Socket;

use App\Classes\Socket\Base\BasePusher;
use ZMQContext;

class Pusher extends BasePusher
{
    /**
     * Отправляем данные в App\Console\Commands\PushServer который затем
     * обратится сюда же (в объект класса Pusher) (в указынный метод)
     *
     * Для того чтобы данные ретранслировать подписчикам
     *
     * @param $data array
     */

    static function sentDataToServer(array $data){
        $context = new ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, "my pusher");

        $socket->connect('tcp://127.0.0.1:8000');

        $data = json_encode($data);

        $socket->send($data);
    }

    public function broadcast($jsonDataToSend){
        $aDataToSend = json_decode($jsonDataToSend, true);

        $subscribedTopics = $this->getSubscribedTopics();

        if (isset($subscribedTopics[$aDataToSend["topic_id"]])){
            $topic = $subscribedTopics[$aDataToSend["topic_id"]];
            $topic->broadcast($aDataToSend);
        }
    }
}
