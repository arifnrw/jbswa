<?php

include('../../include/system.config.php');
include('../../include/school.config.php');
include('../../include/database.config.php');

// nama sekolah diambil dari JUDUL_DEPAN_1
$sekolah = trim($G_JUDUL_DEPAN_1);

// total maksimal pesan dan dikirim per menit yang diambil dari outbox
$cronLimit = 30;

function sendwa($phone, $message, $base_url, $token){
    $url = $base_url . 'api/send_express';
    $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        'token'    => $token,
        'number'     => $phone,
        'message'   => $message,
        ));
    // curl_setopt($curl, CURLOPT_HTTPHEADER,'Content-Type: application/x-www-form-urlencoded');


    $response = curl_exec($curl); 
    curl_close($curl);
    return $response;
}

function sendbutton($phone, $body, $footer, $idb, $button, $base_url, $token){
    
    $url = $base_url . 'api/send_button';
    $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        'token'    => $token,
        'number'     => $phone,
        'contenttext'   => $body,
        'footertext' => $footer,
        'buttonid' => $idb,
        'buttontext' => $button
        ));
    // curl_setopt($curl, CURLOPT_HTTPHEADER,'Content-Type: application/x-www-form-urlencoded');


    $response = curl_exec($curl); 
    curl_close($curl);
    return $response;
}

function sendlist($phone, $body, $button, $section, $title, $subtitle, $idl, $base_url, $token){
    
    $url = $base_url . 'api/send_listmessage';
    $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        'token'    => $token,
        'number'     => $phone,
        'description'   => $body,
        'sectiontitle' => $section,
        'buttontext'   => $button,
        'title'     => $title,
        'descriptionlist' => $subtitle,
        'rowid' => $idl
        
        ));
    // curl_setopt($curl, CURLOPT_HTTPHEADER,'Content-Type: application/x-www-form-urlencoded');


    $response = curl_exec($curl); 
    curl_close($curl);
    return $response;
}

function hapuskoma($text){
    return str_replace(",", "", $text);
}

function rupiah($angka){
	$hasil_rupiah = "Rp" . number_format($angka,0,',','.');
	return $hasil_rupiah;
}