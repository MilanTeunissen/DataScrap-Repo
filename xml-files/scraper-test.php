<?php

use voku\helper\HtmlDomParser;

require_once '../vendor/autoload.php';

$jsonCompanyData = file_get_contents(__DIR__.'/../xml-files/test-data/testJsonData.json');
$companyData = json_decode($jsonCompanyData, true);

$socials = [];
$tempList = [];


foreach ($companyData as $entry) {

    //functies om links in te kunnen opslaan voor de array
    $companyFacebook = 'not found';
    $companyInstagram = 'not found';
    $companyYoutube = 'not found';
    $companyTwitter = 'not found';
    $companyLinkedin = 'not found';

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

            $socials [] = array(
                "facebook" => $companyFacebook ?: '' ,
                "instagram" => $companyInstagram ?: '',
                "Youtube" => $companyYoutube ?: '',
                "Twitter" => $companyTwitter ?: '',
                "LinkedIn" => $companyLinkedin ?: ''
            );

        }
    }


$finalSocialsData = json_encode($socials, JSON_PRETTY_PRINT);
file_put_contents(__DIR__.'/../xml-files/test-data/testJsonDataSocials.json' , $finalSocialsData);
var_dump($finalSocialsData);
