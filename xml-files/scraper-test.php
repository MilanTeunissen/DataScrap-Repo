<?php

use voku\helper\HtmlDomParser;

require_once '../vendor/autoload.php';

$jsonCompanyData = file_get_contents(__DIR__.'/../xml-files/test-data/testJsonData.json');
$companyData = json_decode($jsonCompanyData, true);

//array's aanmaken die later worden gebruikt
$socials = []; //hier komen de socials in vanuit $tempList
$tempList = []; //hier worden tijdelijk de socials in opgeslagen voordat ze naar de socials gaan
$dataList = []; //hier wordt de bedrijfs data in opgeslagen denk aan naam, website, logo en de beschrijving van actie.nl


//Dit komt allemaal in de foreach uit scraper.php hier gaan we de orginele data
$html = file_get_contents(__DIR__.'/../xml-files/test-data/actieTest.html');
$htmlDom = HtmlDomParser::str_get_html($html);

    $companyTitle = $htmlDom->find('#store-logo', 0)->title; //Name of the company

$dataList[$companyTitle] = array (

    "companyLink" => 'https://'.$htmlDom->find('#store-topbar .right .link span',0)->plaintext, //link naar de website van het bedrijf
    "companyLogo" => $htmlDom->find('#store-logo', 0)->src, //Logo van het bedrijf
    "companyDescription" => $htmlDom->find('#over article p', 1)->plaintext //beschrijving van het bedrijf

);


//buiten de foreach anders kan het programma de data nergens uit ophalen
$dataListFinal = json_encode($dataList);
file_put_contents(__DIR__.'/../xml-files/test-data/testJsonData.json', $dataListFinal);

$jsonCompanyData = file_get_contents(__DIR__.'/../xml-files/test-data/testJsonData.json');
$companyData = json_decode($jsonCompanyData, true);

foreach ($companyData as $entry) {

    //variables in de loop constant leeg maken om te verkomen dat bijvoorbeeld de facebook van lego.nl komt te staan bij albertheijn.nl
    $companyFacebook = '';
    $companyInstagram = '';
    $companyYoutube = '';
    $companyTwitter = '';
    $companyLinkedin = '';

    if (isset($entry['companyLink'])) {

        $htmlContent = file_get_contents($entry['companyLink']);

        $companyHtmlContent = HtmlDomParser::str_get_html($htmlContent);

            //in deze foreach wordt er gezocht naar een 'a' tag href waarbij gezocht wordt naar de link van de socials die ik nodig heb
            //er wordt gebruik gemaakt van de || (or) operator om verschillende varrianten te vinden
            //bij twitter is er rekening mee gehouden dat het voor sommige bedrijven al als 'X' er zou staan aangezien dat de nieuwe naam van het bedrijf is
            //hiermee is het mogelijk gemaakt om dit voor andere projecten te gebruiken
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
                if (str_contains($element->href, 'https://www.twitter') || str_contains($element->href, "https://www.Twitter")  || str_contains($element->href, "https://twitter")  || str_contains($element->href, "https://Twitter") || str_contains($element->href, "https://www.X") || str_contains($element->href, "https://www.x") || str_contains($element->href, "https://X") || str_contains($element->href, "https://x")) {
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
