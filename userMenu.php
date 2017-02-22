<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:49 PM
 */
function userMenu($a,$f)                    //show user the menu and handle its requests
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if($a == 1)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");

        if($locale == "farsi" && $f == 0)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "سوالات", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "تیم من", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "صفحه شخصی", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "پادپُرس", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "امتیاز من", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
        elseif($locale == "farsi" && $f == 1)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "سوالات", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "تیم من", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "طرح سوال", "callback_data" => "Cr3at3_Qu3sTi0n"]
                    ],
                    [
                        ["text" => "صفحه شخصی", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "پادپُرس", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "امتیاز من", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
        elseif($locale == "english" && $f == 0)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Questions", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "My Team", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "My Profile", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "PADPORS", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "My Score", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
        elseif($locale == "english" && $f == 1)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Questions", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "My Team", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "Create Question", "callback_data" => "Cr3at3_Qu3sTi0n"]
                    ],
                    [
                        ["text" => "My Profile", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "PADPORS", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "My Score", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
    }
    elseif ($a == 2)
    {
        if ($text == "g0_back_to_qu3stion")
            question_menu();
        elseif ($text == "My_Inf0")
            info(0);
        elseif ($text == "My_sc0R3")
            score();
        elseif ($text == "mY_t3aM")
            team(0);
        elseif ($text == "Cr3at3_Qu3sTi0n")
            makeQuestion(0);
    }
}
