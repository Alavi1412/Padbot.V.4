<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:54 PM
 */
function score()                                //calculate the score
{
    global $user_id;
    global $db;
    global $message_id;
    global $locale;

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
    $xml = file_get_contents("https://padpors.com/users/{$username}/summary.json?api_key=61bb4efa6432469bf8b1e9ed1dc1f507558cffd01653ea3fc56f0ff09b13ff96&api_username=padpors");          //TODO don't commit API key
    $answer = json_decode($xml);
    if ($answer -> user_summary -> likes_received)
        $score = $answer -> user_summary -> likes_received;
    if ($row['logged_in'] == 1)
        $score = $score + 5;
    mysqli_query($db, "UPDATE padporsc_bot4.users SET score = {$score} WHERE user_id = {$user_id}");

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