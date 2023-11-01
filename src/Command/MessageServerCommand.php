<?php

namespace App\Command;


use App\Entity\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\VarDumper\VarDumper;

#[AsCommand(
    name: 'app:chat',
    description: 'Launch socket servet',
    hidden: false,
)]
class MessageServerCommand extends Command {

    private $serializer;

    public function __construct(JsonEncoder $serializer, string $name = null)
    {
        parent::__construct($name);
        $this->serializer = $serializer;
    }

    public function execute(InputInterface $input, OutputInterface $output){

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat($this->serializer)
                )
            ),
            8080
        );

        $output->writeln('Lancement serveur socket.');
        $server->run();
    }

    protected function configure(): void{

    }

}