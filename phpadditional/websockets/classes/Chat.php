<?php

require dirname(__DIR__) . '/vendor/autoload.php';

//require '../vendor/autoload.php';
//DO NOT FORGET CREATE NEW CLASS FOR DB


use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $dbConnection;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->dbConnection = new PDO
            ('mysql:dbname=mod4;host=127.0.0.1', 'root', 'root');
        $this->allOffline();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
//        $conn->send(json_encode(array('type'=>'users', 'data'=> $this->getAllUsers())));
        return 'hi';
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $req = json_decode($msg);
        echo $req->type . '-' . $req->data ."\n";
        switch ($req->type) {
            case 'message':
            {
                $numRecv = count($this->clients) - 1;
                echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
                    , $from->resourceId, $req->data[0], $numRecv, $numRecv == 1 ? '' : 's');

                foreach ($this->clients as $client) {
                    $client->send(json_encode(array('type'=>'message', 'data'=>array($req->data[1], $req->data[0]))));
                }

                $this->addMessage($req->data[1], $req->data[0]);
                break;
            }
            case 'auth' :
            {
                $data = $this->dbConnection->prepare('SELECT * FROM chat_users WHERE name =:name');
                $data->bindValue('name', $req->data);
                $data->execute();

                $result = $data->fetch(PDO::FETCH_ASSOC);

                $logRes = false;

                if ($result) {
                    echo $result['is_online'];
                    if ($result['is_online'] == 0) {
                        $data = $this->dbConnection->
                        prepare('UPDATE chat_users SET is_online = 1 WHERE name =:name');
                        $data->bindValue('name', $req->data);
                        $data->execute();
                        $logRes = true;
                        $from->send(json_encode(array('type'=>'login', 'data'=>'success')));
                    } else {
                        $from->send(json_encode(array('type'=>'login', 'data'=>'fail')));
                    }
                } else {
                    $data = $this->dbConnection->prepare('INSERT INTO chat_users VALUES(:id, :name, 1)');
                    $data->bindValue('id', $from->resourceId);
                    $data->bindValue('name', $req->data);
                    $data->execute();
                    $logRes = true;
                    $from->send(json_encode(array('type'=>'login', 'data'=>'success')));
                }

                if($logRes){
                    $lastMessages = $this->getLastMessages();
                    foreach ($lastMessages as $lastMessage) {
                        $from->send(
                            json_encode(
                                array('type'=>'message',
                                    'data'=>array($lastMessage['name'],$lastMessage['message']))));

//                        foreach ($this->clients as $client) {
//                            $client->send(json_encode(array('type'=>'users', 'data'=> $this->getAllUsers())));
//                        }
                    }
                }
                break;
            }
            case 'exit':
            {
                $data = $this->dbConnection->
                prepare('UPDATE chat_users SET is_online = 0 WHERE name =:name');
                $data->bindValue('name', $req->data);
                $data->execute();
                break;
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
//        $conn->send(json_encode(array('type'=>'users', 'data'=> $this->getAllUsers())));
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function allOffline()
    {
        $data = $this->dbConnection->prepare('UPDATE chat_users SET is_online = 0');
        $data->execute();
    }

    public function addMessage($name, $message){
        $data = $this->dbConnection->
        prepare('INSERT INTO messages (name, message) VALUES(:name, :message)');
        $data->bindValue('name', $name);
        $data->bindValue('message', $message);
        $data->execute();
    }

    public function getLastMessages(){
        $data = $this->dbConnection->prepare('SELECT * FROM 
              (SELECT * FROM messages ORDER BY id DESC LIMIT 30)
              t ORDER BY id ASC');
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers(ConnectionInterface $conn)
    {
        $data = $this->dbConnection->
            prepare('SELECT * FROM chat_users WHERE is_online = 1 ORDER BY name');
        $data->execute();
        $online = $data->fetchAll(PDO::FETCH_ASSOC);

        $data = $this->dbConnection->
            prepare('SELECT * FROM chat_users WHERE is_online = 0 ORDER BY name');
        $data->execute();
        $offline = $data->fetchAll(PDO::FETCH_ASSOC);

        $all = array_merge($online, $offline);
        $usernames = array();
        foreach($all as $user){
            array_push($usernames, $user['name']);
        }

        return $usernames;
    }
}
