<?php

namespace Spider\Model;

use Illuminate\Database\Eloquent\Model;

class TaskRawData extends Model
{

    private $task_id;
    private $name;
    private $green;
    private $pink;
    private $blue;
    private $orange;
    private $green_text;
    private $pink_text;
    private $blue_text;
    private $orange_text;
    /**
     * @var false|string
     */
    private $currect_date;
}
