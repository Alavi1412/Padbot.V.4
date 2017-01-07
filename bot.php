<?php
/**
 * Created by PhpStorm.
 * Author: SMHassanAlavi
 * Date: 12/29/16
 * Time: 4:17 PM
 */
function makeCurl($method,$datas=[])    //macke and receive request to bot
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot274622754:AAFb0_FK4ShDjOjy1KpnbRf-U9-GVBzNpVk/{$method}");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($datas));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec ($ch);
    curl_close ($ch);
    return $server_output;
}

$db;                            //global database connect
$level;                         //user level
$user_id;                       //user unique user id.find in main function in each update
$text;                          //text that user sent.sometimes the callback data of inline keyboard
$username;                      //user telegram username
$message_id;                    //message_id of button that user pressed
$question;                      //the question should be asked from user
$user_firstname;                //user first name;
$locale;                        //user language
$last_updated_id = 0;           //should be removed
function mysqlConnect($i)       //function for connection and disconnection to database
{
    global $db;
    if ($i == 1)
        $db=mysqli_connect("localhost","root","root","bot");
    elseif ($i == 0)
        echo mysqli_close($db);
}

function levelFinder()          //find user's level and return it
{
    global $user_id;
    global $level;
    global $db;
    mysqlConnect(1);
    $b = 0;
    $result = mysqli_query($db,"SELECT * FROM bot.users WHERE user_id={$user_id}");
    while($row = mysqli_fetch_array($result))
    {
        if($row['current_level'])
        {
            $level = $row['current_level'];
            $locale = $row['locale'];
            $b = 1;
        }
    }
    if($b == 0)
        $level = "Begin";
    mysqlConnect(0);
}

function intro()                //send User the introduction and add him to database and create his own database
{
    global $user_id;
    global $db;
    global $user_firstname;
    global $username;
    mysqlConnect(1);
    makeCurl("sendPhoto",["chat_id" => $user_id, "photo" => "https://meta-s3-cdn.global.ssl.fastly.net/original/3X/c/a/ca61ea9e8fa9b8046cd59c524f7e0f76c912211f.png", "reply_markup" => json_encode([
        "inline_keyboard" =>[
            [
                ["text" => "فارسی" , "callback_data" => "P3R$1an"],["text" => "English", "callback_data" => "3ngL1$1h"]
            ]
        ]
    ])]);
    mysqli_query($db, "INSERT INTO bot.users (user_id, user_first_name, current_level, username) VALUES ({$user_id}, \"{$user_firstname}\", 'intro_showed', \"{$username}\")");
    mysqlConnect(0);
}

function firstStep()                //the first step for user after click on locale. table for each user created here
{
    //TODO this function should be completed
    global $user_id;
    global $text;
    global $db;
    mysqlConnect(1);
    if( $text == "P3R$1an")
    {
        mysqli_query($db, "UPDATE bot.users SET loacle = 'farsi', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "به پادپرس بات خوش آمدید. ایمیل خودتون رو وارد کنید و اگر یوزر پادپرس هستید ایمیل پادپرست رو وارد کن."]);
    }elseif ($text == "3ngL1$1h")
    {
        mysqli_query($db, "UPDATE bot.users SET loacle = 'english', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Enter your email:"]);
    }
    mysqli_query($db, "CREATE TABLE bot.user{$user_id} ( content LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL , score INT NOT NULL DEFAULT '0' , question_number INT NOT NULL , group_of_answer_master_key INT NOT NULL , answer_id INT NOT NULL )");
    mysqlConnect(0);
}

function main()
{
    global $level;
    global $user_id;
    global $text;
    global $username;
    global $user_firstname;
    global $message_id;
    global $last_updated_id;
    global $db;
//    $update = json_decode(file_get_contents("php://input"));          //should not be comment
    $updates = json_decode(makeCurl("getUpdates",["offset"=>($last_updated_id+1)]));        //should be removed
    if($updates->ok == true && count($updates->result) > 0) {               //should be removed
        foreach ($updates->result as $update) {                             //should be removed
            if ($update->callback_query) {
                makeCurl("answerCallbackQuery", ["callback_query_id" => $update->callback_query->id]);
                $text = $update->callback_query->data;
                $user_id = $update->callback_query->from->id;
                $user_firstname = $update->callback_query->from->first_name;
                $username = $update->callback_query->from->username;
                $message_id = $update->callback_query->message->message_id;
            } else {
                $text = $update->message->text;
                $user_id = $update->message->chat->id;
                $username = $update->message->from->username;
                $user_firstname = $update->message->from->first_name;
            }
            levelFinder();
            if ($level == "Begin")
                intro();
            elseif ($level == "intro_showed")
                firstStep();
            $last_updated_id = $update->update_id;              //should be removed
        }           //should be removed
    }               //should be removed
}
while(1) {
    main();
}
