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
        $db=mysqli_connect("localhost","root","root","padporsc_bot4");
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
    elseif ($note == "f1rst_Qu3stion")
        return 0;
    elseif ($note == "sec0nd_Qu3stion")
        return 0;
    elseif ($note == "th1rd_Qu3stion")
        return 0;
    elseif ($note == "f0rth_Qu3stion")
        return 0;
    elseif ($note == "f1fth_Qu3stion")
        return 0;
    elseif ($note == "s1x_Qu3stion" || $note == "us3R_m3nU")
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
    $result = mysqli_query($db,"SELECT * FROM padporsc_bot4.users WHERE user_id={$user_id}");
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
    makeCurl("sendPhoto",["chat_id" => $user_id, "photo" => "https://padpors.com//uploads/default/original/2X/4/4af4b49dc716348d5f988e5664d97795dbc1e04f.png", "reply_markup" => json_encode([
        "inline_keyboard" =>[
            [
                ["text" => "English", "callback_data" => "3ngL1$1h"],["text" => "فارسی" , "callback_data" => "P3R$1an"]
            ]
        ]
    ])]);
    mysqli_query($db, "INSERT INTO padporsc_bot4.users (user_id, user_first_name, current_level, username) VALUES ({$user_id}, \"{$user_firstname}\", 'intro_showed', \"{$username}\")");
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
        mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'farsi', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "به پادبات خوش اومدی، بات رسمی پادپُرس. ایمیلت رو وارد کن و اگر کاربر پادپُرس هستی، ایمیل پادپُرست رو وارد کن."]);
    }elseif ($text == "3ngL1$1h")
    {
        mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'english', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Enter your email; if you are Padpors user, provide your Padpors email."]);
    }
    mysqli_query($db, "CREATE TABLE padporsc_bot4.user{$user_id} ( content LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL , score INT NULL DEFAULT '0' , question_number INT NULL , group_of_answer_master_key INT NULL , answer_id INT NULL )");
    mysqlConnect(0);
}

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
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET email = \"{$text}\", current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        if ($locale == "farsi") {
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
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'waiting_for_master_key' WHERE user_id = {$user_id}");
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
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'answering_the_entrance_question' WHERE user_id = {$user_id}");
        mysqlConnect(0);
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
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
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
        mysqlConnect(0);
        if ($b == 0 || $a == 0) {
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'has_email_go_to_entrance' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            if ($a == 0){
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
            else
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
            mysqlConnect(1);
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
    global $text;
    mysqlConnect(1);
    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'question_menu' WHERE user_id = {$user_id}");
    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
    mysqlConnect(0);
    $row = mysqli_fetch_array($result);
    mail("postmaster@discourse.padpors.com", "Entrance", $text, "From: {$row['email']}");
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
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        mysqlConnect(0);
        if($row['question_string'] == "111111")
            userMenu(1,1);
        else
            userMenu(1,0);
    }
    elseif ($level == "user_menu")
    {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        mysqlConnect(0);
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

function question_menu()                    //show user the questions and user menu button
{
    global $user_id;
    global $message_id;
    global $db;
    global $locale;
    mysqlConnect(1);
    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
    $row = mysqli_fetch_array($result);
    $string = $row['question_string'];
    mysqli_query($db,"UPDATE padporsc_bot4.users SET current_level = 'question_showed' WHERE user_id = {$user_id}");
    mysqlConnect(0);
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

function questionHandling()                     //handle requests from question menu button
{
    global $user_id;
    global $text;
    global $db;
    if($text == "us3R_m3nU")
    {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        mysqlConnect(0);
        if($row['question_string'] == "111111")
            userMenu(1,1);
        else
            userMenu(1,0);
    }elseif ($text == "f1rst_Qu3stion" || $text == "sec0nd_Qu3stion" || $text == "th1rd_Qu3stion" || $text == "f0rth_Qu3stion" || $text == "f1fth_Qu3stion" || $text == "s1x_Qu3stion")
    {
        $b = 2;
        if($text == "f1rst_Qu3stion")
            $b = 1;
        elseif ($text == "sec0nd_Qu3stion")
            $b = 2;
        elseif ($text == "th1rd_Qu3stion")
            $b = 3;
        elseif ($text == "f0rth_Qu3stion")
            $b = 4;
        elseif ($text == "f1fth_Qu3stion")
            $b = 5;
        elseif ($text == "s1x_Qu3stion")
            $b = 6;
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'asking_question', question_number = {$b} WHERE user_id = {$user_id}");
        mysqlConnect(0);
        askQuestion($b);
    }
}

function returnQuestion($b)              //input=question number * output=question string
{
    global $locale;
    if($locale == "farsi")
    {
        if($b == 1)
            return "طرح پرسشنامه برای آمارگیری مجازی
یه پرسشنامه خوب میتونه واسه قضیه آلایندگی خودروهای شخصی مناسب باشه، چون آلودگی هوا و استفاده از خودرو بیشتر یجور نوع مصرفه که می‌تونه اصلاح بشه. چه سوالاتی پیشنهاد میدید برای یه پرسشنامه؟";
        elseif ($b == 2)
            return "چه جوری میشه توجه آدمای بیشتری رو به آلودگی هوا جلب کرد؟
وقتی توی یه هوای آلوده زندگی کنیم، کم کم این معضل برامون عادی می شه، بهش عادت می کنیم و دنبال رفعشم نیستیم. به چه روش های خلاقانه و ساده ای میشه راجع به موضوع آلودگی هوا آگاهی و توجه رو ایجاد کرد که بهش عادت نکنیم";
        elseif ($b == 3)
            return "کلان شهرهای پایدار چه ویژگی هایی باید داشته باشن؟
مثلا چین و ژاپن با تراکم جمعیت مشابه، آسمون های متفاوتی دارن: چین به شدت آلوده و ژاپن به شدت پاک! به نظرتون کلان‌شهرهای پایداری مثل ژاپن، چه ویژگی‌های مشترکی دارن؟";
        elseif ($b == 4)
            return "چیکار کنیم که استفاده از خودرو به حداقل برسه؟
داستان این است که بیش از ۸۰ درصد آلودگی هوا (هوایی که نفس می کشیم) ناشی از تردد خودرو های شخصی است!‌ حالا این یعنی چی!؟‌ 
یعنی اینکه این وسیله نقلیه با یه سوختی کار می کند که دیر یازود تمام می شود (سوخت فسیلی) و این سوخت هنگام سوختن آلایندگی دارد! 
این وسیله نقلیه برای حرکت کردن و نگهداری از آن به فضای زیادی احتیاج دارد! 
تردد خودرو شخصی باعث آلودگی صوتی جدی می شود!
یک حجمی به وزن بالا می تونه با سرعتی بیش از 60 کیلومتر درساعت حرکت کنه! این تقریبا\" میشه یک اسلحه مرگبار!

در نتیجه این وسیله، وسیله \"نا کارآمدی\" است یعنی پایدار نیست، یعنی انسانی نیست یعنی با طبیعت نمی تواند خودرا تطبیق دهد.
ساده و دوستانه بگم: غیر هوشمندانه، غیر اخلاقی، جاهلانه و غیر انسانی ست استفاده از وسیله ای که آسیب آن بیشتر از سود آن است!

سوال اصلی این است: چیکار کنیم که استفاده از این وسیله به حداقل برسه؟";
        elseif ($b == 5)
            return "چه جوری میشه هوا رو بازیافت کرد؟
چه روش‌های عجیبی به ذهنتون می‌رسه که بتونیم باهاش هوا رو پاک کنیم؟";
        elseif ($b == 6)
            return "سنجش آلودگی هوا به روش های نو
گاهی مامان بزرگم ازم می‌پرسن که آیا امروز می‌تونن برن بیرون یا نه، و من میمونم که روی چه حسابی اطلاعات بدم. مخصوصا که خونه ی مامان بزرگم وب نداره! به نظرتون به چه روش های نویی میشه آلودگی هوا رو سنجید؟";
    }
    elseif ($locale == "english")
    {
        if($b == 1)
            return "Designing a questionnaire
            A good questionnaire about car pollution, can increase awareness and help us improve our consumption habits. What questions do you suggest to put in such a questionnaire?";
        elseif ($b == 2)
            return "How can we draw attentions to the pollution challenge? 
When we live in a polluted air, we get used to it and forget to improve it. In what creative and simple ways can we draw others attention to this problem, so that we don’t get addicted to our situations? ";
        elseif ($b == 3)
            return "How can we make cities sustainable? 
China and Japan have similar human density, but different skies! One highly polluted and the other one clean. How can we build metropolises like Tokyo? What are the “to-do’s and not-t-do’s”?";
        elseif ($b == 4)
            return "How can we reduce number of cars in a city?
Using cars has many damages to our environment: 1- air pollution because of oil burned out, 2- sound pollution 3- a deadly weapon because of the speed of this heavy mass. 
As a result, it is better to use it less. What creative ways do you know to reduce the number of cars in our cities?";
        elseif ($b == 5)
            return "If anything was possible, what out of box and extraordinary ways comes into your mind to clean the air? ";
        elseif ($b == 6)
            return "How can we measure pollution?
There are times my grandmother asks me if she can go out or not, and I really don’t know how to answer here! P.s.: OF course she doesn’t have web in her place! 
What new and out of box methods do you suggest to measure the pollution?";
    }
}

function askQuestion($b)                    //ask user the chosen question LEVEL = question_asked
{
    global $user_id;
    global $db;
    global $message_id;
    global $locale;
    $string = returnQuestion($b);
    if ($locale == "farsi")
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $string, "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "انصراف", "callback_data" => "Ca_nC_31"]
                ]
            ]
        ])]);
    elseif ($locale == "english")
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $string, "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "Cancel", "callback_data" => "Ca_nC_31"]
                ]
            ]
        ])]);
    mysqlConnect(1);
    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'question_asked' WHERE user_id = {$user_id}");
    mysqlConnect(0);
}

