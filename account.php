<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:57 PM
 */
function account($i)                    //sync user's padpors account with padbot and make account
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if ($i == 0)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'how_Enter' WHERE user_id = {$user_id}");

        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "یکی از گزینه هارو انتخاب کن.", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "قبلا کاربر پادپرس بودم", "callback_data" => "r3c3nT_us3R"]
                    ],
                    [
                        ["text" => "میخوام کاربر پادپرس بشم", "callback_data" => "MAK3_ACc0uNt"]
                    ],
                    [
                        ["text" => "بازگشت", "callback_data" => "R3Turn"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Recent user", "callback_data" => "r3c3nT_us3R"]
                    ],
                    [
                        ["text" => "Make user", "callback_data" => "MAK3_ACc0uNt"]
                    ],
                    [
                        ["text" => "return", "callback_data" => "R3Turn"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {
        if ($text == "R3Turn")
            info(0);
        elseif ($text == "r3c3nT_us3R")
        {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "نام کاربری خود را وارد کنید:", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" =>"بازگشت", "callback_data" => "R3Turn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Enter Your Username:", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" =>"Return", "callback_data" => "R3Turn"]
                        ]
                    ]
                ])]);


            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'current_user' WHERE user_id = {$user_id}");

        }
        elseif ($text == "MAK3_ACc0uNt")
        {

            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'getting_username_for_sign_up' WHERE user_id = {$user_id}");
            $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");

            $row3 = mysqli_fetch_array($result3);
            if ($row3['trying_log'] == 0) {
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "نام کاربری دلخواهت رو وارد کن تا ما برات اکانت بسازیم", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "انصراف", "callback_data" => "CAnc3l"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Enter Your Username", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Cancel", "callback_data" => "CAnc3l"]
                            ]
                        ]
                    ])]);
            }
            elseif ($row3['trying_log'] == 1){

                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");

                if ($locale == "farsi")
                    makeCurl("editMessageText", ["message_id" =>$message_id, "chat_id" => $user_id, "text" => "قبلا ثبت نام کردی. یا وارد بات شو و یا اگه اشتباه کردی میتونی بری توی سایت و اونجا حساب کاربری بسازی", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "you can't make account any more here", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
        }
    }
    elseif ($i == 2)
    {
        if ($text == "R3Turn")
            info(0);
        elseif (recognize($text) == 0) {
            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "لطفا نام کاربری معتبر وارد کنید", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Please Enter a valid Username", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
        else
        {

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);

            $email = $row['email'];
            $xml = file_get_contents("https://padpors.com/admin/users/list/active.json?filter={$email}&show_emails=false&_=1484208836960&api_key=61bb4efa6432469bf8b1e9ed1dc1f507558cffd01653ea3fc56f0ff09b13ff96&api_username=padpors");
            $answer = json_decode($xml);
            if($answer[0] -> username)
            {
                $username = $answer[0] -> username;
                if(strcasecmp($username ,$text) == 0)
                {

                    $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
                    $row2 = mysqli_fetch_array($result2);
                    $score = $row2['score'];
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu',score = $score, padpors_username = \"{$username}\", logged_in = 1 WHERE user_id = {$user_id}");

                    if ($locale == "farsi")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "تبریک. شما وارد شدید و 5 امتیاز هدیه گرفتید.", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                    elseif ($locale == "english")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Congratulation You Logged in successfully. You have reached 5 score as gift", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                }
                else
                    if ($locale == "farsi")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "نام کاربری با ایمیل شما مطابق ندارد.", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                    elseif ($locale == "english")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Please Enter a valid Username", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
            }
            else {
                if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "نام کاربری با ایمیل شما مطابق ندارد.", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Please Enter a valid Username", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
        }
    }
    elseif ($i == 3)
    {
        if ($text == "CAnc3l")
            info(0);
        else
        {

            mysqli_query($db, "UPDATE padporsc_bot4.users SET padpors_username = \"{$text}\", current_level = 'getting_password' WHERE user_id = {$user_id}");

            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "حالا رمزت رو وارد کن."]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Enter your password now"]);
        }
    }
    elseif ($i == 4)
    {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");

        $row = mysqli_fetch_array($result);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://padpors.com/users/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(["username" => $row['padpors_username'] , "password" => $text, "email" => $row['email'], "api_key" => "61bb4efa6432469bf8b1e9ed1dc1f507558cffd01653ea3fc56f0ff09b13ff96", "api_username" => "padpors", "acive" => false]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        echo $server_output;
        $ans2 = json_decode($server_output);
        echo $ans2 -> message;
        if ($ans2 -> success)
        {

            mysqli_query($db, "UPDATE padporsc_bot4.users SET trying_log = 1, current_level = 'user_menu' WHERE user_id = {$user_id}");

            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "توی سایت ثبت نام شدی حالا فقط کافیه بری توی ایمیلت و ثبت نامت رو تایید کنی.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "SignUp complete. You need to verify your account in your email.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }else
        {

            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu', padpors_username = NULL WHERE user_id = {$user_id}");

            if ($locale == "farsi") {
                makeCurl("sendMessage", ["text" => "ثبت نام شما با خطای زیر مواجه شد.", "chat_id" => $user_id]);
                makeCurl("sendMessage", ["text" => $ans2->message, "chat_id" => $user_id]);
                makeCurl("sendMessage", ["text" => "برای ادامه کلیک کنید.", "chat_id" => $user_id, "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" =>"C0nT1nu3"]
                        ]
                    ]
                ])]);
            }
            elseif ($locale == "english"){
                makeCurl("sendMessage", ["text" => "Solve following errors", "chat_id" => $user_id]);
                makeCurl("sendMessage", ["text" => $ans2->message, "chat_id" => $user_id]);
                makeCurl("sendMessage", ["text" => "Click for continue", "chat_id" => $user_id, "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" =>"C0nT1nu3"]
                        ]
                    ]
                ])]);
            }
        }
    }
}