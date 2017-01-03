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
function main()
{
    global $level;
    global $chat_id;
    global $text;
    global $username;
    global $message_id;
    $update = json_decode(file_get_contents("php://input"));
    if ($update->callback_query) {
        makeCurl("answerCallbackQuery", ["callback_query_id" => $update->callback_query->id]);
        $text = $update->callback_query->data;
        $chat_id = $update->callback_query->from->id;
        $username = $update->callback_query->from->first_name;
        $message_id = $update->callback_query->message->message_id;
    } else {
        $text = $update->message->text;
        $chat_id = $update->message->chat->id;
        $username = $update->message->from->first_name;
    }
}
main();