function userMenu($a,$f)                    //show user the menu and handle its requests
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if($a == 1)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        if($locale == "farsi" && $f == 0)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "سوالات", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "تیم من", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "صفحه شخصی", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "پادپُرس", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "امتیاز من", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
        elseif($locale == "farsi" && $f == 1)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "سوالات", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "تیم من", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "طرح سوال", "callback_data" => "Cr3at3_Qu3sTi0n"]
                    ],
                    [
                        ["text" => "صفحه شخصی", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "پادپُرس", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "امتیاز من", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
        elseif($locale == "english" && $f == 0)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Questions", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "My Team", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "My Profile", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "PADPORS", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "My Score", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
        elseif($locale == "english" && $f == 1)
            makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "انتخاب کن.", "message_id" => $message_id, "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Questions", "callback_data" => "g0_back_to_qu3stion"]
                    ],
                    [
                        ["text" => "My Team", "callback_data" => "mY_t3aM"]
                    ],
                    [
                        ["text" => "Create Question", "callback_data" => "Cr3at3_Qu3sTi0n"]
                    ],
                    [
                        ["text" => "My Profile", "callback_data" => "My_Inf0"]
                    ],
                    [
                        ["text" => "PADPORS", "url" => "http://www.padpors.com"]
                    ],
                    [
                        ["text" => "My Score", "callback_data" => "My_sc0R3"]
                    ]
                ]
            ])]);
    }
    elseif ($a == 2)
    {
        if ($text == "g0_back_to_qu3stion")
            question_menu();
        elseif ($text == "My_Inf0")
            info(0);
        elseif ($text == "My_sc0R3")
            score();
        elseif ($text == "mY_t3aM")
            team(0);
        elseif ($text == "Cr3at3_Qu3sTi0n")
            makeQuestion(0);
    }
}

