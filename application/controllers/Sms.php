<?php

$url =  'https://rest.nexmo.com/sms/json?api_key=f838e84c&api_secret=2Mt0bnqKJIBfhgSo&to=6281392788437&from="Kouvee Pet Shop"&text="Layanan+yang+anda+pesan+di+Kouvee+Pet+Shop+telah+SELESAI,+silahkan+lakukan+pembayaran"';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

echo "<pre>";
echo $response;
echo"<prev>";