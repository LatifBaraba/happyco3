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
    $ossClient->getObject($bucket, $object[1]);
} catch(OssException $e) {
    printf(__FUNCTION__ . ": FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}
print(__FUNCTION__ . "<a href='../index.php'>index</a> | <a href=delete.php?".$object[1]. ">delete</a><br/>" . "\n");
print('<br/>');
//print(__FUNCTION__ . "<img width='100%' src=".$bucketdomain."/".$object[1] . "><br/>" . "\n");
print(__FUNCTION__ . "
<table>
    <tr>
        <th>Tumbnail</th>
        <th>Real Pic</th>
    </tr>
    <tr>
        <td><img width='100%' src=".$bucketdomain."/".$folder.$object[1] ."?". $style."></td>
        <td><img width='100%' src=".$bucketdomain."/".$object[1] . "></td>
    </tr>
</table>
\n");
//var_dump($object[1]);