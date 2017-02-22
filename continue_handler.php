<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:34 PM
 */
function continueHandler()                          //handle the continue button related to user's level
{
    global $user_id;
    global $message_id;
    global $level;
    global $locale;
    global $db;
    if ($level == "has_email_go_to_entrance")
    {
        if ($locale == "farsi") {
            makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" =>"از چه راهی میخواهی وارد مسابقه بشی؟", "reply_markup" => json_encode([
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
            makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" =>"How do you want to enter?", "reply_markup" => json_encode([
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
    elseif ($level == "question_menu"){
        question_menu();
    }
    elseif ($level == "watching_info") {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);

        if($row['question_string'] == "111111")
            userMenu(1,1);
        else
            userMenu(1,0);
    }
    elseif ($level == "user_menu")
    {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);

        if($row['question_string'] == "111111")
            userMenu(1,1);
        else
            userMenu(1,0);
    }
    elseif ($level == "current_user")
        info(0);
    elseif ($level == "team_menu")
        team(0);
    elseif ($level == "have_team_menu")
        haveTeam(0);

}
