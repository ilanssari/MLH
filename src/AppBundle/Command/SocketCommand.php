<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use AppBundle\Sockets\Chat;

class SocketCommand extends Command
{
    protected function configure()
    {
        $this->setName('sockets:start-chat')
            ->setHelp("commencer le chat")
            ->setDescription('demarrer socket du chat')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Bonjour Chat',// A line
            '============',// Another line
            'pour commencer, merci d\'ouvrir le navigateur.',// Empty line
        ]);
        
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8080
        );
        
        $server->run();
    }
}