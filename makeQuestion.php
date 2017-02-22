<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:53 PM
 */
function makeQuestion($i)                       //make question and change level to making_question
{
    global $user_id;
    global $message_id;
    global $locale;
    global $db;
    global $text;
    if ($i == 0)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'making_question' WHERE user_id = {$user_id}");

        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "سوال خودت رو مطرح کن", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "انصراف", "callback_data" => "Ca_nC_31"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Type your OWN question", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Cancel", "callback_data" => "Ca_nC_31"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {
        if ($text == "Ca_nC_31")
            userMenu(1,1);
        else
        {
            if (recognize($text) == 0) {
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "سوال خودت رو مطرح کن", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "انصراف", "callback_data" => "Ca_nC_31"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Type your OWN question", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Cancel", "callback_data" => "Ca_nC_31"]
                            ]
                        ]
                    ])]);
            }
            elseif (recognize($text) == 1)
            {

                $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
                $row = mysqli_fetch_array($result);
                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                mail("postmaster@discourse.padpors.com", "User's Question", $text, "From: {$row['email']}");

                if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "سوالت ثبت شد", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Your question saved", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
        }
    }
}