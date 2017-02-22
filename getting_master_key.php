<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:31 PM
 */
function gettingMasterKey()                 //this function get and validate the input master key for entrance
{
    global $db;
    global $user_id;
    global $text;
    global $message_id;
    global $locale;
    if ($text == "Ca_nC_31")
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");

        if ($locale == "farsi") {
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" =>"از چه راهی میخواهی وارد مسابقه بشی؟", "reply_markup" => json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "شاه کلید دارم", "callback_data" => "Ent3R_V1a_Ma3t3R_k3Y"]
                    ],
                    [
                        ["text" => "ازم سوال بپرس", "callback_data" => "asK_m3_Qu3sT1an"]
                    ]
                ]
            ])]);
        }
        elseif ($locale == "english"){
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" =>"How do you want to enter?", "reply_markup" => json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "I have a MASTER KEY", "callback_data" => "Ent3R_V1a_Ma3t3R_k3Y"]
                    ],
                    [
                        ["text" => "Ask me question", "callback_data" => "asK_m3_Qu3sT1an"]
                    ]
                ]
            ])]);
        }
    }
    else{

        $b = 0;
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE master_key = {$text}");
        while ($row = mysqli_fetch_array($result)) {
            $b = 1;
            $teams = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$text}");
        }
        $a = 0;
        while ($row2 = mysqli_fetch_array($teams))
        {
            $a++;
        }
        if ($a == 5 || $b == 0) {
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
            if ($a == 5){
                if ($locale == "farsi")
                {
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "ظرفیت تیم تکمیل است.", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                }elseif ($locale == "english")
                {
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Team is full", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                }
            }
            elseif ($b == 0)
            {
                if ($locale == "farsi")
                {
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "شاه کلید شما پیدا نشد.", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                }elseif ($locale == "english")
                {
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "MASTER KEY Not Found", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                }
            }
        }
        elseif ($b == 1){

            $ans = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$text}");
            $z = 0;
            while ($g = mysqli_fetch_array($ans))
                $z++;
            $z++;
            mysqli_query($db, "INSERT INTO padporsc_bot4.team{$text} (user_id, level) VALUE ({$user_id}, {$z})");
            if ($z == 5)
                mysqli_query($db,"UPDATE padporsc_bot4.teams SET open = 0 WHERE master_key = {$text} ");
            mysqli_query($db, "UPDATE padporsc_bot4.users SET team_master_key = {$text}, current_level = 'question_menu' WHERE user_id = {$user_id}");
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE master_key = {$text}");
            while ($row = mysqli_fetch_array($result)) {
                $teamName = $row['name'];
            }

            if($locale == "farsi")
                makeCurl("sendMessage", ["text" => " شما به تیم {$teamName} اضافه شدید", "chat_id" => $user_id, "reply_markup" =>
                    json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            elseif($locale == "english")
                makeCurl("sendMessage", ["text" => "You have been added to team {$teamName}", "chat_id" => $user_id, "reply_markup" =>
                    json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
        }
    }
}
