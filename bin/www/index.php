<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$method = $_SERVER['REQUEST_METHOD'];
$path   = $_SERVER['REQUEST_URI'];

$dummyJson = json_encode([
    'data' => []
]);

$cacheFile = __DIR__ . '/cache/method_test';

if (empty(file_get_contents($cacheFile))) {
    file_put_contents($cacheFile, $dummyJson);
}

$getJson = function() use ($cacheFile) {
    return json_decode(file_get_contents($cacheFile), true);
};

$saveJson = function($content) use ($cacheFile) {
    $content = json_encode($content);
    return file_put_contents($cacheFile, $content);
};

$result = '';

switch ($method) {

    case 'GET':
        $result = $getJson();
        break;

    case 'POST':
        $result = $getJson();

        $result['data'] = [
            'set' => [
                'name'  => 'test',
                'value' => 'test'
            ]
        ];

        $saveJson($result);

        break;

    case 'PATCH':

        $result = $getJson();

        $result['data']['set'] = [
            'name'  => 'test_patched',
            'value' => 'test_patched'
        ];

        $saveJson($result);

        break;

    case 'DELETE':

        $result = $getJson();

        unset($result['data']['set']);

        $saveJson($result);

        break;

    case 'CLEAR':
        $saveJson('');
        echo 'Cleanup successful';
        break;

}

header('Content-Type: application/json');

echo json_encode($result);

exit(0);