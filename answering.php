<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 4:01 PM
 */
function answering()                    //after answering this function will handle every thing.
{
    global $user_id;
    global $text;
    global $db;
    global $locale;
    global $message_id;
    if ($text == "3nD_iT")
    {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $qString = $row['question_string'];
        $qString[$row['question_number'] - 1] = "1";
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'question_menu', current_content = NULL , question_number = 0 , question_string = \"{$qString}\" WHERE user_id = {$user_id}");
        if ($row['team_master_key'])
            $team = $row['team_master_key'];
        else
            $team = 0;
        mysqli_query($db, "INSERT INTO padporsc_bot4.user{$user_id} (content, question_number, group_of_answer_master_key) VALUES (\"{$row['current_content']}\", {$row['question_number']}, {$team})");
        $rand = rand(0, 999999999999999);
        mail("postmaster@discourse.padpors.com", "this is question {$row['question_number']} {$user_id} {$rand}", $row['current_content'], "From: {$row['email']}");
        question_menu();
//        if ($locale == "farsi")
//            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "ممنون ازین که به این سوال پاسخ دادی.", "reply_markup" => json_encode([
//                "inline_keyboard" => [
//                    [
//                        ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
//                    ]
//                ]
//            ])]);
//        elseif ($locale == "english")
//            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Thanks for your answer", "reply_markup" => json_encode([
//                "inline_keyboard" => [
//                    [
//                        ["text" => "Continue", "callback_data" => "C0nT1nu3"]
//                    ]
//                ]
//            ])]);

    }
    elseif(recognize($text) == 0)
    {
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "هروقت پاسخت تکمیل شد بزن رو دکمه ی زیر.",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تمومش کن","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "If you have finished writing tap on the button below.Or you can continue writing without any problem",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "End it","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
    }
    elseif (recognize($text) == 1){

        $result=mysqli_query($db,"SELECT * FROM padporsc_bot4.users WHERE user_id={$user_id}");
        $row = mysqli_fetch_array($result);
        $content = $row['current_content'];
        $content .= " ";
        $content .= $text;
        mysqli_query($db,"UPDATE padporsc_bot4.users set  current_content = \"{$content}\" WHERE user_id={$user_id}");
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "هر وقت پاسخت تکمیل شد بزن رو دکمه ی زیر.",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تمومش کن","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "If you have finished writing tap on the button below.Or you can continue writing without any problem",
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