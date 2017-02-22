<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 4:02 PM
 */
function send()                         //for sending message to a group
{
    global $user_id;
    global $db;
    global $text;
    global $user_firstname;
    global $locale;
    if ($text == "CancELL")
        haveTeam(0);
    else
    {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
        while ($row = mysqli_fetch_array($result))
        {
            if ($row['user_id'] != $user_id)
            {
                if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $row['user_id'], "text" => "{$user_firstname}✉️:{$text}"]);
                elseif ($loacle == "english")
                    makeCurl("sendMessage", ["chat_id" => $row['user_id'], "text" => "{$user_firstname}✉️:{$text}"]);
            }
        }
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'team_menu' WHERE  user_id = {$user_id}");
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "پیامت ارسال شد.", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Your Message Sent", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);

    }
}