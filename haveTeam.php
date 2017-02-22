<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:50 PM
 */
function haveTeam($i)                               //handle if you have team menu
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;

    if ($i == 0)
    {
        $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
        $row = mysqli_fetch_array($result);
        $ress = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']} WHERE user_id = {$user_id}");
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'have_team_menu' WHERE user_id = {$user_id}");
        $content = mysqli_fetch_array($ress);
        $teem = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE master_key = {$row['team_master_key']}");
        $rrr = mysqli_fetch_array($teem);
        if ($rrr['open'] == 1 )
        {if ($locale == "farsi")
            $sre = "بستن تیم";
        elseif ($locale == "english")
            $sre = "Close Team";
        }
        elseif ($rrr['open'] == 0)
        {if ($locale == "farsi")
            $sre = "باز کردن تیم";
        elseif ($locale == "english")
            $sre = "Open Team";
        }
        $glevel = $content['level'];
        if ($glevel == 1)
        {
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "مشاهده ی تیم", "callback_data" => "WatCh_TE3AM"],["text" =>$sre, "callback_data" => "Clos33_RAndomm"]
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
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Show team", "callback_data" => "WatCh_TE3AM"],["text" => $sre, "callback_data" => "Clos33_RAndomm"]
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
        elseif ($text == "Clos33_RAndomm")
        {
            $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row2 = mysqli_fetch_array($result2);
            $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.teams WHERE master_key = {$row2['team_master_key']}");
            $row3 = mysqli_fetch_array($result3);
            if ($row3['open'] == 1)
            {
                mysqli_query($db, "UPDATE padporsc_bot4.teams SET open = 0 WHERE master_key = {$row2['team_master_key']}");
                mysqli_query($db, "UPDATE padporsc_bot4.usres SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "حالا دیگه کسی بدون شاه کلید نمیتونه وارد تیمت بشه", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Your team is now hidden from random search, tap on random search", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
            elseif ($row3['open'] == 0)
            {
                mysqli_query($db, "UPDATE padporsc_bot4.teams SET open = 1 WHERE master_key = {$row2['team_master_key']}");
                mysqli_query($db, "UPDATE padporsc_bot4.usres SET current_level = 'user_menu' WHERE user_id = {$user_id}");
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "حالا تیمت توی جستوجوی تصادفی پیدا میشه", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Your team will be found in random search", "reply_markup" => json_encode([
                        "inline_keyboard" => [
                            [
                                ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                            ]
                        ]
                    ])]);
            }
        }
        elseif ($text == "Cjangingggg_nakek")
        {
            changeName(0);
        }
        elseif ($text == "DelET3_Us3TRR")
        {
            kick(0);
        }
    }

}