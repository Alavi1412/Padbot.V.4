<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:51 PM
 */
function kick($i)                           //for kicking users by admin
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
        mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'kick' WHERE user_id = {$user_id}");
        $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
        $z = 0;
        while ($row2 = mysqli_fetch_array($result2))
        {
            $z++;
            if ($row2['level'] == 2)
                $u1 = $row2['user_id'];
            elseif ($row2['level'] == 3)
                $u2 = $row2['user_id'];
            elseif ($row2['level'] == 4)
                $u3 = $row2['user_id'];
            elseif ($row2['level'] == 5)
                $u4 = $row2['user_id'];
        }
        if ($z == 1)
        {
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'have_team_menu' WHERE user_id = {$user_id}");
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "تنها عضو این تیم خودتی.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Your are the only member of this team", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
        elseif ($z == 2)
        {
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u1}");
            $row = mysqli_fetch_array($result);
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"]
                            ],
                            [
                                ["text" => "انصراف", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"]
                            ],
                            [
                                ["text" => "Cancel", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
        }
        elseif ($z == 3)
        {
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u1}");
            $row = mysqli_fetch_array($result);
            $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u2}");
            $row2 = mysqli_fetch_array($result2);
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"],["text" => $row2['user_first_name'], "callback_data" => "S3c0Ondd"]
                            ],
                            [
                                ["text" => "انصراف", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"],["text" => $row2['user_first_name'], "callback_data" => "S3c0Ondd"]
                            ],
                            [
                                ["text" => "Cancel", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
        }
        elseif ($z == 4)
        {
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u1}");
            $row = mysqli_fetch_array($result);
            $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u2}");
            $row2 = mysqli_fetch_array($result2);
            $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u3}");
            $row3 = mysqli_fetch_array($result3);
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"],["text" => $row2['user_first_name'], "callback_data" => "S3c0Ondd"]
                            ],
                            [
                                ["text" => $row3['user_first_name'], "callback_data" => "Th1rDDD"]
                            ],
                            [
                                ["text" => "انصراف", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"],["text" => $row2['user_first_name'], "callback_data" => "S3c0Ondd"]
                            ],
                            [
                                ["text" => $row3['user_first_name'], "callback_data" => "Th1rDDD"]
                            ],
                            [
                                ["text" => "Cancel", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
        }
        elseif ($z == 5)
        {
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u1}");
            $row = mysqli_fetch_array($result);
            $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u2}");
            $row2 = mysqli_fetch_array($result2);
            $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u3}");
            $row3 = mysqli_fetch_array($result3);
            $result4 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u4}");
            $row4 = mysqli_fetch_array($result4);
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "انتخاب کن", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"],["text" => $row2['user_first_name'], "callback_data" => "S3c0Ondd"]
                            ],
                            [
                                ["text" => $row3['user_first_name'], "callback_data" => "Th1rDDD"],["text" => $row4['user_first_name'], "callback_data" => "F0rThT"]
                            ],
                            [
                                ["text" => "انصراف", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Choose", "reply_markup" => json_encode([
                    "inline_keyboard" =>
                        [
                            [
                                ["text" => $row['user_first_name'], "callback_data" => "Fi1rSt"],["text" => $row2['user_first_name'], "callback_data" => "S3c0Ondd"]
                            ],
                            [
                                ["text" => $row3['user_first_name'], "callback_data" => "Th1rDDD"],["text" => $row4['user_first_name'], "callback_data" => "F0rThT"]
                            ],
                            [
                                ["text" => "Cancel", "callback_data" => "CanCell44s"]
                            ]
                        ]
                ])]);
        }
    }
    elseif ($i == 1)
    {
        if ($text == "CanCell44s")
            haveTeam(0);
        else
        {
            if ($text == "Fi1rSt" || $text == "S3c0Ondd" || $text == "Th1rDDD" || $text == "F0rThT")
            {
                $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
                $row = mysqli_fetch_array($result);
                $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
                $z = 0;
                while ($row2 = mysqli_fetch_array($result2))
                {
                    $z++;
                    if ($row2['level'] == 2)
                        $u1 = $row2['user_id'];
                    elseif ($row2['level'] == 3)
                        $u2 = $row2['user_id'];
                    elseif ($row2['level'] == 4)
                        $u3 = $row2['user_id'];
                    elseif ($row2['level'] == 5)
                        $u4 = $row2['user_id'];
                }
                if ($text == "Fi1rSt")
                {
                    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u1}");
                    $row = mysqli_fetch_array($result);
                    $name = $row['user_first_name'];
                    $user = $row['user_id'];
                }
                elseif ($text == "S3c0Ondd")
                {
                    $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u2}");
                    $row2 = mysqli_fetch_array($result2);
                    $name = $row2['user_first_name'];
                    $user = $row2['user_id'];
                }
                elseif ($text == "Th1rDDD")
                {
                    $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u3}");
                    $row3 = mysqli_fetch_array($result3);
                    $name = $row3['user_first_name'];
                    $user = $row3['user_id'];
                }
                elseif ($text == "F0rThT")
                {
                    $result4 = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$u4}");
                    $row4 = mysqli_fetch_array($result4);
                    $name = $row4['user_first_name'];
                    $user = $row4['user_id'];
                }
                mysqli_query($db, "UPDATE padporsc_bot4.users SET temp = {$user}, current_level = 'make_sure' WHERE user_id = {$user_id}");
                if ($locale == "farsi")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "مطئن هستی که میخواهی {$name} رو اخراج کنی؟", "reply_markup" => json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "آره", "callback_data" => "YESSSSSSSS33"],["text" => "نه", "callback_data" => "NO0o0oOO0"]
                            ]
                        ]
                    ])]);
                elseif ($locale == "english")
                    makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Are You sure you want to Kick {$name}", "reply_markup" => json_encode([
                        "inline_keyboard" =>[
                            [
                                ["text" => "Yes", "callback_data" => "YESSSSSSSS33"],["text" => "No", "callback_data" => "NO0o0oOO0"]
                            ]
                        ]
                    ])]);
            }
            else
                haveTeam(0);
        }
    }
    elseif ($i == 2)
    {
        if ($text == "YESSSSSSSS33")
        {
            $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
            $row = mysqli_fetch_array($result);
            $result2 = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
            $result3 = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']} WHERE user_id = {$row['temp']}");
            echo $row['temp'];
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
            mysqli_query($db, "DELETE FROM padporsc_bot4.team{$row['team_master_key']} WHERE user_id = {$row['temp']}");
            $ans = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$row['temp']}");
            $ras = mysqli_fetch_array($ans);
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'user_menu', team_master_key = NULL WHERE user_id = {$row['temp']}");
            mysqli_query($db, "UPDATE padporsc_bot4.users SET current_level = 'have_team_menu', temp = NULL WHERE user_id = {$user_id}");
            if ($locale == "farsi")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "کاربر حذف شد.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($locale == "english")
                makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "User have been kicked", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            echo $ras['locale'];
            if ($ras['locale'] == "farsi")
                makeCurl("sendMessage", ["chat_id" => $ras['user_id'], "text" => "این تیم جای تو نیست. دیگه توی این تیم نیستی و میتونی دنبال تیم جدید تری باشی و پیشرفت کنی.", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "ادامه", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
            elseif ($ras['locale'] == "english")
                makeCurl("sendMessage", ["chat_id" => $ras['user_id'], "text" => "This team is not your place anymore.Let's find new team and develope your mind(I means you're kicked 😶)", "reply_markup" => json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Continue", "callback_data" => "C0nT1nu3"]
                        ]
                    ]
                ])]);
        }
        elseif ($text == "NO0o0oOO0")
        {
            mysqli_query($db, "UPDATE padporsc_bot4.users SET temp = NULL WHERE user_id = {$user_id}");
            haveTeam(0);
        }
    }

}