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
use OSS\Core\OssUtil;

require_once __DIR__.'/setid.php';

//file upload to oss
$today = date('ymd');
$uniqId = $today.'-'.uniqid().'-';
$object = $uniqId.($_FILES["fileToUpload"]['name']); 
$file = $_FILES["fileToUpload"]['tmp_name'];
$uploadFile = $file;

/**
 *  Step 1: Initiate a multipart upload event and obtain the uploadId.
 */
try{
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

    // An uploadId is returned. It is the unique identifier for a part upload event. You can initiate related operations (such as part upload cancelation and query) based on the uploadId.
    $uploadId = $ossClient->initiateMultipartUpload($bucket, $object);
} catch(OssException $e) {
    printf(__FUNCTION__ . ": initiateMultipartUpload FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}
print(__FUNCTION__ . ": initiateMultipartUpload OK" . "\n");
/*
 * Step 2: Upload parts.
 */
$partSize = 10 * 1024 * 1024;
$uploadFileSize = filesize($uploadFile);
$pieces = $ossClient->generateMultiuploadParts($uploadFileSize, $partSize);
$responseUploadPart = array();
$uploadPosition = 0;
$isCheckMd5 = true;
foreach ($pieces as $i => $piece) {
    $fromPos = $uploadPosition + (integer)$piece[$ossClient::OSS_SEEK_TO];
    $toPos = (integer)$piece[$ossClient::OSS_LENGTH] + $fromPos - 1;
    $upOptions = array(
        $ossClient::OSS_FILE_UPLOAD => $uploadFile,
        $ossClient::OSS_PART_NUM => ($i + 1),
        $ossClient::OSS_SEEK_TO => $fromPos,
        $ossClient::OSS_LENGTH => $toPos - $fromPos + 1,
        $ossClient::OSS_CHECK_MD5 => $isCheckMd5,
    );
	// MD5 check.
    if ($isCheckMd5) {
        $contentMd5 = OssUtil::getMd5SumForFile($uploadFile, $fromPos, $toPos);
        $upOptions[$ossClient::OSS_CONTENT_MD5] = $contentMd5;
    }
    try {
		// Upload parts.
        $responseUploadPart[] = $ossClient->uploadPart($bucket, $object, $uploadId, $upOptions);
    } catch(OssException $e) {
        printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} FAILED\n");
        printf($e->getMessage() . "\n");
        return;
    }
    printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} OK\n");
}
// $uploadParts is an array composed by the ETags and part number (PartNumber) of each part.
$uploadParts = array();
foreach ($responseUploadPart as $i => $eTag) {
    $uploadParts[] = array(
        'PartNumber' => ($i + 1),
        'ETag' => $eTag,
    );
}
/**
 * Step 3: Complete multipart upload.
 */
try {
	// You must provide all valid $uploadParts when you perform this operation. OSS verifies the validity of all parts one by one after it receives $uploadParts. After part verification is successful, OSS combines these parts into a complete object.
    $ossClient->completeMultipartUpload($bucket, $object, $uploadId, $uploadParts);
}  catch(OssException $e) {
    printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
    printf($e->getMessage() . "\n");
    return;
}
printf(__FUNCTION__ . ": completeMultipartUpload OK\n <br/>");
//printf(__FUNCTION__ . "<img src='".$bucketdomain."/".$object."?x-oss-process=style/testdemo' alt=".$bucketdomain."/".$object.">");
printf(__FUNCTION__ . "<a href='".$bucketdomain."/".$object."?x-oss-process=style/testdemo' target='_blank'>".$bucketdomain."/".$object."</a>\n");