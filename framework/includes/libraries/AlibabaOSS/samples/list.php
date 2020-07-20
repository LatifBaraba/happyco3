<!--<a href="../index.php">index</a><br/>-->
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

$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

//foreach()

while (true) {
    try {
        //$listObjectInfo = $ossClient->listObjects($bucket, $options);
        $listObjectInfo = $ossClient->listObjects($bucket);
    } catch (OssException $e) {
        printf(__FUNCTION__ . ": FAILED\n");
        printf($e->getMessage() . "\n");
        return;
    }
    // Obtain the nextMarker. Obtain the object list from the object next to the last object read by listObjects.
    $nextMarker = $listObjectInfo->getNextMarker();
    $listObject = $listObjectInfo->getObjectList();
    $listPrefix = $listObjectInfo->getPrefixList();

    if (! empty($listObject)) {
        //print("objectList:<br/>\n");
        print("
        <table border='1' class='table table-responsive table--hovered'>
            <thead>
                <tr>
                    <th>Tumbnail </th>
                    <th>URL </th>
                    <th>Action Buttons </th>
                <tr>\n");
            foreach ($listObject as $objectInfo) {
                //print("<a href=".$bucketdomain."/".$objectInfo->getKey() . " target='_blank'><img width='25%' src=".$bucketdomain."/".$objectInfo->getKey() . "><br/>".$bucketdomain."/".$objectInfo->getKey() . "</a> | <a href=samples/delete.php?".$objectInfo->getKey() . ">delete</a> | <a href=samples/select.php?".$objectInfo->getKey() . ">detail</a><br/>\n");
                print("
                <tr>
                    <td><img src=".$bucketdomain."/".$objectInfo->getKey() ."?". $style ."></td>
                    <td><a href=".$bucketdomain."/".$objectInfo->getKey() . " target='_blank'>".$bucketdomain."/".$objectInfo->getKey() . "</a></td>
                    <td>
                        <a href=samples/delete.php?".$objectInfo->getKey().">delete</a> ||
                        <a href=samples/tumbnail.php?".$objectInfo->getKey().">create tumbnail</a> ||
                        <a href=samples/select.php?".$objectInfo->getKey().">detail</a>
                    </td>
                </tr>\n");
            }
        print("</table>\n");
    }
    /*if (! empty($listPrefix)) {
        print("prefixList: \n");
        foreach ($listPrefix as $prefixInfo) {
            print($prefixInfo->getPrefix() . "\n");
        }
    }*/
    if ($nextMarker === '') {
        break;
    }
break;
}