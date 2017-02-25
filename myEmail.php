<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:58 PM
 */
function myEmail($i)                            //used to show user his email and change his email
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if($i == 0)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'changing_email' WHERE user_id = {$user_id}");
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");

        $row = mysqli_fetch_array($result);
        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "ایمیل شما: {$row['email']}
            برای تغییر ایمیل جدید را وارد کنید", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "بازگشت", "callback_data" => "R3Turn"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Your Email: {$row['email']}
            Enter New Email If you want to change it", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Return", "callback_data" => "R3Turn"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {
        if ($text == "R3Turn")
            info(0);
        else
        {

//            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'watching_info', email = \"{$text}\" WHERE user_id = {$user_id}");
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu', email = \"{$text}\" WHERE user_id = {$user_id}");

            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "ایمیل شما با موفقیت تغییر یافت", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Your Email Changes successfully", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
    }
}
