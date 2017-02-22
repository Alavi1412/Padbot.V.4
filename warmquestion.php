<?php
/**
 * Created by PhpStorm.
 * User: alavi
 * Date: 2/22/17
 * Time: 3:45 PM
 */
function warmQuestion($i)                               //return warm up question TODO English Not added
{
    global $locale;
    global $db;
    global $user_id;
    global $text;
    $result = mysqli_query($db, "SELECT * FROM padporsc_bot4.users WHERE user_id = {$user_id}");
    $row = mysqli_fetch_array($result);
    if ($locale == "farsi") {
        if ($row['chosen'] != NULL)
        {
            switch ($row['chosen']) {
                case "1":
                    $str = "دوچرخه";
                    break;
                case "2":
                    $str = "پیاده روی";
                    break;
                case "3":
                    $str = "ماشین";
                    break;
                case "4":
                    $str = "مترو";
                    break;
                default:
                    $str = $row['chosen'];
            }
        }
        else
        {
            switch ($text) {
                case "1":
                    $str = "دوچرخه";
                    break;
                case "2":
                    $str = "پیاده روی";
                    break;
                case "3":
                    $str = "ماشین";
                    break;
                case "4":
                    $str = "مترو";
                    break;
                default:
                    $str = $text;
                    break;
            }
        }
    }
    if ($locale == "farsi")
        switch ($i) {
            case 1:
                return "۱. یه وسیله‌ای-چیزی که الان دور و برت میبینی چیه؟";
                break;
            case 2:
                return "۲. {$text} چه مشکلاتی میتونه به وجود بیاره؟ (هر چی تعداد مشکلاتی که مطرح میکنی بیشتر باشه بهتره!)";
                break;
            case  3:
                return "۳.{$row['word']} راه حل چه مشکلاتی میتونه باشه؟ (هر چی بیشتر بهتر!)";
                break;
            case 4:
                return "۴. با {$row['word']} چه جوری میشه آلودگی هوا رو زیاد کرد؟";
                break;
            case 5:
                return "۵. به نظرت چه جوری با {$row['word']} میشه به کاهش آلودگی هوا کمک کرد؟ حتی اگه راه فانتزی به ذهنت میرسه بگو ;)";
                break;
            case 6:
                return "۶. یکی از این گزینه ها رو انتخاب کن (فقط عدد گزینه): 1) دوچرخه     2) پیاده روی    3) ماشین    4) مترو";
                break;
            case 7:
                return "۷. با {$row['word']} چه جوری میشه به افزایش استفاده از {$str} کمک کرد؟";
                break;
            case 8:
                return "۸. با {$row['word']} چه جوری میشه به کاهش استفاده از {$str} کمک کرد؟";
                break;
            case 9:
                return "۹. خب حالا که ذهنت گرم شد؛ خداییش، به نظرت سهم تو در آلودگی هوای دور و برت چقدره؟ (امتیاز بین صفر(اصلا) تا ده  (تماما) بده).";
                break;
        }
}
