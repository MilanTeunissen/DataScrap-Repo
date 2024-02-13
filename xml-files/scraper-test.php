<?php

use voku\helper\HtmlDomParser;

require_once '../vendor/autoload.php';

$jsonCompanyData = file_get_contents(__DIR__.'/../xml-files/test-data/testJsonData.json');
$companyData = json_decode($jsonCompanyData, true);

$socials = [];
$tempList = [];
$dataList = [];


//Dit komt allemaal in de foreach uit scraper.php hier gaan we de orginele data
$html = file_get_contents(__DIR__.'/../xml-files/test-data/actieTest.html');
$htmlDom = HtmlDomParser::str_get_html($html);

    $companyTitle = $htmlDom->find('#store-logo', 0)->title; //Name of the company

$dataList[$companyTitle] = array (

    "companyLink" => 'https://'.$htmlDom->find('#store-topbar .right .link span',0)->plaintext, //link to the company website
    "companyLogo" => $htmlDom->find('#store-logo', 0)->src, //Logo
    "companyDescription" => $htmlDom->find('#over article p', 1)->plaintext //description

);

foreach ($companyData as $entry) {

    //functies om links in te kunnen opslaan voor de array
    $companyFacebook = '';
    $companyInstagram = '';
    $companyYoutube = '';
    $companyTwitter = '';
    $companyLinkedin = '';

    if (isset($entry['companyLink'])) {

        $htmlContent = file_get_contents($entry['companyLink']);

        $companyHtmlContent = HtmlDomParser::str_get_html($htmlContent);

            foreach ($companyHtmlContent->find('a') as $element) {
                if (str_contains($element->href, "https://www.facebook") || str_contains($element->href, "https://www.Facebook")|| str_contains($element->href, "https://facebook")  || str_contains($element->href, "https://Facebook")) {
                    $companyFacebook = $tempList['facebook'] = $element->href;
                }
                if (str_contains($element->href, "https://www.instagram") || str_contains($element->href, "https://www.Instagram") || str_contains($element->href, "https://instagram") || str_contains($element->href, "https://Instagram")) {
                    $companyInstagram = $tempList['instagram'] = $element->href;
                }
                if (str_contains($element->href, 'https://www.youtube') || str_contains($element->href, "https://www.Youtube") || str_contains($element->href, "https://youtube") || str_contains($element->href, "https://Youtube")) {
                    $companyYoutube = $tempList['youtube'] = $element->href;
                }
                if (str_contains($element->href, 'https://www.twitter') || str_contains($element->href, "https://www.Twitter")  || str_contains($element->href, "https://twitter")  || str_contains($element->href, "https://Twitter")) {
                    $companyTwitter = $tempList['twitter'] = $element->href;
                }
                if (str_contains($element->href, 'https://www.linkedin')  || str_contains($element->href, "https://www.Linkedin")  || str_contains($element->href, "https://linkedin")  || str_contains($element->href, "https://Linkedin")) {
                    $companyLinkedin = $tempList['linkedin'] = $element->href;
                }

            }

            $socials [$companyTitle] = array(
                "facebook" => $companyFacebook,
                "instagram" => $companyInstagram,
                "Youtube" => $companyYoutube,
                "Twitter" => $companyTwitter,
                "LinkedIn" => $companyLinkedin
            );

        }
    }



$dataListMerge = array_merge_recursive($dataList, $socials);

$finalSocialsData = json_encode($dataListMerge, JSON_PRETTY_PRINT);
file_put_contents(__DIR__.'/../xml-files/test-data/testJsonDataSocials.json' , $finalSocialsData);
print_r($finalSocialsData);
