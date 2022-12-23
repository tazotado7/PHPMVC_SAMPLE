<?php

namespace models;
 
class Main
{
    public function __construct()
    {
    }

    public function get_user_from_DB($user_id)
    {
        $DB = [
            0 => 'Guest',
            1 => 'User'
        ];

        if (!isset($DB[$user_id])) return 'Guest';

        return $DB[$user_id];
    }
}
