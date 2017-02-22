<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:33 PM
 */
function answeringEntranceQuestion()               //this function handle the answer for entrance question
{
    global $user_id;
    global $db;
    global $locale;
    global $text;

    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'question_menu' WHERE user_id = {$user_id}");
    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");

    $row = mysqli_fetch_array($result);
    mail("postmaster@discourse.padpors.com", "Entrance Question for Bot", $text, "From: {$row['email']}");
    if ($locale == "farsi")
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "تبریک میگم! وارد مسابقه شدی.", "reply_markup" =>
            json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "بزن بریم!", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
    elseif ($locale == "english")
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Congratulations, You have entered the Game", "reply_markup" =>
            json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Let's GO", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
}