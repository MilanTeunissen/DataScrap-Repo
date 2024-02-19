<?php

$jsonBasicData = file_get_contents(__DIR__.'/../xml-files/dataList.json');
$jsonRelativeInfo = file_get_contents(__DIR__.'/../xml-files/finalRelativeInfo.json');

$basicData = json_decode($jsonBasicData, true);
if ($basicData === null) {
    echo "Failed to decode JSON from dataList.json\n";
    exit(1);
}

$relativeInfo = json_decode($jsonRelativeInfo, true);
if ($relativeInfo === null) {
    echo "Failed to decode JSON from finalRelativeInfo.json\n";
    exit(1);
}

$mergedData = [];

foreach ($basicData as $basicCompany) {
    $mergedData[$basicCompany['name']] = $basicCompany;
}

foreach ($relativeInfo as $relativeCompany) {
    if (isset($mergedData[$relativeCompany['name']])) {
        $mergedData[$relativeCompany['name']] = array_merge($mergedData[$relativeCompany['name']], $relativeCompany);
    }
}

foreach ($mergedData as $key => $value) {
    $mergedData[$key]['companyLink'] = $value['companyLink'] ?? '';
    $mergedData[$key]['companyDescription'] = $value['companyDescription'] ?? '';
}

$mergedJson = json_encode(array_values($mergedData), JSON_PRETTY_PRINT);
file_put_contents(__DIR__.'/../xml-files/mergedData.json', $mergedJson);
print_r($mergedJson);