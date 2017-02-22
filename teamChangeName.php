<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:52 PM
 */
function changeName($i)                         //used for changing name of the team by admin
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if ($i == 0)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'changing_name_team' WHERE user_id = {$user_id}");

        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "اسم جدیدت رو برای تیم وارد کن.", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "انصراف", "callback_data" => "CaNec33L"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Enter you new name for team", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Cancel", "callback_data" => "CaNec33L"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {
        if ($text == "CaNec33L")
            haveTeam(0);
        else
        {

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqli_query($db, "UPDATE padporsc_bot4.teams SET name = \"{$text}\" WHERE master_key = {$row['team_master_key']}");
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'have_team_menu' WHERE user_id = {$user_id}");

            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "اسم تیمت عوض شد.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Team name changed.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
    }
}