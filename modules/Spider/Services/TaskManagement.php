<?php

namespace Spider\Services;

use Spider\Model\Task;
use Spider\Model\TaskRawData;

class TaskManagement
{
    public static function getTask()
    {
        return Task::whereRaw('status = 0', [1])->get();
    }

    /**
     * @param $message
     */
    public static function insertResult($message)
    {
        $date = date('Y-m-d');

        $taskRawData = new TaskRawData;
        $taskRawData->task_id = $message['id'];
        $taskRawData->name = $message['name'];
        $taskRawData->green = $message['body']['green'];
        $taskRawData->pink = $message['body']['pink'];
        $taskRawData->blue = $message['body']['blue'];
        $taskRawData->orange = $message['body']['orange'];
        $taskRawData->green_text = $message['body']['green_text'];
        $taskRawData->pink_text = $message['body']['pink_text'];
        $taskRawData->blue_text = $message['body']['blue_text'];
        $taskRawData->orange_text = $message['body']['orange_text'];
        $taskRawData->currect_date = $date;
        $taskRawData->save();

    }
}