function team($i)                           //handle creating team and choosing team
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if ($i == 0) {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'team_menu' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        $row = mysqli_fetch_array($result);
        if ($row['team_master_key'] == NULL) {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "عضو هیچ تیمی نیستی ولی میتونی با گزینه های زیر کار تیمی رو با آدما دیگه تجربه کنی", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "می خوام تیم بسازم", "callback_data" => "wanT_T0_Cr3Ate_T3aM"]
                        ],
                        [
                            ["text" => "شاه کلید دارم", "callback_data" => "I_Hav3_MAst3R_K3y"]
                        ],
                        [
                            ["text" => "انتخاب تصادفی تیم", "callback_data" => "RanD0M_T3AM"]
                        ],
                        [
                            ["text" => "بازگشت", "callback_data" => "R3Turn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "You are not in any team.Choose one below to experience TEAMWORK with others", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "I want create a TEAM", "callback_data" => "wanT_T0_Cr3Ate_T3aM"]
                        ],
                        [
                            ["text" => "I have a Master Key", "callback_data" => "I_Hav3_MAst3R_K3y"]
                        ],
                        [
                            ["text" => "Random Team", "callback_data" => "RanD0M_T3AM"]
                        ],
                        [
                            ["text" => "Return", "callback_data" => "R3Turn"]
                        ]
                    ]
                ])]);
        }
        else
            haveTeam(0);
    }
    elseif ($i == 1){
        if ($text == "R3Turn")
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqlConnect(0);
            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        elseif ($text == "wanT_T0_Cr3Ate_T3aM")
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqlConnect(0);
            if ($row['score'] < 10)
            {
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "برای ساختن تیم حداقل باید 10 امتیاز داشته باشی.", "reply_markup" => json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "For Creating a team 10 reputation is needed at least", "reply_markup" => json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
            else{
                mysqlConnect(1);
                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'getting_team_name' WHERE user_id = {$user_id}");
                mysqlConnect(0);
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "اسم تیم خودت رو انتخاب کن.", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "انصراف", "callback_data" => "CanC3l"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "Choose your team name:", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Cancel", "callback_data" => "CanC3l"]
                            ]
                        ]
                    ])]);
            }
        }
        elseif ($text == "I_Hav3_MAst3R_K3y"){
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'get_master_key' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "شاه کلیدت رو وارد کن:", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "انصراف", "callback_data" => "Canc3L"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Enter Your MASTER KEY:", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Cancel", "callback_data" => "Canc3L"]
                        ]
                    ]
                ])]);
        }
        elseif ($text == "RanD0M_T3AM")
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            if ($row['random_try'] < 6)
            {
                $try = $row['random_try'];
                $try++;
                $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE open = 1");
                $b = 0;
                while ( $row = mysqli_fetch_array($result) )
                    $b++;
                if ($b == 0)
                {
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                    if ($locale == "farsi")
                        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "متاسفانه تیمی با ظرفیت خالی پیدا نشد. بعدا دوباره امتحان کن.", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                    elseif ($locale == "english")
                        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "No empty team found. Try Later", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                }
                else
                {
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET trying_log = {$try}, current_level = 'random_find' WHERE user_id = {$user_id}");
                    $team = rand(1, $b);
                    $b = 0;
                    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE open = 1");
                    while ($row = mysqli_fetch_array($result)) {
                        $b++;
                        if ($b == $team)
                            break;
                    }
                    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['master_key']}");
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET team_master_key = {$row['master_key']} WHERE user_id = {$user_id}");
                    $num = 0;
                    while($ros = mysqli_fetch_array($result))
                        $num++;
                    if ($locale == "farsi")
                        makeCurl("editMessageText" . ["chat_id" => $user_id, "text" => "اسم تیم:{$row['name']}
                        تعداد کاربران:{$num}", "message_id" => $message_id, "reply_markup" => json_encode([
                                "inline_keyboard" => [
                                    [
                                        ["text" => "میخوام عضو بشم", "callback_data" => "Y3s_I_WanT"]
                                    ],
                                    [
                                        ["text" => "نه، نمیخوام", "callback_data" => "N0_I_DonT"]
                                    ]
                                ]
                            ])]);
                    elseif ($locale == "english")
                        makeCurl("editMessageText" . ["chat_id" => $user_id, "text" => "Team name: {$row['name']}
                        Users: {$num}", "message_id" => $message_id, "reply_markup" => json_encode([
                                "inline_keyboard" => [
                                    [
                                        ["text" => "I want this team", "callback_data" => "Y3s_I_WanT"]
                                    ],
                                    [
                                        ["text" => "I don't want this team", "callback_data" => "N0_I_DonT"]
                                    ]
                                ]
                            ])]);
                }
            }
            else
            {
                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "در هر روز حداکثر 5 بار میتونی ازین دکمه استفاده کنی.", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "You can use this button Maximum 5 Times a day", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
            mysqlConnect(0);
        }
    }
    elseif ($i == 2){
        if ($text == "CanC3l")
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqlConnect(0);
            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }else
        {
            $rand = rand(1000000000000000,9999999999999999);
            mysqlConnect(1);
            while(1)
            {
                $b = 0;
                $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams");
                while($row = mysqli_fetch_array($result))
                    if($row['team_master_key'] == $rand)
                        $b = 1;
                if ($b == 1)
                    $rand = rand(1000000000000000,9999999999999999);
                else
                    break;
            }
            mysqli_query($db, "INSERT INTO padporsc_bot4.teams (master_key, name, score) VALUES ({$rand}, \"{$text}\", 0)");
            mysqli_query($db, "UPDATE padporsc_bot4.users SET team_master_key = {$rand}, current_level = 'user_menu' WHERE user_id = {$user_id}");
            mysqli_query($db, "CREATE TABLE padporsc_bot4.team{$rand} ( user_id INT NULL , level INT NULL )");
            mysqli_query($db, "INSERT INTO padporsc_bot4.team{$rand} (user_id, level) VALUES ({$user_id}, 1)");
            mysqlConnect(0);
            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "بهت تبریک میگم. تیمت ساخته شد", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Congratulation, You have maded your team", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
    }
    elseif ($i == 3){
        if ($text == "Canc3L")
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        else
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE 1");
            $b = 0;
            while($row = mysqli_fetch_array($result))
            {
                if ($text == $row['master_key'])
                {
                    $b = 1;
                    break;
                }
            }
            if ($b == 0)
            {
                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "تیمی با این شاه کلید پیدا نشد", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "No team found with this Master Key", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
            elseif ($b == 1)
            {
                $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$text} WHERE 1");
                $number = 0;
                while($row = mysqli_fetch_array($result))
                {
                    $number++;
                }
                if ($number < 5)
                {
                    $number++;
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu', team_master_key = {$text} WHERE user_id = {$user_id}");
                    mysqli_query($db, "INSERT INTO padporsc_bot4.team{$text} (user_id, level) VALUES ({$user_id}, {$number})");
                    if ($number == 5)
                        mysqli_query($db, "UPDATE padporsc_bot4.teams SET open = 0 WHERE master_key = {$text}");
                    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE master_key = {$text}");
                    $row = mysqli_fetch_array($result);
                    if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "عضو تیم {$row['name']} شدی
                    سطحت در این تیم {$number} هست. برای دیدن تیم وارد بخش تیم من شو", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                    elseif ($locale == "english")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "You are now member of team {$row['name']}.
                        Your level in team is {$number}. You can see your team in my team", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                }
                else
                {
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                    if ($locale == "farsi")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "ظرفیت تیم پر است", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                    elseif ($locale == "english")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Team is full", "reply_markup" => json_encode([
                            "inline_keyboard" => [
                                [
                                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                                ]
                            ]
                        ])]);
                }
            }
            mysqlConnect(0);
        }
    }
    elseif ($i == 4){
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
        $res = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row2 = mysqli_fetch_array($res);
        $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE master_key = {$row2['team_master_key']}");
        $row3 = mysqli_fetch_array($result2);
        $teamName = $row3['name'];
        if ($text == "Y3s_I_WanT")
        {
            $ans = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row2['team_master_key']}");
            $z = 0;
            while ($g = mysqli_fetch_array($ans))
                $z++;
            $z++;
            mysqli_query($db, "INSERT INTO padporsc_bot4.team{$text} (user_id, level) VALUE ({$user_id}, {$z})");
            if ($z == 5)
                mysqli_query($db, "UPDATE padporsc_bot4.teams SET open = 0 WHERE master_key = {$row2['team_master_key']}");
            if($locale == "farsi")
                makeCurl("editMessageText", ["text" => " شما به تیم {$teamName} اضافه شدید", "message_id" => $message_id, "chat_id" => $user_id, "reply_markup" =>
                    json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            elseif($locale == "english")
                makeCurl("editMessageText", ["text" => "You have been added to team {$teamName}", "message_id" => $message_id, "chat_id" => $user_id, "reply_markup" =>
                    json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
        }
        elseif ($text == "N0_I_DonT")
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqlConnect(0);
            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        mysqlConnect(0);
    }
}

function haveTeam($i)                               //handle if you have team menu TODO not complete .change name and kick should be complete
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    mysqlConnect(1);
    if ($i == 0)
    {
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $ress = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']} WHERE user_id = {$user_id}");
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'have_team_menu' WHERE user_id = {$user_id}");
        $content = mysqli_fetch_array($ress);
        $glevel = $content['level'];
        if ($glevel == 1)
        {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "مشاهده ی تیم", "callback_data" => "WatCh_TE3AM"]
                        ],
                        [
                            ["text" => "پیام به تیم", "callback_data" => "msG_T0_T33m"],["text" => "اضافه کردن دوستان", "callback_data" => "Temamm_MASTeR_K3y"]
                        ],
                        [
                            ["text" => "تغییر نام", "callback_data" => "Cjangingggg_nakek"],["text" => "حذف بازیکن", "callback_data" => "DelET3_Us3TRR"]
                        ],
                        [
                            ["text" => "ترک تیم", "callback_data" => "LE3aVEEEE_TEMM"]
                        ],
                        [
                            ["text" => "بازگشت", "callback_data" => "R3TUrn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Show team", "callback_data" => "WatCh_TE3AM"]
                        ],
                        [
                            ["text" => "Send message to team", "callback_data" => "msG_T0_T33m"],["text" => "Add member", "callback_data" => "Temamm_MASTeR_K3y"]
                        ],
                        [
                            ["text" => "Change Name", "callback_data" => "Cjangingggg_nakek"],["text" => "Kick user", "callback_data" => "DelET3_Us3TRR"]
                        ],
                        [
                            ["text" => "Leave Team", "callback_data" => "LE3aVEEEE_TEMM"]
                        ],
                        [
                            ["text" => "Retrun", "callback_data" => "R3TUrn"]
                        ]
                    ]
                ])]);
        }
        else
        {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "مشاهده ی تیم", "callback_data" => "WatCh_TE3AM"]
                        ],
                        [
                            ["text" => "پیام به تیم", "callback_data" => "msG_T0_T33m"]
                        ],
                        [
                            ["text" => "ترک تیم", "callback_data" => "LE3aVEEEE_TEMM"]
                        ],
                        [
                            ["text" => "بازگشت", "callback_data" => "R3TUrn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Show team", "callback_data" => "WatCh_TE3AM"]
                        ],
                        [
                            ["text" => "Send message to team", "callback_data" => "msG_T0_T33m"]
                        ],
                        [
                            ["text" => "Leave Team", "callback_data" => "LE3aVEEEE_TEMM"]
                        ],
                        [
                            ["text" => "Retrun", "callback_data" => "R3TUrn"]
                        ]
                    ]
                ])]);
        }
    }
    elseif ($i == 1)
    {
        if ($text == "R3TUrn")
        {

            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);

            if($row['question_string'] == "111111")
                userMenu(1,1);
             else
                userMenu(1,0);
        }
        elseif ($text == "WatCh_TE3AM")
        {
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            $teamt = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
            $teamScore = 0;
            $help = 0;
            while ($teamr = mysqli_fetch_array($teamt))
            {
                $teammatet = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$teamr['user_id']}");
                $teammater = mysqli_fetch_array($teammatet);
                $teamScore = $teamScore + $teammater['score'];
                if ($help == 0) {
                    $help = 1;
                    if ($locale == "farsi")
                        makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "نام: {$teammater['user_first_name']} امتیاز: {$teammater['score']}"]);
                    elseif ($locale == "english")
                        makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "Name: {$teammater['user_first_name']} Score: {$teammater['score']}"]);
                }
                elseif ($help == 1)
                {
                    if ($locale == "farsi")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "نام: {$teammater['user_first_name']} امتیاز: {$teammater['score']}"]);
                    elseif ($locale == "english")
                        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Name: {$teammater['user_first_name']} Score: {$teammater['score']}"]);
                }
            }
            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "امتیاز تیم: {$teamScore}", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Team Score: {$teamScore}", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
        elseif ($text == "msG_T0_T33m")
        {
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'send_message' WHERE user_id = {$user_id}");
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "text" => "پیامت رو وارد کن:", "message_id" => $message_id, "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "انصراف", "callback_data" => "CancELL"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Enter Your Message:", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Cancel", "callback_data" => "CancELL"]
                        ]
                    ]
                ])]);
        }
        elseif ($text == "LE3aVEEEE_TEMM")
        {
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'leaving' WHERE user_id = {$user_id}");
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "من فک کنم آدمای خوبین، مطمئنی میخواهی بری بیرون؟", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "آره، جای من دیگه اینجا نیست", "callback_data" => "Y3SESS"]
                        ],
                        [
                            ["text" => "نه بابا، شوخی کردم.", "callback_data" => "NO0OOOoo"]
                        ]
                      ]
                ])
                ]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Are you Sure:", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Yes,I am", "callback_data" => "Y3SESS"]
                        ],
                        [
                            ["text" => "Noooo", "callback_data" => "NO0OOOoo"]
                        ]
                    ]
                ])
                ]);
        }
        elseif ($text == "Temamm_MASTeR_K3y")
        {
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $row['team_master_key'], "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "اینو بده به دوستت تا باهاش بیاد توی تیمت", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => $row['team_master_key'], "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Give it to your friend to enter the team", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
    }
    mysqlConnect(0);
}

