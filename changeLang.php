<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:59 PM
 */
function changeLang($i)                 //show and change the language for user
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if ($i == 0)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'watching_change_language' WHERE user_id = {$user_id}");

        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن.", "reply_markup" => json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "فارسی", "callback_data" => "chang3_T0_p3Rs1an"], ["text" => "انگلیسی", "callback_data" => "chang3_T0_3nGl1sH"]
                    ],
                    [
                        ["text" => " بازگشت", "callback_data" => "R3tuRn"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "Farsi", "callback_data" => "chang3_T0_p3Rs1an"], ["text" => "English", "callback_data" => "chang3_T0_3nGl1sH"]
                    ],
                    [
                        ["text" => "Return", "callback_data" => "R3tuRn"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);

        if($row['question_string'] == "111111")
            $b = 1;
        else
            $b = 0;
        if ($text == "R3tuRn")
            userMenu(1, $b);
        elseif ($text == "chang3_T0_p3Rs1an")
        {
            $locale = "farsi";

            mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'farsi' WHERE user_id = {$user_id}");

            userMenu(1, $b);
        }
        elseif ($text == "chang3_T0_3nGl1sH")
        {
            $locale = "english";

            mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'english' WHERE user_id = {$user_id}");

            userMenu(1, $b);
        }
    }

}