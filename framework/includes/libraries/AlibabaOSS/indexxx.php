<?php
if (is_file(__DIR__ . 'autoload.php')) {
    require_once __DIR__ . 'autoload.php';
}
if (is_file(__DIR__ . 'vendor/autoload.php')) {
    require_once __DIR__ . 'vendor/autoload.php';
}

use OSS\OssClient;
use OSS\Core\OssException;

// It is highly risky to log on with AccessKey of an Alibaba Cloud account because the account has permissions on all the APIs in OSS. We recommend that you log on as a RAM user to access APIs or perform routine operations and maintenance. To create a RAM account, log on to https://ram.console.aliyun.com.
$accessKeyId = "LTAI4FiLiiCr9HoP23gxj477";
$accessKeySecret = "sxW9RrMkJ4dMsa6ZBOH3DkVY59rlfE";
// This example uses endpoint China (Hangzhou). Specify the actual endpoint based on your requirements.
$endpoint = "http://oss-ap-southeast-5.aliyuncs.com";
// Bucket name
$bucket= "akhirbucket";
// Object name
$object = "test.txt";
// Object content
$content = "Hello OSS";
try{
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

    $ossClient->putObject($bucket, $object, $content);
} catch(OssException $e) {
    printf(__FUNCTION__ . ": FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}
print(__FUNCTION__ . ": OK" . "\n");