function leaving()                  //used when user want to leave a team
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if ($text == "Y3SESS")
    {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
        $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']} WHERE user_id = {$user_id}");
        $row3 = mysqli_fetch_array($result3);
        $ulevel = $row3['level'];
        while ($row2 = mysqli_fetch_array($result2))
        {
            if ($row2['level'] > $ulevel )
            {
                $h = $row2['level'];
                $h--;
                mysqli_query($db, "UPDATE padporsc_bot4.team{$row['team_master_key']} SET level = {$h} WHERE user_id = {$row2['user_id']}");
            }
        }
        mysqli_query($db, "DELETE FROM padporsc_bot4.team{$row['team_master_key']} WHERE user_id = {$user_id}");
        mysqli_query($db, "UPDATE padporsc_bot4.users SET team_master_key = NULL, current_level = 'user_menu' WHERE user_id = {$user_id}");
        if ($locale == "farsi")
            makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "از تیم اومدی بیرون", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "You have exited the team", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
        mysqlConnect(0);
    }
    elseif ($text == "NO0OOOoo")
        haveTeam(0);
}

function makeQuestion($i)                       //make question and change level to making_question
{
    global $user_id;
    global $message_id;
    global $locale;
    global $db;
    global $text;
    if ($i == 0)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'making_question' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "سوال خودت رو مطرح کن", "reply_markup" => json_encode([
                "inline_keyboard" => [
                     [
                         ["text" => "انصراف", "callback_data" => "Ca_nC_31"]
                     ]
                ]
           ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Type your OWN question", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Cancel", "callback_data" => "Ca_nC_31"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {
        if ($text == "Ca_nC_31")
            userMenu(1,1);
        else
        {
            if (recognize($text) == 0) {
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "سوال خودت رو مطرح کن", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "انصراف", "callback_data" => "Ca_nC_31"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Type your OWN question", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Cancel", "callback_data" => "Ca_nC_31"]
                            ]
                        ]
                    ])]);
            }
            elseif (recognize($text) == 1)
            {
                mysqlConnect(1);
                $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
                $row = mysqli_fetch_array($result);
                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                mail("postmaster@discourse.padpors.com", "User's Question", $text, "From: {$row['email']}");
                mysqlConnect(0);
                if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "سوالت ثبت شد", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Your question saved", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
        }
    }
}

