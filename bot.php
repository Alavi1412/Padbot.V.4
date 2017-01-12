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
    makeCurl("sendPhoto",["chat_id" => $user_id, "photo" => "https://padpors.com//uploads/default/original/2X/4/4af4b49dc716348d5f988e5664d97795dbc1e04f.png", "reply_markup" => json_encode([
        "inline_keyboard" =>[
            [
                ["text" => "English", "callback_data" => "3ngL1$1h"],["text" => "فارسی" , "callback_data" => "P3R$1an"]
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
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "به پادبات خوش اومدی، بات رسمی پادپُرس. ایمیلت رو وارد کن و اگر کاربر پادپُرس هستی، ایمیل پادپُرست رو وارد کن."]);
    }elseif ($text == "3ngL1$1h")
    {
        mysqli_query($db, "UPDATE bot.users SET locale = 'english', current_level = 'firstStep' WHERE user_id = {$user_id}");
        makeCurl("sendMessage", ["chat_id" => $user_id, "text" => "Enter your email; if you are Padpors user, provide your Padpors email."]);
    }
    mysqli_query($db, "CREATE TABLE bot.user{$user_id} ( content LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL , score INT NULL DEFAULT '0' , question_number INT NULL , group_of_answer_master_key INT NULL , answer_id INT NULL )");
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
        $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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
        $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        mysqlConnect(0);
        if($row['question_string'] == "111111")
            userMenu(1,1);
        else
            userMenu(1,0);
    }
    elseif ($level == "current_user")
        info(0);
}

function question_menu()                    //show user the questions and user menu button
{
    global $user_id;
    global $message_id;
    global $db;
    global $locale;
    mysqlConnect(1);
    $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
    $row = mysqli_fetch_array($result);
    $string = $row['question_string'];
    mysqli_query($db,"UPDATE bot.users SET current_level = 'question_showed' WHERE user_id = {$user_id}");
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
        $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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
        mysqli_query($db, "UPDATE bot.users SET current_level = 'asking_question', question_number = {$b} WHERE user_id = {$user_id}");
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
    mysqli_query($db, "UPDATE bot.users SET current_level = 'question_asked' WHERE user_id = {$user_id}");
    mysqlConnect(0);
}

function userMenu($a,$f)                    //show user the menu and handle its requests TODO create not completed
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if($a == 1)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE bot.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
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
        {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "این بخش به زودی اضافه خواهد شد", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["message_id" => $message_id, "chat_id" => $user_id, "text" => "Coming SOON ...", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
        elseif ($text == "Cr3at3_Qu3sTi0n")
            makeQuestion(0);
    }
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
        mysqli_query($db, "UPDATE bot.users SET current_level = 'making_question' WHERE user_id = {$user_id}");
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
                $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
                $row = mysqli_fetch_array($result);
                mysqli_query($db, "UPDATE bot.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                mysqlConnect(0);
                mail("content.padpors@gmail.com", returnQuestion($row['question_number']), $text, "From: {$row['email']}");
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
    $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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
    mysqli_query($db, "UPDATE bot.users SET score = {$score} WHERE user_id = {$user_id}");
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

function info($i)                       //show and handle my info button  TODO my padpors not complete
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if($i == 0)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE bot.users SET current_level = 'watching_info' WHERE user_id = {$user_id}");
        $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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
            $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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

function account($i)                    //sync user's padpors account with padbot
{
    global $message_id;
    global $user_id;
    global $text;
    global $db;
    global $locale;
    if ($i == 0)
    {
        mysqlConnect(1);
        mysqli_query($db, "UPDATE bot.users SET current_level = 'how_Enter' WHERE user_id = {$user_id}");
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
        mysqli_query($db, "UPDATE bot.users SET current_level = 'current_user' WHERE user_id = {$user_id}");
        mysqlConnect(0);
        }
        elseif ($text == "MAK3_ACc0uNt")
        {
            mysqlConnect(1);
            mysqli_query($db, "UPDATE bot.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "این بخش به زودی اضافه خواهد شد.", "reply_markup" => json_encode([
                    "inline_keyboard" =>[
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Coming SOON", "reply_markup" => json_encode([
                    "inline_keyboard" =>[
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
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
            $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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
                    $result2 = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
                    $row2 = mysqli_fetch_array($result2);
                    $score = $row2['score'];
                    mysqli_query($db, "UPDATE bot.users SET current_level = 'user_menu',score = $score, padpors_username = \"{$username}\", logged_in = 1 WHERE user_id = {$user_id}");
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
        mysqli_query($db, "UPDATE bot.users SET current_level = 'changing_email' WHERE user_id = {$user_id}");
        $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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
        elseif ($lcale == "english")
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
            mysqli_query($db, "UPDATE bot.users SET current_level = 'watching_info', email = \"{$text}\" WHERE user_id = {$user_id}");
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
        mysqli_query($db, "UPDATE bot.users SET current_level = 'watching_change_language' WHERE user_id = {$user_id}");
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
        $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
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
            mysqli_query($db, "UPDATE bot.users SET locale = 'farsi' WHERE user_id = {$user_id}");
            mysqlConnect(0);
            userMenu(1, $b);
        }
        elseif ($text == "chang3_T0_3nGl1sH")
        {
            $locale = "english";
            mysqlConnect(1);
            mysqli_query($db, "UPDATE bot.users SET locale = 'english' WHERE user_id = {$user_id}");
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
        mysqli_query($db, "UPDATE bot.users SET current_level = 'answering', current_content = \"{$text}\" WHERE user_id = {$user_id}");
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
        $result = mysqli_query($db, "SELECT * FROM bot.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $qstring = $row['question_string'];
        $qstring[$row['question_number'] - 1] = "1";
        mysqli_query($db, "UPDATE bot.users SET current_level = 'question_menu', current_content = NULL , question_number = 0 , question_string = \"{$qstring}\" WHERE user_id = {$user_id}");
        if ($row['team_master_key'])
            $team = $row['team_master_key'];
        else
            $team = 0;
        mysqli_query($db, "INSERT INTO bot.user{$user_id} (content, question_number, group_of_answer_master_key) VALUES (\"{$row['current_content']}\", {$row['question_number']}, {$team})");
        mysqlConnect(0);
        mail("content.padpors@gmail.com", returnQuestion($row['question_number']), $row['content'], "From: {$row['email']}");
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
        $result=mysqli_query($db,"SELECT * FROM bot.users WHERE user_id={$user_id}");
        $row = mysqli_fetch_array($result);
        $content = $row['content'];
        $content .= " ";
        $content .= $text;
        mysqli_query($db,"UPDATE bot.users set  current_content = \"{$content}\" WHERE user_id={user_id}");
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
            $last_updated_id = $update->update_id;              //should be removed
        }           //should be removed
    }               //should be removed
}
while(1) {
    main();
}
