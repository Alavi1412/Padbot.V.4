<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 8:50 AM
 */
function intro()                //send User the introduction and add him to database and create his own database
{
    global $user_id;
    global $db;
    global $user_firstname;
    global $username;
    $data = mysqli_connect("localhost","root","root","padporsc_data");
    $result = mysqli_query($data, "SELECT * FROM padporsc_data.image");
    $url = mysqli_fetch_array($result);
    makeCurl("sendPhoto",["chat_id" => $user_id, "caption" => '🌘',"photo" => $url['url'], "reply_markup" => json_encode([
        "inline_keyboard" =>[
            [
                ["text" => "English", "callback_data" => "3ngL1$1h"],["text" => "فارسی" , "callback_data" => "P3R$1an"]
            ]
        ]
    ])]);
    mysqli_query($db, "INSERT INTO padporsc_bot4.users (user_id, user_first_name, current_level, username) VALUES ({$user_id}, \"{$user_firstname}\", 'intro_showed', \"{$username}\")");

}