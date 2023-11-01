<?php
namespace App\Entity;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


class Chat implements MessageComponentInterface {

    protected $clients;
    public $serializer;

    public function __construct(JsonEncoder $serializer) {
        $this->clients = new \SplObjectStorage;
        $this->serializer = $serializer;
    }

    public function onOpen(ConnectionInterface $conn) {

        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            $isFrom = $from == $client;
            $array = ['msg' => $msg, 'id' => $client->resourceId, 'isFrom' => $isFrom];
            $json = $this->serializer->encode($array, 'json');
            $client->send($json);
        }
    }

    public function onClose(ConnectionInterface $conn) {

        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}