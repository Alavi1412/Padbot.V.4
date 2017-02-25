<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 5:43 PM
 */
function askMyFriend()
{
    global $user_id;
    global $message_id;
    global $locale;
    if ($locale == "farsi")
    {
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "فقط کافیه اینو به دوستت بفرستی"]);
        makeCurl("sendMessage", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "دوست من.
        با همراهی با این بات میتونی در حل مشکلات اطرافت کمک کنی و برای آن ها راهکار ارائه بدی تا ما برات عملیشون کنیم.
        @padporsbot", "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "بازگشت", "callback_data" => "C0nT1nu3"]
                ]
            ]
        ])]);
    }
    elseif ($locale == "english")
        makeCurl("editMessageText", ["chat_id" => $user_id, "message_id" => $message_id, "text" => "Just send it to your friend
        @padporsbot", "reply_markup" => json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "Return", "callback_data" => "C0nT1nu3"]
                ]
            ]
        ])]);
}