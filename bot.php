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
$db=mysqli_connect("localhost","root","root","padporsc_bot4");

require "recognize.php";

require "level_finder.php";

require "intro.php";

require "firststep.php";

require "got_email.php";

require "entrance.php";

require "getting_master_key.php";

require "answering_entrance_question.php";

require "continue_handler.php";

require "question_menu.php";

require "question_handling.php";

require "warm.php";

require  "warmquestion.php";

require "returnQuestion.php";

require "askQuestion.php";

require "userMenu.php";

require "team.php";

require "haveTeam.php";

require "kick.php";

require "teamChangeName.php";

require "leaving.php";

require "makeQuestion.php";

require "score.php";

require "info.php";

require "account.php";

require "myEmail.php";

require "changeLang.php";

require "startAsking.php";

require "answering.php";

require "send.php";

require  "askMyFriend.php";

require "comment.php";

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
            elseif ($text == "my_3ma1L")
                myEmail(0);
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
            elseif ($level == "changing_name_team")
                changeName(1);
            elseif ($level == "kick")
                kick(1);
            elseif ($level == "make_sure")
                kick(2);
            elseif ($level == "warming_up")
                warm(1);
            elseif ($level == "commenting")
                comment(1);
            $last_updated_id = $update->update_id;              //should be removed
        }           //should be removed
    }               //should be removed
}

while(1) {
    main();
}
