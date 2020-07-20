<?php
if (is_file(__DIR__ . '/../../../framework/includes/libraries/AlibabaOSS/autoload.php')) {
    require_once __DIR__ . '/../../../framework/includes/libraries/AlibabaOSS/autoload.php';
}

use OSS\OssClient;
use OSS\Core\OssException;
use OSS\Core\OssUtil;

class AttachedfileConf
{
    const FILETYPE_NIK_DIREKTUR = 1;
    const FILETYPE_NPWP_DIREKTUR = 2;
    const FILETYPE_NIK_PENGURUS = 3;
    const FILETYPE_NPWP_PENGURUS = 4;
    const FILETYPE_AKTE_PENDIRIAN_PERUSAHAAN = 5;
    const FILETYPE_AKTE_PERUBAHAN_PERUSAHAAN = 6;
    const FILETYPE_PENGESAHAN_MENTERI_HUKUM_DAN_HAM_PERUSAHAAN = 7;
    const FILETYPE_NIB_PERUSAHAAN = 8;
    const FILETYPE_SURAT_IZIN_USAHA_PERUSAHAAN = 9;
    const FILETYPE_SURAT_KETERANGAN_DOMISILI_PERUSAHAAN = 10;
    const FILETYPE_NPWP_PERUSAHAAN = 11;
    const FILETYPE_TANDA_DAFTAR_PERUSAHAAN = 12; /*User profile original image*/
    const FILETYPE_REI_APERSI_PERUSAHAAN = 13;    /*User profile croped image*/
    const FILETYPE_SITE_PLAN_PROYEK = 14; /*Used for mobile and shop templates*/
    const FILETYPE_IMB_INDUK_PROYEK = 15; /* Used in category detail page */
    const FILETYPE_SERTIFIKAT_INDUK = 16;
    const FILETYPE_IZIN_LOKASI = 17;
    const FILETYPE_RENCANA_KOTA = 18;
    const FILETYPE_PIL_BANJIR_PROYEK = 19; /* Used in seller shop template page */
    const FILETYPE_BROSUR_DAN_PRICELIST_PROYEK = 20; /* Used in seller shop template page */
    const FILETYPE_COMPANY_PROFILE_PROYEK = 21;
    const FILETYPE_IZIN_UU_GANGGUAN_PROYEK = 22;
    const FILETYPE_REKOMENDASI_PLD_PDAM = 24;
    const FILETYPE_PBB_PROYEK = 25;
    const FILETYPE_LAPORAN_KEUANGAN = 26;
    const FILETYPE_REKENING_KORAN_KEUANGAN = 27;    
    const FILETYPE_SLIP_GAJI_INDIVIDU = 28;
    const FILETYPE_REKENING_KORAN_INDIVIDU = 29;
    const FILETYPE_PESANAN_INDIVIDU = 30;
    const FILETYPE_PBB_INDIVIDU = 31;
    const FILETYPE_LOKASI_INDIVIDU = 32;
    const FILETYPE_SURAT_NIKAH_INDIVIDU = 33;
    const FILETYPE_NIK_INDIVIDU = 34;
    const FILETYPE_NPWP_INDIVIDU = 35;
    const FILETYPE_NIK_INDIVIDU_PASANGAN = 36;
    const FILETYPE_NPWP_INDIVIDU_PASANGAN = 37;
    const FILETYPE_KK_INDIVIDU = 38;

