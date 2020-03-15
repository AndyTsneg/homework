<?php

namespace Spider\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class Worker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'Spider:Worker';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Grab data';


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

        $callback = function ($msg) {
            $this->go($msg->body);
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
        $url = $message['url'];
        echo "Get Task : ".$message['name'];
        echo "\r\n";
        $html = file_get_contents($url);
        preg_match('/<div class="TODAY_CONTENT">(.*?)<\/div>/s', $html, $matches);
        $starArray['green']  =  $this->getStar("txt_green",$matches[0]);
        $starArray['pink']   =  $this->getStar("txt_pink",$matches[0]);
        $starArray['blue']   =  $this->getStar("txt_blue",$matches[0]);
        $starArray['orange'] =  $this->getStar("txt_orange",$matches[0]);
        $contentArray = $this->getContent($matches[0]);

        $body = array_merge($starArray,$contentArray);
        $message['body'] = $body;

        $this->deliverMessage($message);

    }

    public function deliverMessage($message)
    {
        $queueLocation = $_ENV['RABBITMQ_HOST'];
        $queuePort     = $_ENV['RABBITMQ_PORT'];
        $queueId       = $_ENV['RABBITMQ_LOGIN'];
        $queuePw       = $_ENV['RABBITMQ_PASSWORD'];
        $queueName     = $_ENV['RABBITMQ_QUEUE_CONSUM'];

        $connection = new AMQPStreamConnection($queueLocation, $queuePort, $queueId, $queuePw);

        $channel = $connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);

        $message = json_encode($message);

        $msg = new AMQPMessage(
            $message,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $channel->basic_publish($msg, '', $queueName);

        $channel->close();
        $connection->close();
    }

    /**
     * Only getting star
     *
     * @param $className
     * @param $html
     * @return int
     */
    public function getStar($className,$html){
        preg_match_all('#<span class=\"'.$className.'">(.*?)</span>#', $html, $matches);
        $resultStr = str_split($matches[1][0],3);
        $totalStar = 0;
        foreach ($resultStr as $singleWord) {
            if($singleWord=='★'){
                $totalStar++;
            }
        }
        return $totalStar;
    }

    /**
     * Only get content
     *
     * @param $html
     * @return array
     */
    public function getContent($html){
        $mappingTable = ['1'=>'green_text','3'=>'pink_text','5'=>'blue_text','7'=>'orange_text'];
        $contentArray = array();
        preg_match_all('#<p>(.*?)</p>#', $html, $matches);
        for($i=1;$i<=7;$i+=2){
            $matches[0][$i] = str_replace("<p>","",$matches[0][$i]);
            $matches[0][$i] = str_replace("</p>","",$matches[0][$i]);
            $contentArray[$mappingTable[$i]] = $matches[0][$i];
        }
        return $contentArray;
    }



}
