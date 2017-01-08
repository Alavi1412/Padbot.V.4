<?php
/**
 * Created by PhpStorm.
 * Author: SMHassanAlavi
 * Date: 12/29/16
 * Time: 4:17 PM
 */
function makeCurl($method,$datas=[])    //make and receive requests to bot
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

function recognize($note)         //recognize if user press the inline button or enter valid data
{
    if ($note == "P3R$1an")
        return 0;
    elseif ($note == "3ngL1$1h")
        return 0;
    elseif ($note == "/start")
        return 0;
    elseif ($note == "Ent3R_V1a_Ma3t3R_k3Y")
        return 0;
    elseif ($note == "asK_m3_Qu3sT1an")
        return 0;
    elseif ($note == "Ca_nC_31")
        return 0;
    else
        return 1;
}

function levelFinder()          //find user's level and return it
{
    global $user_id;
    global $level;
    global $db;
    global $locale;
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
    global $user_id;
    global $text;
    global $db;
    mysqlConnect(1);
    if( $text == "P3R$1an")
    {
        mysqli_query($db, "UPDATE bot.users SET locale = 'farsi', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "به پادپرس بات خوش آمدید. ایمیل خودتون رو وارد کنید و اگر یوزر پادپرس هستید ایمیل پادپرست رو وارد کن."]);
    }elseif ($text == "3ngL1$1h")
    {
        mysqli_query($db, "UPDATE bot.users SET locale = 'english', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Enter your email:"]);
    }
    mysqli_query($db, "CREATE TABLE bot.user{$user_id} ( content LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL , score INT NOT NULL DEFAULT '0' , question_number INT NOT NULL , group_of_answer_master_key INT NOT NULL , answer_id INT NOT NULL )");
    mysqlConnect(0);
}

function getEmailStartEntrance()            //this function get user's email and add it to database and ask user if he has a master key or want to be asked as entrance
{
    global $user_id;
    global $db;
    global $text;
    global $locale;
    if( recognize($text == 0)){
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "لطفا ایمیل معتبر وارد کنید."]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Please Enter a valid email."]);
    }elseif ( recognize($text) == 1)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE bot.users SET email = \"{$text}\", current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        if ($locale == "farsi") {
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" =>"از چه راهی میخواهی وارد مسابقه بشی؟", "reply_markup" => json_encode([
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
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" =>"How do you want to enter?", "reply_markup" => json_encode([
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
}

function entrance()                     //handle the menu in entrance and the user's pressed button
{
    global $user_id;
    global $text;
    global $db;
    global $locale;
    global $message_id;
    if($text == "Ent3R_V1a_Ma3t3R_k3Y")
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE bot.users SET current_level = 'waiting_for_master_key' WHERE user_id = {$user_id}");
        mysqlConnect(0);
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
        mysqlConnect(1);
        mysqli_query($db, "UPDATE bot.users SET current_level = 'answering_the_entrance_question' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        if ($locale == "farsi")
        {
            echo makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "سوال ورودی", "message_id" => $message_id]);
        }
        elseif ($locale == "english")
        {
            echo makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "Entrance question", "message_id" => $message_id]);
        }
    }
}

function gettingMasterKey()                 //this function get and validate the input master key for entrance
{
    global $db;
    global $user_id;
    global $text;
    global $message_id;
    global $locale;
    if ($text == "Ca_nC_31")
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE bot.users SET current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
        mysqlConnect(0);
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
        mysqlConnect(1);
        $b = 0;
        $result = mysqli_query($db, "SELECT * FROM bot.teams WHERE master_key = {$text}");
        while ($row = mysqli_fetch_array($result)) {
            $b = 1;
        }
        mysqlConnect(0);
        if ($b == 0) {
            mysqlConnect(1);
            mysqli_query($db, "UPDATE bot.users SET current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
            mysqlConnect(0);
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
        elseif ($b == 1){
            mysqlConnect(1);
            mysqli_query($db, "INSERT INTO bot.team{$text} (user_id, group_level) VALUE ({$user_id}, 1)");
            mysqli_query($db, "UPDATE bot.users SET team_master_key = {$text}, current_level = 'question_menu' WHERE user_id = {$user_id}");
            $result = mysqli_query($db, "SELECT * FROM bot.teams WHERE master_key = {$text}");
            while ($row = mysqli_fetch_array($result)) {
                $teamName = $row['name'];
            }
            mysqlConnect(0);
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

function answeringEntranceQuestion()               //this function handle the answer for entrance question
{
    global $user_id;
    global $db;
    global $locale;
    mysqlConnect(1);
    mysqli_query($db, "UPDATE bot.users SET current_level = 'question_menu' WHERE user_id = {$user_id}");
    mysqlConnect(0);
    if ($locale == "farsi")
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "تبریک شما به مسابقه وارد شدید", "reply_markup" =>
        json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                ]
            ]
        ])]);
    elseif ($locale == "english")
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Congratualtions, You have Enterd the Game", "reply_markup" =>
            json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
}

function continueHandler()                          //handle the continue button related to user's level
{
    global $user_id;
    global $message_id;
    global $level;
    global $locale;
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
        makeCurl("SendMessage", ["chat_id" => $user_id, "text" => "To be continued"]);
    }
}

function question_menu()                    //show user the questions
{
    //TODO this function should be completed
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
            elseif ($text == "C0nT1nu3")
                continueHandler();
            elseif ($level == "intro_showed")
                firstStep();
            elseif ($level == "firstStep")
                getEmailStartEntrance();
            elseif ($level == "has_email_go_to_entrance")
                entrance();
            elseif ($level == "answering_the_entrance_question")
                answeringEntranceQuestion();
            elseif ($level == "waiting_for_master_key")
                gettingMasterKey();
            $last_updated_id = $update->update_id;              //should be removed
        }           //should be removed
    }               //should be removed
}
while(1) {
    main();
}
