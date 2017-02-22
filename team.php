<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:49 PM
 */
function team($i)                           //handle creating team and choosing team
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if ($i == 0) {

        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'team_menu' WHERE user_id = {$user_id}");

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

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);

            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        elseif ($text == "wanT_T0_Cr3Ate_T3aM")
        {

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);

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

                mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'getting_team_name' WHERE user_id = {$user_id}");

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

            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'get_master_key' WHERE user_id = {$user_id}");

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

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            if ($row['random_try'] < 5)
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
                        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "متاسفانه تیمی متناسب با تو پیدا نشد. بعدا دوباره امتحان کن.", "reply_markup" => json_encode([
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
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'random_find' WHERE user_id = {$user_id}");
                    $team = rand(1, $b);
                    $b = 0;
                    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE open = 1");
                    while ($row = mysqli_fetch_array($result)) {
                        $b++;
                        if ($b == $team)
                            break;
                    }
                    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['master_key']}");
                    mysqli_query($db, "UPDATE padporsc_bot4.users SET team_master_key = {$row['master_key']}, random_try = {$try} WHERE user_id = {$user_id}");
                    $num = 0;
                    while($ros = mysqli_fetch_array($result))
                        $num++;
                    if ($locale == "farsi")
                        makeCurl("editMessageText" , ["chat_id" => $user_id, "text" => "اسم تیم:{$row['name']}
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
                        makeCurl("editMessageText" , ["chat_id" => $user_id, "text" => "Team name: {$row['name']}
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

        }
    }
    elseif ($i == 2){
        if ($text == "CanC3l")
        {

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);

            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }else
        {
            $rand = rand(1000000000000000,9999999999999999);

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

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu' WHERE user_id = {$user_id}");

            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }
        else
        {

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

        }
    }
    elseif ($i == 4){

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
            mysqli_query($db, "INSERT INTO padporsc_bot4.team{$row2['team_master_key']} (user_id, level) VALUE ({$user_id}, {$z})");
            if ($z == 5)
                mysqli_query($db, "UPDATE padporsc_bot4.teams SET open = 0 WHERE master_key = {$row2['team_master_key']}");
            if($locale == "farsi")
                makeCurl("editMessageText", ["text" => " شما به تیم {$teamName} اضافه شدید", "message_id" => $message_id, "chat_id" => $user_id, "reply_markup" =>
                    json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "یه پیام برای تیمت بفرست و خودتو معرفی کن.", "callback_data" => "C0nT1nu3"]
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

            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            mysqli_query($db, "UPDATE padporsc_bot4.users SET team_master_key = NULL WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);

            if($row['question_string'] == "111111")
                userMenu(1,1);
            else
                userMenu(1,0);
        }

    }
}