<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:31 PM
 */
function entrance()                     //handle the menu in entrance and the user's pressed button
{
    global $user_id;
    global $text;
    global $db;
    global $locale;
    global $message_id;
    if($text == "Ent3R_V1a_Ma3t3R_k3Y")
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'waiting_for_master_key' WHERE user_id = {$user_id}");

        if ($locale == "farsi")
        {
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "شاه کلیدت رو وارد کن", "reply_markup"=>
                json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "انصراف", "callback_data" => "Ca_nC_31"]
                        ]
                    ]
                ])
            ]);
        }
        elseif ($locale == "english")
        {
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Enter your MASTER KEY", "reply_markup"=>
                json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Cancel", "callback_data" => "Ca_nC_31"]
                        ]
                    ]
                ])
            ]);
        }
    }elseif ($text == "asK_m3_Qu3sT1an")
    {

        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'answering_the_entrance_question' WHERE user_id = {$user_id}");

        if ($locale == "farsi")
        {
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "چرا میخوای تو چالش مقابله با آلودگی هوا مشارکت کنی؟", "message_id" => $message_id]);
        }
        elseif ($locale == "english")
        {
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "Why do you want to participate in \"air pollution\" challenge?", "message_id" => $message_id]);
        }
    }
}
