<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:36 PM
 */
function questionHandling()                     //handle requests from question menu button
{
    global $user_id;
    global $text;
    global $db;
    if($text == "us3R_m3nU")
    {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);

        if($row['question_string'] == "111111")
            userMenu(1,1);
        else
            userMenu(1,0);
    }
    elseif ($text == "f1rst_Qu3stion" || $text == "sec0nd_Qu3stion" || $text == "th1rd_Qu3stion" || $text == "f0rth_Qu3stion" || $text == "f1fth_Qu3stion" || $text == "s1x_Qu3stion")
    {
        $b = 2;
        if($text == "f1rst_Qu3stion")
            $b = 1;
        elseif ($text == "sec0nd_Qu3stion")
            $b = 2;
        elseif ($text == "th1rd_Qu3stion")
            $b = 3;
        elseif ($text == "f0rth_Qu3stion")
            $b = 4;
        elseif ($text == "f1fth_Qu3stion")
            $b = 5;
        elseif ($text == "s1x_Qu3stion")
            $b = 6;

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'asking_question', question_number = {$b} WHERE user_id = {$user_id}");

        askQuestion($b);
    }
    elseif ($text == "WARMin5UPPASDP")
    {
        warm(0);
    }
}