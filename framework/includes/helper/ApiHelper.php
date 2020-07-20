<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ApiHelper
{
    public static function stdclasstoarray($arr){
        $array = json_decode(json_encode($arr), true);
        return $array;
    }

    public static function getSetting() {

        $setting = db()->select('*')->from('tbl_settings')->execute()->getRow();

        if ($setting != null)
        {
            if ($setting->setting_default_contact_phone == '' || $setting->setting_default_contact_phone == null)
                $setting->setting_default_contact_phone = '+628DUMMYPHONE';
    
            if ($setting->setting_default_contact_email == '' || $setting->setting_default_contact_email == null)
                $setting->setting_default_contact_email = 'mydummy@dumpmail.com';

            if ($setting->setting_facebook_url == '' || $setting->setting_facebook_url == null)
                $setting->setting_facebook_url = 'https://facebook.com/mydummypage';

            if ($setting->setting_instagram_url == '' || $setting->setting_instagram_url == null)
                $setting->setting_instagram_url = 'https://instagram.com/mydummypage';

            if ($setting->setting_twitter_url == '' || $setting->setting_twitter_url == null)
                $setting->setting_twitter_url = 'https://twitter.com/mydummypage';
        }
        else 
        {
            $setting = new stdClass();
            $setting->setting_default_contact_phone = '+628DUMMYPHONE';
            $setting->setting_default_contact_email = 'mydummy@dumpmail.com';
            $setting->setting_facebook_url = 'https://facebook.com/mydummypage';
            $setting->setting_instagram_url = 'https://instagram.com/mydummypage';
            $setting->setting_twitter_url = 'https://twitter.com/mydummypage';
        }
    
        return $setting;
    }

    public static function encrypt($string, $salt = SECURITY_SALT) 
    {
        $encrypt_method = 'AES-256-CBC';
        $secret_key = $salt;
        $secret_iv = 'chiper';

        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));;
    }

    public static function decrypt($string, $salt = SECURITY_SALT) 
    {
        $encrypt_method = 'AES-256-CBC';
        $secret_key = $salt;
        $secret_iv = 'chiper';

        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    public static function encryptID($string, $salt = SEC_SALT_ID)
    {
        return self::encrypt($string, $salt);
    }

    public static function decryptID($string, $salt = SEC_SALT_ID)
    {
        return self::decrypt($string, $salt);
    }

    public static function getRandomString($length = 10) 
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyz', ceil($length/strlen($x)) )), 1, $length);
    }

    public static function getConfig($confName) 
    {
        $conf = db()->getWhere('tbl_configurations', 'conf_name = :cname', ['cname' => $confName])->getRow();
        return $conf->conf_val;
    }

    public static function SG_SendApiAdminEmail($data, $path = '../') 
    {
        /*  
            $data['fromEmail', 'fromName', 'subject', 'toEmail', 'toName', 'textBody', 'htmlBody', 'attachment', 'actionType'];
        */
        include_once $path.'framework/includes/libraries/sendgrid-php/sendgrid-php.php';

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom(self::getConfig('CONF_ADMIN_SENDER_FROM_EMAIL'), self::getConfig('CONF_ADMIN_SENDER_FROM_NAME'));
        $email->setSubject($data['subject']);
        $email->addTo($data['toEmail'], $data['toName']);

        if (isset($data['textContent'])) $email->addContent("text/plain", $data['textBody']);

        $email->addContent("text/html", $data['htmlBody']);

        $sendgrid = new \SendGrid(self::getConfig('CONF_SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($email);
            $arrResponse = [
                'statusCode' => $response->statusCode(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ];

            $emailHistory = [
                'emailsendhis_provider' => 'SENDGRID API',
                'emailsendhis_is_sent' => 2,
                'emailsendhis_response' => json_encode($arrResponse),
                'emailsendhis_action_type' => $data['actionType'],
                'emailsendhis_to_email' => $data['toEmail'],
                'emailsendhis_subject' => $data['subject'],
                'emailsendhis_body' => $data['htmlBody'],
                'created_at' => ApiHelper::getDateComplete(),
                'created_by' => 'SYSTEM'
            ];

            db()->insert('tbl_email_send_history', $emailHistory);

        } catch (Exception $e) {


            // echo 'Caught exception: '. $e->getMessage() ."\n";
            // save to db to send later
        }
    }

    public static function SG_SendApiMemberEmail($data, $path = '../') 
    {
        /*  
            $data['fromEmail', 'fromName', 'subject', 'toEmail', 'toName', 'textBody', 'htmlBody', 'attachment', 'actionType'];
        */
        include_once $path.'framework/includes/libraries/sendgrid-php/sendgrid-php.php';

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom(self::getConfig('CONF_MEMBER_SENDER_FROM_EMAIL'), self::getConfig('CONF_MEMBER_SENDER_FROM_NAME'));
        $email->setSubject($data['subject']);
        $email->addTo($data['toEmail'], $data['toName']);

        if (isset($data['textContent'])) $email->addContent("text/plain", $data['textBody']);

        $email->addContent("text/html", $data['htmlBody']);

        $sendgrid = new \SendGrid(self::getConfig('CONF_SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($email);
            $arrResponse = [
                'statusCode' => $response->statusCode(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ];

            $emailHistory = [
                'emailsendhis_provider' => 'SENDGRID API',
                'emailsendhis_is_sent' => 2,
                'emailsendhis_response' => json_encode($arrResponse),
                'emailsendhis_action_type' => $data['actionType'],
                'emailsendhis_to_email' => $data['toEmail'],
                'emailsendhis_subject' => $data['subject'],
                'emailsendhis_body' => $data['htmlBody'],
                'created_at' => ApiHelper::getDateComplete(),
                'created_by' => 'SYSTEM'
            ];

            db()->insert('tbl_email_send_history', $emailHistory);

        } catch (Exception $e) {


            // echo 'Caught exception: '. $e->getMessage() ."\n";
            // save to db to send later
        }
    }

    public static function sendSmtpEmail($toAdress, $Subject, $body) 
    {
        include_once 'includes/libraries/PHPMailer/src/Exception.php';
        include_once 'includes/libraries/PHPMailer/src/PHPMailer.php';
        include_once 'includes/libraries/PHPMailer/src/SMTP.php';

        $host = isset($smtp_arr["host"]) ? $smtp_arr["host"] : ApiHelper::getConfig("CONF_SMTP_HOST");
        $port = isset($smtp_arr["port"]) ? $smtp_arr["port"] : ApiHelper::getConfig("CONF_SMTP_PORT");
        $username = isset($smtp_arr["username"]) ? $smtp_arr["username"] : ApiHelper::getConfig("CONF_SMTP_USERNAME");
        $password = isset($smtp_arr["password"]) ? $smtp_arr["password"] : ApiHelper::getConfig("CONF_SMTP_PASSWORD");
        $secure = isset($smtp_arr["secure"]) ? $smtp_arr["secure"] : ApiHelper::getConfig("CONF_SMTP_SECURE");
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->IsHTML(true);
        $mail->Host = $host;
        $mail->Port = $port;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = $secure;
        $mail->SMTPDebug = false;
        $mail->SetFrom(ApiHelper::getConfig('CONF_FROM_EMAIL'));
        $mail->FromName = ApiHelper::getConfig("CONF_FROM_NAME_".$langId);
        $mail->addAddress($toAdress);
        $mail->Subject = '=?UTF-8?B?'.base64_encode($Subject).'?=';
        $mail->MsgHTML($body);

        if (!$mail->send()) {
            return false;
        }
        return true;
    }

    public static function isJson($string) 
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public static function toIndoDate($date, $removeMicro = true)
    {
        // to format Agustus 10, 2015, jam 10:10:09
        $arrDate = explode('-', $date);
        $year = $arrDate[0];
        $month = $arrDate[1];
        $dayTime = $arrDate[2];
        $arrDay = explode(' ', $dayTime);
        $day = (int) $arrDay[0];

        $intMonth = (int) $month;
        $monthName = '';
        switch ($intMonth)
        {
            case 1: $monthName = 'Januari'; break;
            case 2: $monthName = 'Februari'; break;
            case 3: $monthName = 'Maret'; break;
            case 4: $monthName = 'April'; break;
            case 5: $monthName = 'Mei'; break;
            case 6: $monthName = 'Juni'; break;
            case 7: $monthName = 'Juli'; break;
            case 8: $monthName = 'Agustus'; break;
            case 9: $monthName = 'September'; break;
            case 10: $monthName = 'Oktober'; break;
            case 11: $monthName = 'November'; break;
            case 12: $monthName = 'Desember'; break;
        }
    
        if ($removeMicro)
            return $monthName.' '.$day.', '.$year.' jam '.substr($arrDay[1], 0, 8);
        else
            return $monthName.' '.$day.', '.$year.' jam '.$arrDay[1];
    }

    public static function toExpiredJSDate($sysDate)
    {
        // to format 'Dec 29, 2019 14:32:00';
        $time = substr($sysDate, 11, 8);
        $year = substr($sysDate, 0, 4);
        $month = substr($sysDate, 5, 2);
        $day = substr($sysDate, 8, 2);

        switch ($month) {
            case "1": $month = 'Jan'; break;
            case "2": $month = 'Feb'; break;
            case "3": $month = 'Mar'; break;
            case "4": $month = 'Apr'; break;
            case "5": $month = 'May'; break;
            case "6": $month = 'Jun'; break;
            case "7": $month = 'Jul'; break;
            case "8": $month = 'Aug'; break;
            case "9": $month = 'Sep'; break;
            case "10": $month = 'Oct'; break;
            case "11": $month = 'Nov'; break;
            case "12": $month = 'Dec'; break;
        }

        return $month.' '.$day.', '.$year.' '.$time;    
    }

    public static function cleanString($string) 
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
     
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public static function getDateComplete($format = 'Y-m-d H:i:s.u')
    {
        $t = microtime(true);
        $micro = sprintf('%06d',($t - floor($t)) * 1000000);
        $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );

        return $d->format('Y-m-d H:i:s.u');
    }

    public static function getCurlContent($url, $post = '', $curl_timeout = 120, $customHeader = null) 
    {
        $usecookie = __DIR__ . "/cookie.txt";
        $header[] = 'Content-Type: application/json';
        $header[] = "Accept-Encoding: gzip, deflate";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Accept-Language: en-US,en;q=0.8,id;q=0.6";
    
        if ($customHeader != null)
            $header = $customHeader;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        // curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, $curl_timeout);
    
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36");
    
        if ($post)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        $rs = curl_exec($ch);
    
        if(empty($rs)) 
        {
            curl_close($ch);
            return false;
        }
    
        curl_close($ch);
        return $rs;
    }

    public static function convertSysDateToDateEmail($sysDate) {
        // format "2019-12-31 16:07:49" to format 'Rabu, 29 Desember 2019, 14:32:00';
        $time = substr($sysDate, 11, 8);
        $year = substr($sysDate, 0, 4);
        $month = substr($sysDate, 5, 2);
        $day = substr($sysDate, 8, 2);

        switch ($month) {
            case "1": $monthName = 'Januari'; break;
            case "2": $monthName = 'Februari'; break;
            case "3": $monthName = 'Maret'; break;
            case "4": $monthName = 'April'; break;
            case "5": $monthName = 'Mei'; break;
            case "6": $monthName = 'Juni'; break;
            case "7": $monthName = 'Juli'; break;
            case "8": $monthName = 'Agustus'; break;
            case "9": $monthName = 'September'; break;
            case "10": $monthName = 'Oktober'; break;
            case "11": $monthName = 'November'; break;
            case "12": $monthName = 'Desember'; break;
        }

        $intSysDate = (int) str_replace(' ', '', str_replace('-', '', str_replace(':', '', $sysDate) ) );
        $datetime = DateTime::createFromFormat('YmdHis', $intSysDate);
        $dayName = $datetime->format('D');

        $dayIndoName = '';
        switch ($dayName) {
            case 'Mon': $dayIndoName = 'Senin'; break;
            case 'Tue': $dayIndoName = 'Selasa'; break;
            case 'Wed': $dayIndoName = 'Rabu'; break;
            case 'Thu': $dayIndoName = 'Kamis'; break;
            case 'Fri': $dayIndoName = 'Jumat'; break;
            case 'Sat': $dayIndoName = 'Sabtu'; break;
            case 'Sun': $dayIndoName = 'Ahad'; break;
        }

        return $dayIndoName.', '.$day.' '.$monthName.' '.$year.', '.$time;
    }

    public static function toRupiah($price)
    {
        return number_format($price + 0, 0, '', '.');
    }
}