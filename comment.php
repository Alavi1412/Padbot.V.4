<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/23/17
 * Time: 9:39 AM
 */
function comment($i)
{
    global $db;
    global $user_id;
    global $message_id;
    global $locale;
    global $text;
    if ($i == 0)
    {
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'commenting' WHERE user_id = {$user_id}");
        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "نظرت رو برای ما بنویس"]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Enter your comment"]);
    }
    elseif ($i == 1)
    {
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
        mail("salam.padpors@gmail.com", "Comment padpod", $text);
        mail("alavi_h2007@yahoo.com", "Comment padpod", $text);
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "از نظرت متشکریم", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Thank you for commenting", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);

    }
}