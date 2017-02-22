<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:56 PM
 */
function info($i)                       //show and handle my info button
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if($i == 0)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'watching_info' WHERE user_id = {$user_id}");
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");

        $row = mysqli_fetch_array($result);
        if ($row['logged_in'] == 0) {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تغییر زبان", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "ورود به پادپُرس", "callback_data" => "my_Padp0rS"]
                        ],
                        [
                            ["text" => "ایمیل من",  "callback_data" => "my_3ma1L"]
                        ],
                        [
                            ["text" => "بازگشت", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Change Language", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "Log into PadPors", "callback_data" => "my_Padp0rS"]
                        ],
                        [
                            ["text" => "My Email",  "callback_data" => "my_3ma1L"]
                        ],
                        [
                            ["text" => "Return", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
        }
        elseif ($row['logged_in'] == 1){
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تغییر زبان", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "بازگشت", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Change Language", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "Return", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
        }
    }
    elseif ($i == 1)
    {
        if ($text == "R3tuRn")
        {

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);

            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        elseif ($text == "ChanG3_lanGuag3")
            changeLang(0);
        elseif ($text == "my_3ma1L")
            myEmail(0);
        elseif ($text == "my_Padp0rS")
            account(0);

    }
}