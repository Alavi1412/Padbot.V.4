<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 8:54 AM
 */
function getEmailStartEntrance()            //this function get user's email and add it to database and ask user if he has a master key or want to be asked as entrance
{
    global $user_id;
    global $db;
    global $text;
    global $locale;
    if( recognize($text) == 0){
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "لطفا ایمیل معتبر وارد کنید."]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Please Enter a valid email."]);
    }elseif ( recognize($text) == 1)
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET email = \"{$text}\", current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
        $text = "asK_m3_Qu3sT1an";
        entrance();
        /*if ($locale == "farsi") {
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" =>"از چه راهی میخوای وارد مسابقه شی؟", "reply_markup" => json_encode([
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
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" =>"How do you want to enter the challenge?", "reply_markup" => json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "I have a MASTER KEY", "callback_data" => "Ent3R_V1a_Ma3t3R_k3Y"]
                    ],
                    [
                        ["text" => "Ask me question", "callback_data" => "asK_m3_Qu3sT1an"]
                    ]
                ]
            ])]);
        }*/
    }
}