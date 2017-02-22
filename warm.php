<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:37 PM
 */
function warm($i)                   //used for warming up the user's mind
{
    global $user_id;
    global $text;
    global $db;
    global $message_id;
    global $locale;

    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");

    $row = mysqli_fetch_array($result);
    $question = warmQuestion($row['warm']);
    if ($i == 0)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'warming_up' WHERE user_id = {$user_id}");
        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $question, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "انصراف", "callback_data" => "CanCE33LLll"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $question, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Cancel", "callback_data" => "CanCE33LLll"]
                    ]
                ]
            ])]);
        $num = $row['warm'];
        $num++;
        mysqli_query($db, "UPDATE padporsc_bot4.users SET warm = {$num} WHERE user_id = {$user_id}");

    }
    elseif ($i == 1)
    {
        if ($text == "CanCE33LLll")
        {
            mysqli_query($db, "UPDATE padporsc_bot4.users SET warm = 1, chosen = NULL , word = NULL , current_level = 'user_menu' WHERE  user_id = {$user_id}");
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        else
        {
            if ($row['warm'] == 10)
            {

                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu', warm = 1 WHERE user_id = {$user_id}");

                if ($text == "0")
                {
                    if ($locale == "farsi")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "به نظر نمیاد اینجا کاری از دستت بر بیاد", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                    elseif ($locale == "english")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "I think you can't do anything HERE", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                }
                else {
                    if ($locale == "farsi")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "امیدوارم ذهنت گرم شده باشه", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                    elseif ($locale == "english")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "I hope You mind is warmed UP", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                }
            }
            else
            {

                $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
                $row = mysqli_fetch_array($result);
                mail("padpors.innovation@gmail.com", $question, $text);
                if ($row['warm'] == 2)
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'warming_up', word = \"{$text}\" WHERE user_id = {$user_id}");
                if ($row['warm'] == 7)
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'warming_up', chosen = \"{$text}\" WHERE user_id = {$user_id}");
                $question = warmQuestion($row['warm']);
                if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => $question, "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "انصراف", "callback_data" => "CanCE33LLll"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => $question, "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Cancel", "callback_data" => "CanCE33LLll"]
                            ]
                        ]
                    ])]);
                $num = $row['warm'];
                $num++;
                mysqli_query($db, "UPDATE padporsc_bot4.users SET warm = {$num} WHERE user_id = {$user_id}");

            }
        }
    }

}