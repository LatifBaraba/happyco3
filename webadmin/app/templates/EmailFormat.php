<?php

class EmailFormat {

    public static function forgotPass($data = [])
    {
        $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
        
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Lelang Syariah Admin Email</title>
        </head>
        
        <body style="margin:0px; background: #f8f8f8; ">
            <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; padding-bottom:30px;" align="center"><br/>
                                    <img src="https://akhirbucket.oss-ap-southeast-5.aliyuncs.com/rumahlelang/logo_lelang2.jpeg" alt="Lelang Syariah" style="border:none">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="padding: 40px; background: #fff;">
                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="border-bottom:1px solid #f6f6f6;">
                                        <h1 style="font-size:14px; font-family:arial; margin:0px; font-weight:bold;">Hallo '.($data['user_fullname'] ?? '').',</h1>
                                        <p style="margin-top:0px; color:#bbbbbb;">Instruksi untuk mengubah password.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0 30px 0;">
                                        <p style="color:#777">Permintaan untuk mengubah password telah dibuat. Jika anda tidak ingin mengubah password abaikan email ini. Jika ingin mengubah password tekan tombol dibawah ini (link reset ini berlaku 3 jam):</p>
                                        <center>
                                            <a href="'.($data['admin_reset_link'] ?? '').'" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #4fc3f7; border-radius: 60px; text-decoration:none;">Reset Password</a>
                                            <div>Atau</div>
                                            <br/>
                                            <small><a href="'.($data['admin_reset_link'] ?? '').'">'.($data['admin_reset_link'] ?? '').'</a></small>
                                        </center>
                                </tr>
                                <tr>
                                    <td style="border-top:1px solid #f6f6f6; padding-top:20px; color:#777">Jika tombol tidak bekerja, copy dan paste url diatas pada browser anda. Jika masih ada masalah hubungi '.($data['admin_contact_email'] ?? '').'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body>
        
        </html>';

        return $content;
    }

    public static function registerAdmin($data = []) : string
    {
        $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
        
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>KPR Hijrah Admin Email</title>
        </head>
        
        <body style="margin:0px; background: #f8f8f8; ">
            <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; padding-bottom:30px;" align="center"><br/>
                                    <img src="https://akhirbucket.oss-ap-southeast-5.aliyuncs.com/rumahlelang/logo_lelang2.jpeg" alt="Lelang Syariah" style="border:none">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="padding: 40px; background: #fff;">
                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="border-bottom:1px solid #f6f6f6;">
                                        <h1 style="font-size:14px; font-family:arial; margin:0px; font-weight:bold;">Hallo '.($data['admin_email'] ?? '').',</h1>
                                        <p style="margin-top:0px; color:#bbbbbb;">Instruksi untuk verifikasi email.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0 30px 0;">
                                        <p style="color:#777">Klik tombol verifikasi untuk menyelesaikan pendaftaran. Jika anda tidak ingin mendaftar abaikan email ini. (link verifikasi ini berlaku 3 jam):</p>
                                        <center>
                                            <a href="'.($data['admin_verification_link'] ?? '').'" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #4fc3f7; border-radius: 60px; text-decoration:none;">Verifikasi Email</a>
                                            <div>Atau</div>
                                            <br/>
                                            <small style="margin-top: 10px;"><a href="'.($data['admin_verification_link'] ?? '').'">'.($data['admin_verification_link'] ?? '').'</a></small>
                                        </center>
                                </tr>
                                <tr>
                                    <td style="border-top:1px solid #f6f6f6; padding-top:20px; color:#777">Jika tombol tidak bekerja, copy dan paste url diatas pada browser anda. Jika masih ada masalah hubungi '.($data['admin_contact_email'] ?? '').'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body>
        
        </html>';

        return $content;
    }

    public static function registerMember($data = []) : string
    {
        $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
        
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Lelang Syariah Admin Email</title>
        </head>
        
        <body style="margin:0px; background: #f8f8f8; ">
            <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; padding-bottom:30px;" align="center"><br/>
                                    <img src="https://akhirbucket.oss-ap-southeast-5.aliyuncs.com/rumahlelang/logo_lelang2.jpeg" alt="Lelang Syariah" style="border:none">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="padding: 40px; background: #fff;">
                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="border-bottom:1px solid #f6f6f6;">
                                        <h1 style="font-size:14px; font-family:arial; margin:0px; font-weight:bold;">Hallo '.($data['member_email'] ?? '').',</h1>
                                        <p style="margin-top:0px; color:#bbbbbb;">Instruksi untuk verifikasi email.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0 30px 0;">
                                        <p style="color:#777">Klik tombol verifikasi untuk menyelesaikan pendaftaran. Jika anda tidak ingin mendaftar abaikan email ini. (link verifikasi ini berlaku 3 jam):</p>
                                        <center>
                                            <a href="'.($data['member_verification_link'] ?? '').'" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #4fc3f7; border-radius: 60px; text-decoration:none;">Verifikasi Email</a>
                                            <div>Atau</div>
                                            <br/>
                                            <small style="margin-top: 10px;"><a href="'.($data['member_verification_link'] ?? '').'">'.($data['member_verification_link'] ?? '').'</a></small>
                                        </center>
                                </tr>
                                <tr>
                                    <td style="border-top:1px solid #f6f6f6; padding-top:20px; color:#777">Jika tombol tidak bekerja, copy dan paste url diatas pada browser anda. Jika masih ada masalah hubungi '.($data['admin_contact_email'] ?? '').'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body>
        
        </html>';

        return $content;
    }
    
    public static function updateMember($data = []) : string
    {
        $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
        
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Lelang Syariah Admin Email</title>
        </head>
        
        <body style="margin:0px; background: #f8f8f8; ">
            <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; padding-bottom:30px;" align="center"><br/>
                                    <img src="https://akhirbucket.oss-ap-southeast-5.aliyuncs.com/rumahlelang/logo_lelang2.jpeg" alt="Lelang Syariah" style="border:none">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="padding: 40px; background: #fff;">
                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="border-bottom:1px solid #f6f6f6;">
                                        <h1 style="font-size:14px; font-family:arial; margin:0px; font-weight:bold;">Hallo '.($data['member_email'] ?? '').',</h1>
                                        <p style="margin-top:0px; color:#bbbbbb;">Informasi perubahan data Anda.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0 30px 0;">
                                        <p style="color:#777">Klik tautan dibawah ini, untuk melihat perubahan informasi</p>
                                        <center>
                                            <a href="'.($data['member_verification_link'] ?? '').'" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #4fc3f7; border-radius: 60px; text-decoration:none;">Verifikasi Email</a>
                                            <div>Atau</div>
                                            <br/>
                                            <small style="margin-top: 10px;"><a href="'.($data['member_verification_link'] ?? '').'">'.($data['member_verification_link'] ?? '').'</a></small>
                                        </center>
                                </tr>
                                <tr>
                                    <td style="border-top:1px solid #f6f6f6; padding-top:20px; color:#777">Jika tombol tidak bekerja, copy dan paste url diatas pada browser anda. Jika masih ada masalah hubungi '.($data['admin_contact_email'] ?? '').'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body>
        
        </html>';

        return $content;
    }
    
}