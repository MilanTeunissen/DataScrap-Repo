<?php

use voku\helper\HtmlDomParser;
use voku\helper\XmlDomParser;

require_once '../vendor/autoload.php';

$xmlString = XmlDomParser::file_get_xml(__DIR__ . '/../xml-files/sitemap-acties.xml');
$xml = simplexml_load_string($xmlString);

$dataList = [];

foreach ($xml->url as $listing) {

    $html = file_get_contents($listing->loc);

    $htmlDom = HtmlDomParser::str_get_html($html);

    $dataList[] = array (

        "companyTitle" => $htmlDom->find('#store-logo', 0)->title, //Name of the company
        "companyLink" => 'https://'.$htmlDom->find('#store-topbar .right .link span',0)->plaintext, //link to the company website
        "companyLogo" => $htmlDom->find('#store-logo', 0)->src, //Logo
        "companyDescription" => $htmlDom->find('#over article p', 1)->plaintext //description

    );

}

$dataListFinal = json_encode($dataList);
file_put_contents(__DIR__.'/../xml-files/dataList.json', $dataListFinal);
print_r($dataListFinal);



