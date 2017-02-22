<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 4:00 PM
 */
function startAsking()                      //handle the first request to bot after showing the question * level = answering
{
    global $db;
    global $user_id;
    global $text;
    global $locale;
    if($text == "Ca_nC_31")
        question_menu();
    else
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'answering', current_content = \"{$text}\" WHERE user_id = {$user_id}");

        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "اگه پاسخت تموم شد، بزن روی دکمه ی زیر.وگرنه هنوز میتونی به نوشتنت ادامه بدی و پاسخت رو تکمیل کنی.",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تمومش کن","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "I you have finished writing tap on the button below.Or you can continue writing without any problem",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "End it","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
    }
}