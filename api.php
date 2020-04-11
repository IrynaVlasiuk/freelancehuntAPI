<?php

require_once './config.php';

function curlApi($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '. API_TOKEN,
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $curl_response = curl_exec($curl);

    curl_close($curl);

    $data = json_decode($curl_response)->data;
    $result = [];

    foreach ($data as $d) {
        array_push($result, $d->attributes, $d->links);
    }

    return $result;
}

function privatBankApi()
{
    $curl = curl_init(API_URL_PRIVATBANK);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    curl_close($curl);

    return json_decode($curl_response);
}

function converterToUAN($projects)
{
    $currencyСonverter = privatBankApi();
    $arrayBudgetsUAN = [];
    foreach ($projects as $p) {
        if($p->budget->currency == 'UAH') {
            array_push($arrayBudgetsUAN, $p->budget->amount);
        } else {
            foreach ($currencyСonverter as $c) {
                if($p->budget->currency == $c->ccy && $c->base_ccy == 'UAN') {
                    $amountUAN = (int)$p->budget->amount * $c->buy;
                    array_push($arrayBudgetsUAN, $amountUAN);
                } elseif ($p->budget->currency == 'RUB' && $c->ccy == 'RUR') {
                    $amountUAN = (int)$p->budget->amount * $c->buy;
                    array_push($arrayBudgetsUAN, $amountUAN);
                }
            }
        }
    }

    return $arrayBudgetsUAN;
}




