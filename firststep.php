<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 8:53 AM
 */
function firstStep()                //the first step for user after click on locale. table for each user created here
{
    global $user_id;
    global $text;
    global $db;

    if( $text == "P3R$1an")
    {
        mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'farsi', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "به پادبات خوش اومدی، بات رسمی پادپُرس. ایمیلت رو وارد کن و اگر کاربر پادپُرس هستی، ایمیل پادپُرست رو وارد کن."]);
    }elseif ($text == "3ngL1$1h")
    {
        mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'english', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Enter your email; if you are Padpors user, provide your Padpors email."]);
    }
    mysqli_query($db, "CREATE TABLE padporsc_bot4.user{$user_id} ( content LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL , score INT NULL DEFAULT '0' , question_number INT NULL , group_of_answer_master_key INT NULL , answer_id INT NULL )");

}