function score()                                //calculate the score
{
    global $user_id;
    global $db;
    global $message_id;
    global $locale;
    mysqlConnect(1);
    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
    $row = mysqli_fetch_array($result);
    if ($row['padpors_username'])
        $username = $row['padpors_username'];
    else
    {
        $email = $row['email'];
        $pos = strpos($email, "@");
        $username = substr($email, 0, $pos);
    }
    $score = 0;
    $xml = file_get_contents("https://padpors.com/users/{$username}/summary.json?api_key=****&api_username=****");           //TODO don't commit API key
    $answer = json_decode($xml);
    if ($answer -> user_summary -> likes_received)
        $score = $answer -> user_summary -> likes_received;
    if ($row['logged_in'] == 1)
        $score = $score + 5;
    mysqli_query($db, "UPDATE padporsc_bot4.users SET score = {$score} WHERE user_id = {$user_id}");
    mysqlConnect(0);
    if ($locale == "farsi")
        makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "اعتبار شما: {$score}", "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                ]
            ]
        ])]);
    elseif ($locale == "english")
        makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "Your Reputation: {$score}", "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                ]
            ]
        ])]);
}

function info($i)                       //show and handle my info button
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if($i == 0)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'watching_info' WHERE user_id = {$user_id}");
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        mysqlConnect(0);
        $row = mysqli_fetch_array($result);
        if ($row['logged_in'] == 0) {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تغییر زبان", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "ورود به پادپُرس", "callback_data" => "my_Padp0rS"]
                        ],
                        [
                            ["text" => "ایمیل من",  "callback_data" => "my_3ma1L"]
                        ],
                        [
                            ["text" => "بازگشت", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Change Language", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "Log into PadPors", "callback_data" => "my_Padp0rS"]
                        ],
                        [
                            ["text" => "My Email",  "callback_data" => "my_3ma1L"]
                        ],
                        [
                            ["text" => "Return", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
        }
        elseif ($row['logged_in'] == 1){
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تغییر زبان", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "بازگشت", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Change Language", "callback_data" => "ChanG3_lanGuag3"]
                        ],
                        [
                            ["text" => "Return", "callback_data" => "R3tuRn"]
                        ]
                    ]
                ])]);
        }
    }
    elseif ($i == 1)
    {
        if ($text == "R3tuRn")
        {
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqlConnect(0);
            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        elseif ($text == "ChanG3_lanGuag3")
            changeLang(0);
        elseif ($text == "my_3ma1L")
            myEmail(0);
        elseif ($text == "my_Padp0rS")
            account(0);

    }
}