    public static function saveImageOss($file,$namefile,$record_id,$type,$typefile,$folder){
        $accessKeyId = OSS_ACCESS_KEY_ID;
        $accessKeySecret = OSS_ACCESS_KEY_SECRET;
        $endpoint = OSS_ENDPOINT;
        $bucket = OSS_BUCKET;
        $bucketdomain = OSS_BUCKET_DOMAIN;
        $thumb = OSS_DROPZONE_THUMBNAIL;

        #region handle OSS upload
        $uniqId = date('YmdHis').'_'.uniqid().'_';
        $name = $namefile;
        // $name = $_FILES['file_cid']['name'];
        $object = ApiHelper::cleanString($name);
        $uploadFile = $file;
        $ossPath = 'kprhijrah/'.$folder.'/'.$uniqId.$object;
        /* save OSS Alibaba url to database */
        $fileUrl = $bucketdomain . '/' . $ossPath;

        //upload file image ot OSS

        /**
         *  Step 1: Initiate a multipart upload event and obtain the uploadId.
         */
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            // An uploadId is returned. It is the unique identifier for a part upload event. You can initiate related operations (such as part upload cancelation and query) based on the uploadId.
            $uploadId = $ossClient->initiateMultipartUpload($bucket, $ossPath);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": initiateMultipartUpload FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        //print(__FUNCTION__ . ": initiateMultipartUpload OK" . "\n");
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
                $responseUploadPart[] = $ossClient->uploadPart($bucket, $ossPath, $uploadId, $upOptions);
            } catch(OssException $e) {
                printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} FAILED\n");
                printf($e->getMessage() . "\n");
                return;
            }
            //printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} OK\n");
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
            $ossClient->completeMultipartUpload($bucket, $ossPath, $uploadId, $uploadParts);
        }  catch(OssException $e) {
            printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }

        /* insert to database*/
        $attach = [        
            'afile_record_id' => $record_id,
            'afile_type' => $type,
            'afile_typefile' => $typefile,
            'afile_name' => $namefile,
            'afile_display_order' => 1,
            'afile_url' => $fileUrl,
            'afile_path' => $ossPath
        ];
        $checkfile = db()->getWhere('tbl_attached_files', 'afile_record_id = :id and afile_type = :type', ['id' => $record_id,'type' => $type])->getRow();
        if (!empty($checkfile)){
            db()->update('tbl_attached_files', $attach)->where('afile_id = :uid', ['uid' => $checkfile->afile_id])->execute();
        }else{
            db()->insert('tbl_attached_files', $attach);
        }

        return 'success';
    }
    
    public static function saveImageFileOss($file,$namefile,$record_id,$type,$typefile,$folder){
        $accessKeyId = OSS_ACCESS_KEY_ID;
        $accessKeySecret = OSS_ACCESS_KEY_SECRET;
        $endpoint = OSS_ENDPOINT;
        $bucket = OSS_BUCKET;
        $bucketdomain = OSS_BUCKET_DOMAIN;
        $thumb = OSS_DROPZONE_THUMBNAIL;

        #region handle OSS upload
        $uniqId = date('YmdHis').'_'.uniqid().'_';
        $name = $namefile;
        // $name = $_FILES['file_cid']['name'];
        $object = ApiHelper::cleanString($name);
        $uploadFile = $file;
        $ossPath = 'kprhijrah/'.$folder.'/'.$uniqId.$object;
        /* save OSS Alibaba url to database */
        $fileUrl = $bucketdomain . '/' . $ossPath;

        //upload file image ot OSS

        /**
         *  Step 1: Initiate a multipart upload event and obtain the uploadId.
         */
        try{
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            // An uploadId is returned. It is the unique identifier for a part upload event. You can initiate related operations (such as part upload cancelation and query) based on the uploadId.
            $uploadId = $ossClient->initiateMultipartUpload($bucket, $ossPath);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": initiateMultipartUpload FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        //print(__FUNCTION__ . ": initiateMultipartUpload OK" . "\n");
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
                $responseUploadPart[] = $ossClient->uploadPart($bucket, $ossPath, $uploadId, $upOptions);
            } catch(OssException $e) {
                printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} FAILED\n");
                printf($e->getMessage() . "\n");
                return;
            }
            //printf(__FUNCTION__ . ": initiateMultipartUpload, uploadPart - part#{$i} OK\n");
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
            $ossClient->completeMultipartUpload($bucket, $ossPath, $uploadId, $uploadParts);
        }  catch(OssException $e) {
            printf(__FUNCTION__ . ": completeMultipartUpload FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }      
        
        $file = array('fileurl'=>$fileUrl, 'filepath'=>$ossPath);

        return $file;
    }

    public static function deleteOssImage($img){

        $ossClient = new OssClient(OSS_ACCESS_KEY_ID, OSS_ACCESS_KEY_SECRET, OSS_ENDPOINT);
        $ossClient->deleteObject(OSS_BUCKET, $img);

        return 'success';
    }
}