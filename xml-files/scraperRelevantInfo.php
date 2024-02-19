<?php /** @noinspection DuplicatedCode */

use voku\helper\HtmlDomParser;

require_once '../vendor/autoload.php';

$jsonCompanyData = file_get_contents(__DIR__.'/../xml-files/dataList.json');
$companyData = json_decode($jsonCompanyData, true);

$remaining = 1319;

$companyInformation = [];


foreach ($companyData as $company) {
    $companyInfo = [];
    if (isset($company['companyLink']) && filter_var($company['companyLink'], FILTER_VALIDATE_URL)) {
        $htmlContent = @file_get_contents($company['companyLink']);
        if ($htmlContent === false) {
            echo "Failed to get content from URL: " . $company['companyLink'] . "\n";
            continue;
        }

        try {
            $companyHtmlContent = HtmlDomParser::str_get_html($htmlContent);
        } catch (Exception $e) {
            echo "Failed to parse HTML content for URL: " . $company['companyLink'] . "\n";
            echo "Error message: " . $e->getMessage() . "\n";
            continue;
        }

        $companyHtmlContent = HtmlDomParser::str_get_html($htmlContent);

        foreach ($companyHtmlContent->find('script') as $element) {
            if (str_contains($element, 'Organization')) {
                $jsonContent = json_decode($element->innertext, true);

                if (isset($jsonContent['name'])) {
                    $companyInfo['name'] = $jsonContent['name'];
                    echo $jsonContent['name'] . " done moving to next company "."\n";
                } else {
                    echo "Name not found for current company. Moving to next company."."\n";
                }

                if (isset($jsonContent['id'])) {
                    $companyInfo['id'] = $jsonContent['id'];
                }

                if (isset($jsonContent['url'])) {
                    $companyInfo['url'] = $jsonContent['url'];
                }

                if (isset($jsonContent['logo'])) {
                    $companyInfo['logo'] = $jsonContent['logo'];
                }

                if (isset($jsonContent['sameAs'])) {
                    $companyInfo['socials'] = $jsonContent['sameAs'];
                }

                if (isset($jsonContent['contactPoint'])) {
                    $companyInfo['contactInformation'] = $jsonContent['contactPoint'];
                }

                if (isset($jsonContent['address'])) {
                    $companyInfo['address'] = $jsonContent['address'];
                }

                $remaining--;


                echo "company done ".$remaining." companies remaining" ."\n";

                $companyInformation[] = $companyInfo;
            }
        }
    }
}

$finalRelativeInfo = json_encode($companyInformation, JSON_PRETTY_PRINT);
file_put_contents(__DIR__.'/../xml-files/finalRelativeInfo.json' , $finalRelativeInfo);
print_r($companyInformation);