function account($i)                    //sync user's padpors account with padbot and make account
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if ($i == 0)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'how_Enter' WHERE user_id = {$user_id}");
        mysqlConnect(0);
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

        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'current_user' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        }
        elseif ($text == "MAK3_ACc0uNt")
        {
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'getting_username_for_sign_up' WHERE user_id = {$user_id}");
            $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            mysqlConnect(0);
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
                mysqlConnect(1);
                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                mysqlConnect(0);
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
            mysqlConnect(1);
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqlConnect(0);
            $email = $row['email'];
            $xml = file_get_contents("https://padpors.com/admin/users/list/active.json?filter={$email}&show_emails=false&_=1484208836960&api_key=****&api_username=****");           //TODO don't commit API KEY
            $answer = json_decode($xml);
            if($answer[0] -> username)
            {
                $username = $answer[0] -> username;
                if(strcasecmp($username ,$text) == 0)
                {
                    mysqlConnect(1);
                    $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
                    $row2 = mysqli_fetch_array($result2);
                    $score = $row2['score'];
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu',score = $score, padpors_username = \"{$username}\", logged_in = 1 WHERE user_id = {$user_id}");
                    mysqlConnect(0);
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
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET padpors_username = \"{$text}\", current_level = 'getting_password' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "حالا رمزت رو وارد کن."]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Enter your password now"]);
        }
    }
    elseif ($i == 4)
    {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        mysqlConnect(0);
        $row = mysqli_fetch_array($result);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://localhost:3000/users/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(["username" => $row['padpors_username'] , "password" => $text, "email" => $row['email'], "api_key" => "7ebc7612d834903389f9855b96171aaa69c30635abfcdffd33a36d4fc15c4f12", "api_username" => "alavi", "acive" => false]));     //TODO this should be real api
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        echo $server_output;
        $ans2 = json_decode($server_output);
        echo $ans2 -> message;
        if ($ans2 -> success)
        {
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET trying_log = 1, current_level = 'user_menu' WHERE user_id = {$user_id}");
            mysqlConnect(0);
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
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu', padpors_username = NULL WHERE user_id = {$user_id}");
            mysqlConnect(0);
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

function myEmail($i)                            //used to show user his email and change his email
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if($i == 0)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'changing_email' WHERE user_id = {$user_id}");
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        mysqlConnect(0);
        $row = mysqli_fetch_array($result);
        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "ایمیل شما: {$row['email']}
            برای تغییر ایمیل جدید را وارد کنید", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "بازگشت", "callback_data" => "R3Turn"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Your Email: {$row['email']}
            Enter New Email If you want to change it", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Return", "callback_data" => "R3Turn"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {
        if ($text == "R3Turn")
            info(0);
        else
        {
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'watching_info', email = \"{$text}\" WHERE user_id = {$user_id}");
            mysqlConnect(0);
            if ($locale == "farsi")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "ایمیل شما با موفقیت تغییر یافت", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Your Email Changes successfully", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
    }
}

function changeLang($i)                 //show and change the language for user
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if ($i == 0)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'watching_change_language' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن.", "reply_markup" => json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "فارسی", "callback_data" => "chang3_T0_p3Rs1an"], ["text" => "انگلیسی", "callback_data" => "chang3_T0_3nGl1sH"]
                    ],
                    [
                        ["text" => " بازگشت", "callback_data" => "R3tuRn"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                "inline_keyboard" =>[
                    [
                        ["text" => "Farsi", "callback_data" => "chang3_T0_p3Rs1an"], ["text" => "English", "callback_data" => "chang3_T0_3nGl1sH"]
                    ],
                    [
                        ["text" => "Return", "callback_data" => "R3tuRn"]
                    ]
                ]
            ])]);
    }
    elseif ($i == 1)
    {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        mysqlConnect(0);
        if($row['question_string'] == "111111")
            $b = 1;
        else
            $b = 0;
        if ($text == "R3tuRn")
            userMenu(1, $b);
        elseif ($text == "chang3_T0_p3Rs1an")
        {
            $locale = "farsi";
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'farsi' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            userMenu(1, $b);
        }
        elseif ($text == "chang3_T0_3nGl1sH")
        {
            $locale = "english";
            mysqlConnect(1);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET locale = 'english' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            userMenu(1, $b);
        }
    }

}

