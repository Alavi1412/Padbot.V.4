<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 8:41 AM
 */
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