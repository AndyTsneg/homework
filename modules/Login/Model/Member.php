<?php

namespace Login\Model;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * @var array|string
     */
    private $user_id;
    /**
     * @var array|string
     */
    private $name;
    /**
     * @var array|string
     */
    private $password;
    /**
     * @var array|string
     */
    private $social_type;
}
