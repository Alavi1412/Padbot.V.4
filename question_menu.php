<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:35 PM
 */
function question_menu()                    //show user the questions and user menu button
{
    global $user_id;
    global $message_id;
    global $db;
    global $locale;

    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
    $row = mysqli_fetch_array($result);
    $string = $row['question_string'];
    mysqli_query($db,"UPDATE padporsc_bot4.users SET current_level = 'question_showed' WHERE user_id = {$user_id}");

    $sign = array("◻️","◻️","◻️","◻️","◻️","◻️");
    for($i = 0 ; $i < 6 ; $i++)
    {
        if($string[$i] == "1")
            $sign[$i] = "☑️";
    }
    if ($locale == "farsi")
    {
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "یک گزینه رو انتخاب کن.", "reply_markup" =>
            json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "ذهنمو گرم کن...", "callback_data" => "WARMin5UPPASDP"]
                    ],
                    [
                        ["text" => "1{$sign[0]}", "callback_data" => "f1rst_Qu3stion"],["text" => "2{$sign[1]}", "callback_data" => "sec0nd_Qu3stion"],["text" => "3{$sign[2]}", "callback_data" => "th1rd_Qu3stion"]
                    ],
                    [
                        ["text" => "4{$sign[3]}", "callback_data" => "f0rth_Qu3stion"],["text" => "5{$sign[4]}", "callback_data" => "f1fth_Qu3stion"],["text" => "6{$sign[5]}", "callback_data" => "s1x_Qu3stion"]
                    ],
                    [
                        ["text" => "منوی کاربری", "callback_data" => "us3R_m3nU"]
                    ]
                ]
            ])]);
    }
    elseif ($locale == "english")
    {
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose one:", "reply_markup" =>
            json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "1{$sign[0]}", "callback_data" => "f1rst_Qu3stion"],["text" => "2{$sign[1]}", "callback_data" => "sec0nd_Qu3stion"],["text" => "3{$sign[2]}", "callback_data" => "th1rd_Qu3stion"]
                    ],
                    [
                        ["text" => "4{$sign[3]}", "callback_data" => "f0rth_Qu3stion"],["text" => "5{$sign[4]}", "callback_data" => "f1fth_Qu3stion"],["text" => "6{$sign[5]}", "callback_data" => "s1x_Qu3stion"]
                    ],
                    [
                        ["text" => "User Menu", "callback_data" => "us3R_m3nU"]
                    ]
                ]
            ])]);
    }
}