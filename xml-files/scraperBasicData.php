<?php

use voku\helper\HtmlDomParser;
use voku\helper\XmlDomParser;

require_once '../vendor/autoload.php';

$xmlString = XmlDomParser::file_get_xml(__DIR__ . '/../xml-files/sitemap-acties.xml');
$xml = simplexml_load_string($xmlString);

$dataList = [];

foreach ($xml->url as $listing) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $listing->loc);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set timeout to 10 seconds
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2); // Use TLSv1.2

    $html = curl_exec($ch);
    if($html === false) {
        echo 'Curl error: '. curl_error($ch);
    } else {
        $htmlDom = HtmlDomParser::str_get_html($html);

        $dataList[] = array (
            "companyTitle" => $htmlDom->find('#store-logo', 0)->title,
            "companyLink" => 'https://'.$htmlDom->find('#store-topbar .right .link span',0)->plaintext,
            "companyLogo" => $htmlDom->find('#store-logo', 0)->src,
            "companyDescription" => $htmlDom->find('#over article p', 1)->plaintext
        );

        echo "Processed URL: " . $listing->loc . "\n";
    }

    curl_close($ch);
    sleep(1);
}

$dataListFinal = json_encode($dataList);
file_put_contents(__DIR__.'/../xml-files/dataList.json', $dataListFinal);
print_r($dataListFinal);