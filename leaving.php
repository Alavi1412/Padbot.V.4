<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:52 PM
 */
function leaving()                  //used when user want to leave a team
{
    global $user_id;
    global $db;
    global $text;
    global $message_id;
    global $locale;
    if ($text == "Y3SESS")
    {

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
        $result4 = mysqli_query($db, "SELECT * FROM padporsc_bot4.team{$row['team_master_key']}");
        $e = 0;
        while ($row4 = mysqli_fetch_array($result4))
        {
            $e = 1;
        }
        if ($e == 0)
        {
            mysqli_query($db, "DROP TABLE padporsc_bot4.team{$row['team_master_key']}");
            mysqli_query($db, "DELETE FROM padporsc_bot4.teams WHERE master_key = {$row['team_master_key']}");
        }
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

    }
    elseif ($text == "NO0OOOoo")
        haveTeam(0);
}