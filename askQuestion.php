<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:47 PM
 */
function askQuestion($b)                    //ask user the chosen question LEVEL = question_asked
{
    global $user_id;
    global $db;
    global $message_id;
    global $locale;
    $string = returnQuestion($b);
    if ($locale == "farsi")
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $string, "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => '💤 نمیدونم', "callback_data" => "Ca_nC_31"]
                ]
            ]
        ])]);
    elseif ($locale == "english")
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $string, "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "Cancel", "callback_data" => "Ca_nC_31"]
                ]
            ]
        ])]);

    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'question_asked' WHERE user_id = {$user_id}");

}