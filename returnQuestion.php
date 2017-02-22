<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:46 PM
 */
function returnQuestion($b)              //input=question number * output=question string
{
    global $locale;
    $data = mysqli_connect("localhost","root","root","padporsc_questions");

    if($locale == "farsi")
    {
        $result = mysqli_query($data, "SELECT * from farsi_questions");
        for($i = 0 ; $i <$b ; $i++)
        {
            $row = mysqli_fetch_array($result);
        }
        return $row['question'];

    }
    elseif ($locale == "english")
    {
        $result = mysqli_query($data, "SELECT * from english_questions");
        for($i = 0 ; $i <$b ; $i++)
        {
            $row = mysqli_fetch_array($result);
        }
        return $row['question'];

    }
}