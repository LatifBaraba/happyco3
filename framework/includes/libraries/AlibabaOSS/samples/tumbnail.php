<a href="../index.php">index</a><br/>
<?php
if (is_file(__DIR__ . '/../autoload.php')) {
    require_once __DIR__ . '/../autoload.php';
}
//if (is_file(__DIR__ . '/../vendor/autoload.php')) {
//    require_once __DIR__ . '/../vendor/autoload.php';
//}

use OSS\OssClient;
use OSS\Core\OssException;

require_once __DIR__.'/setid.php';

$src_bucket = $bucket;
$dst_bucket = $bucket;
$src_object = explode("?", $_SERVER['REQUEST_URI']);
$dst_object = $folder.$src_object[1];

try{
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
    
    // Initialize a multipart copy.
    $upload_id = $ossClient->initiateMultipartUpload($dst_bucket, $dst_object);

    $copyId = 1;
    // Copy the parts one-by-one.
    $eTag = $ossClient->uploadPartCopy( $src_bucket, $src_object[1], $dst_bucket, $dst_object,$copyId, $upload_id);
    $upload_parts[] = array(
        'PartNumber' => $copyId,
        'ETag' => $eTag,
    );

    // Complete the multipart copy.
    $result = $ossClient->completeMultipartUpload($dst_bucket, $dst_object, $upload_id, $upload_parts);
} catch(OssException $e) {
    printf(__FUNCTION__ . ": FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}
print(__FUNCTION__ . ": OK" . "\n");