function startAsking()                      //handle the first request to bot after showing the question * level = answering
{
    global $db;
    global $user_id;
    global $text;
    global $locale;
    if($text == "Ca_nC_31")
        question_menu();
    else
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'answering', current_content = \"{$text}\" WHERE user_id = {$user_id}");
        mysqlConnect(0);
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "اگه پاسخت تموم شد، بزن روی دکمه ی زیر.وگرنه هنوز میتونی به نوشتنت ادامه بدی و پاسخت رو تکمیل کنی.",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تمومش کن","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "I you have finished writing tap on the button below.Or you can continue writing without any problem",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "End it","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
    }
}

function answering()                    //after answering this function will handle every thing.
{
    global $user_id;
    global $text;
    global $db;
    global $locale;
    global $message_id;
    if ($text == "3nD_iT")
    {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $qstring = $row['question_string'];
        $qstring[$row['question_number'] - 1] = "1";
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'question_menu', current_content = NULL , question_number = 0 , question_string = \"{$qstring}\" WHERE user_id = {$user_id}");
        if ($row['team_master_key'])
            $team = $row['team_master_key'];
        else
            $team = 0;
        mysqli_query($db, "INSERT INTO padporsc_bot4.user{$user_id} (content, question_number, group_of_answer_master_key) VALUES (\"{$row['current_content']}\", {$row['question_number']}, {$team})");
        mysqlConnect(0);
        mail("postmaster@discourse.padpors.com", returnQuestion($row['question_number']), $row['current_content'], "From: {$row['email']}");
        if ($locale == "farsi")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "ممنون ازین که به این سوال پاسخ دادی.", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Thanks for your answer", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);

    }
    elseif(recognize($text) == 0)
    {
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "اگه پاسخت تموم شد، بزن روی دکمه ی زیر.وگرنه هنوز میتونی به نوشتنت ادامه بدی و پاسخت رو تکمیل کنی.",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تمومش کن","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "If you have finished writing tap on the button below.Or you can continue writing without any problem",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "End it","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
    }
    elseif (recognize($text) == 1){
        mysqlConnect(1);
        $result=mysqli_query($db,"SELECT * FROM padporsc_bot4.users WHERE user_id={$user_id}");
        $row = mysqli_fetch_array($result);
        $content = $row['current_content'];
        $content .= " ";
        $content .= $text;
        mysqli_query($db,"UPDATE padporsc_bot4.users set  current_content = \"{$content}\" WHERE user_id={$user_id}");
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "اگه پاسخت تموم شد، بزن روی دکمه ی زیر.وگرنه هنوز میتونی به نوشتنت ادامه بدی و پاسخت رو تکمیل کنی.",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "تمومش کن","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "If you have finished writing tap on the button below.Or you can continue writing without any problem",
                "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "End it","callback_data" => "3nD_iT"]
                        ]
                    ]
                ])
            ]);
    }
}

