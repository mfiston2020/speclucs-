<?php

use App\Models\CompanyInformation;

function format_money($money)
{
    $currency   =   getuserCompanyInfo()->currency=='USD'?'$':getuserCompanyInfo()->currency;
    if (!$money) {
        return $currency." 0.00";
    }

    $money = number_format($money, 2);

    if (strpos($money, '-') !== false) {
        $formatted = explode('-', $money);
        return "-".$currency." $formatted[1]";
    }

    return $currency." $money ";
}

function format_numbers($money)
{
    if (!$money) {
        return "0";
    }

    $money = number_format($money);

    if (strpos($money, '-') !== false) {
        $formatted = explode('-', $money);
        return " $formatted[1]";
    }

    return " $money ";
}

function format_values($money)
{
    if (!$money) {
        return "0.00";
    }

    $money = number_format($money, 2);

    return "$money";
}

function initials(string $name)
{
    $words = explode(' ', $name);
    return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
}

function initials2(string $name)
{
    $words = explode(' ', $name);
    return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
}

function Oneinitials(string $name)
{
    $words = explode(' ', $name);
    return strtoupper(substr($words[0], 0, 1));
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function userInfo()
{
    return auth()->user();
}

function makeMonths()
{
    $months =   [];
    for ($x = 1; $x <= 12; $x++)
        array_push($months, date('F', mktime(0, 0, 0, $x, 10)));

    return $months;
}

function getuserType()
{
    return userInfo()->permissions;
}

function getuserCompanyInfo()
{
    return CompanyInformation::find(userInfo()->company_id);
}

function compareArrayValues($lastValue, $currentValue)
{
    if ($currentValue == $lastValue) {
        dd(true);
    } else {
        return false;
    }
}

function numberToWord($num)
{
    $num    = (string) ((int) $num);

    if ((int) ($num) && ctype_digit($num)) {
        $words  = array();

        $num    = str_replace(array(',', ' '), '', trim($num));

        $list1  = array(
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
            'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
            'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );

        $list2  = array(
            '', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
            'seventy', 'eighty', 'ninety', 'hundred'
        );

        $list3  = array(
            '', 'thousand', 'million', 'billion', 'trillion',
            'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion',
            'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion',
            'octodecillion', 'novemdecillion', 'vigintillion'
        );

        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num    = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        foreach ($num_levels as $num_part) {
            $levels--;
            $hundreds   = (int) ($num_part / 100);
            $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
            $tens       = (int) ($num_part % 100);
            $singles    = '';

            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int) ($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_part % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
        }
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $words  = implode(', ', $words);

        $words  = trim(str_replace(' ,', ',', ucwords($words)), ', ');
        if ($commas) {
            $words  = str_replace(',', ' and', $words);
        }

        return $words;
    } else if (!((int) $num)) {
        return 'Zero';
    }
    return '';
}

function dateDiffInDays($date1, $date2)
{

    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
    $datediff = $date1 - $date2;

    return round($datediff / (60 * 60 * 24));
}

function lensDescription($description){
    $desc   =   null;
    if (str_starts_with($description,'BB')) {
        $desc   =   str_replace('BB','Bifocal',$description);
    }
    elseif (str_starts_with('BT',$description)) {
        $desc   =   str_replace('BT','Bifocal Round Top',$description);
    }
    elseif (str_starts_with($description,'PP')) {
        $desc   =   str_replace('PP','PROG',$description);
    }else{
        $desc   =   $description;
    }
    return $desc;
}
