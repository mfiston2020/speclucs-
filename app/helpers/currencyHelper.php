<?php

function format_money($money)
{
    if(!$money) {
        return "RWF 0.00";
    }

    $money = number_format($money, 2);

    if(strpos($money, '-') !== false) {
        $formatted = explode('-', $money);
        return "-RWF $formatted[1]";
    }

    return "RWF $money ";
}

function format_numbers($money)
{
    if(!$money) {
        return "0";
    }

    $money = number_format($money);

    if(strpos($money, '-') !== false) {
        $formatted = explode('-', $money);
        return " $formatted[1]";
    }

    return " $money ";
}

function format_values($money)
{
    if(!$money) {
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

function generateRandomString($length = 10) {
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
