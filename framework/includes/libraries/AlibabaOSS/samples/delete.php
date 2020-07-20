<a href="../index.php">index</a><br/>
<?php
if (is_file(__DIR__ . '/../autoload.php')) {
    require_once __DIR__ . '/../autoload.php';
}
//if (is_file(__DIR__ . '/../vendor/autoload.php')) {
  //  require_once __DIR__ . '/../vendor/autoload.php';
//}

use OSS\OssClient;
use OSS\Core\OssException;

require_once __DIR__.'/setid.php';

$object = explode("?", $_SERVER['REQUEST_URI']);

try{
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
    //var_dump(explode("?", $_SERVER['REQUEST_URI']));
    //var_dump($object[1]);
    $ossClient->deleteObject($bucket, $object[1]);
    $ossClient->deleteObject($bucket, $folder.$object[1]);
} catch(OssException $e) {
    printf(__FUNCTION__ . ": FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}
print(__FUNCTION__ . ": OK" . "\n");