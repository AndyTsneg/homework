<?php

namespace Spider\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Spider\Services\TaskManagement;

class Task extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'Spider:Launch';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Task Management';


    public function __construct() {
        parent::__construct();
    }


    public function handle() {

        $queueLocation = $_ENV['RABBITMQ_HOST'];
        $queuePort     = $_ENV['RABBITMQ_PORT'];
        $queueId       = $_ENV['RABBITMQ_LOGIN'];
        $queuePw       = $_ENV['RABBITMQ_PASSWORD'];
        $queueName     = $_ENV['RABBITMQ_QUEUE_WORKER'];




        $connection = new AMQPStreamConnection($queueLocation, $queuePort, $queueId, $queuePw);
        $channel = $connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);


        $tasks = TaskManagement::getTask();

        echo 'Launch spider';
        echo "\r\n";

        if (count($tasks)>0){
            foreach($tasks as $row){
                $message = array('id'=>$row->id,'name'=>$row->name,'url'=>$row->url);
                $message = json_encode($message);

                $msg = new AMQPMessage(
                    $message,
                    array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
                );

                $channel->basic_publish($msg, '', $queueName);
            }
        }


        $channel->close();
        $connection->close();
        
    }




}
