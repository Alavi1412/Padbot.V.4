<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 8:48 AM
 */
function levelFinder()          //find user's level and return it
{
    global $user_id;
    global $level;
    global $db;
    global $locale;

    $b = 0;
    $result = mysqli_query($db,"SELECT * FROM padporsc_bot4.users WHERE user_id={$user_id}");
    while($row = mysqli_fetch_array($result))
    {
        if($row['current_level'])
        {
            $level = $row['current_level'];
            $locale = $row['locale'];
            $b = 1;
        }
    }
    if($b == 0)
        $level = "Begin";

}