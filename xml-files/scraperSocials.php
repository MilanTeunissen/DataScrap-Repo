<?php

use voku\helper\HtmlDomParser;

require_once '../vendor/autoload.php';

$jsonCompanyData = file_get_contents(__DIR__.'/../xml-files/test-data/testJsonData.json');
$companyData = json_decode($jsonCompanyData, true);

//array's aanmaken die later worden gebruikt
$companies = [];

foreach ($companyData as $company) {

    if (isset($company['companyLink'])) {

        $htmlContent = file_get_contents($company['companyLink']);

        $companyHtmlContent = HtmlDomParser::str_get_html($htmlContent);

        foreach ($companyHtmlContent->find('a') as $element) {
            if (str_contains($element->href, "https://www.facebook") || str_contains($element->href, "https://www.Facebook")|| str_contains($element->href, "https://facebook")  || str_contains($element->href, "https://Facebook")) {
                $companyFacebook = $company['facebook'] = $element->href;
            }
            if (str_contains($element->href, "https://www.instagram") || str_contains($element->href, "https://www.Instagram") || str_contains($element->href, "https://instagram") || str_contains($element->href, "https://Instagram")) {
                $companyInstagram = $company['instagram'] = $element->href;
            }
            if (str_contains($element->href, 'https://www.youtube') || str_contains($element->href, "https://www.Youtube") || str_contains($element->href, "https://youtube") || str_contains($element->href, "https://Youtube")) {
                $companyYoutube = $company['youtube'] = $element->href;
            }
            if (str_contains($element->href, 'https://www.twitter') || str_contains($element->href, "https://www.Twitter")  || str_contains($element->href, "https://twitter")  || str_contains($element->href, "https://Twitter") || str_contains($element->href, "https://www.X") || str_contains($element->href, "https://www.x") || str_contains($element->href, "https://X") || str_contains($element->href, "https://x")) {
                $companyTwitter = $company['twitter'] = $element->href;
            }
            if (str_contains($element->href, 'https://www.linkedin')  || str_contains($element->href, "https://www.Linkedin")  || str_contains($element->href, "https://linkedin")  || str_contains($element->href, "https://Linkedin")) {
                $companyLinkedin = $company['linkedin'] = $element->href;
            }

        }

        sleep(1);

        echo "Processed company: " . $company['companyTitle'] . "\n";

        $companies [] = $company;

    }
}

$finalSocialsData = json_encode($companies, JSON_PRETTY_PRINT);
file_put_contents(__DIR__.'/../xml-files/test-data/testJsonDataSocials.json' , $finalSocialsData);
print_r($finalSocialsData);