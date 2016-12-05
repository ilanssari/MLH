<?php
namespace AppBundle\Sockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $cl;

    public function __construct() {
        $this->cl = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $cn) {
        $this->cl->attach($cn);

        echo "nouvelle connexion! ({$cn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $nR = count($this->cl) - 1;
        echo sprintf('%d a envoye un msg "%s" au %d autre connexion%s' . "\n"
            , $from->resourceId, $msg, $nR, $nR == 1 ? '' : 's');

        foreach ($this->cl as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $cn) {
        $this->cl->detach($cn);

        echo "connexion {$cn->resourceId} a quitte\n";
    }

    public function onError(ConnectionInterface $cn, \Exception $e) {
        echo "une erreur est survenue: {$e->getMessage()}\n";

        $cn->close();
    }
}