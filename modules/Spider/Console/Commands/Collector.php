<?php

namespace Spider\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Spider\Services\TaskManagement;

class Collector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'Spider:Collector';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'I am a collector. ';


    public function __construct() {
        parent::__construct();
    }


    public function handle() {



        $queueLocation = $_ENV['RABBITMQ_HOST'];
        $queuePort     = $_ENV['RABBITMQ_PORT'];
        $queueId       = $_ENV['RABBITMQ_LOGIN'];
        $queuePw       = $_ENV['RABBITMQ_PASSWORD'];
        $queueName     = $_ENV['RABBITMQ_QUEUE_CONSUM'];

        $connection = new AMQPStreamConnection($queueLocation, $queuePort, $queueId, $queuePw);
        $channel = $connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);

        $callback = function ($msg) {
            $message = json_decode($msg->body,true);
            TaskManagement::insertResult($message);
            echo "DONE - ".$message['name'];
            echo "\r\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queueName, '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();


    }

    public function go($body)
    {
        $message = json_decode($body,true);
        print_r($message);

    }


}