function send()
{
    global $user_id;
    global $db;
    global $text;
    global $user_firstname;
    global $locale;
    if ($text == "CancELL")
        haveTeam(0);
    else
    {
        mysqlConnect(1);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
        while ($row = mysqli_fetch_array($result))
        {
            if ($row['user_id'] != $user_id)
            {
                if ($locale == "farsi")
                    makeCurl("sendMessage", ["chat_id" => $row['user_id'], "text" => "{$user_firstname}✉️:{$text}"]);
                elseif ($loacle == "english")
                    makeCurl("sendMessage", ["chat_id" => $row['user_id'], "text" => "{$user_firstname}✉️:{$text}"]);
            }
        }
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'team_menu' WHERE  user_id = {$user_id}");
        if ($locale == "farsi")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "پیامت ارسال شد.", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
        elseif ($locale == "english")
            makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Your Message Sent", "reply_markup" => json_encode([
                "inline_keyboard" => [
                    [
                        ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                    ]
                ]
            ])]);
        mysqlConnect(0);
    }
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
            elseif ($level == "question_menu")
                question_menu();
            elseif ($level == "question_showed")
                questionHandling();
            elseif ($level == "user_menu")
                userMenu(2,0);
            elseif ($level == "question_asked")
                startAsking();
            elseif ($level == "answering")
                answering();
            elseif ($level == "watching_info")
                info(1);
            elseif ($level == "watching_change_language")
                changeLang(1);
            elseif ($level == "changing_email")
                myEmail(1);
            elseif ($level == "making_question")
                makeQuestion(1);
            elseif ($level == "how_Enter")
                account(1);
            elseif ($level == "current_user")
                account(2);
            elseif ($level == "getting_username_for_sign_up")
                account(3);
            elseif ($level == "getting_password")
                account(4);
            elseif ($level == "team_menu")
                team(1);
            elseif ($level == "getting_team_name")
                team(2);
            elseif ($level == "get_master_key")
                team(3);
            elseif ($level == "random_find")
                team(4);
            elseif ($level == "have_team_menu")
                haveTeam(1);
            elseif ($level == "send_message")
                send();
            elseif ($level == "leaving")
                leaving();
            $last_updated_id = $update->update_id;              //should be removed
        }           //should be removed
    }               //should be removed
}

while(1) {
    main();
}
