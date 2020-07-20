<?php
$testdum = "cek ricek";
$app->route('GET /auth/testing', function() {
    var_dump($testdum);
    setOut([
        'pageTitle' => 'Login &mdash; '.APP_NAME
    ]);

    render('aauth/login');
});

$app->route('GET /auth/login', function() {

    setOut([
        'pageTitle' => 'Login &mdash; '.APP_NAME
    ]);

    render('auth/login');
});

$app->route('POST /auth/login', function() {
    
    // var_dump(ApiHelper::encrypt(post()->password)); exit;
    v()->isNullOrEmpty(post()->email, 'Email atau password tidak ditemukan')
        ->isNullOrEmpty(post()->password, 'Email atau password tidak ditemukan');

    if (!v()->isValid)
    {
        setMessages([
            'isError' => true,
            'errorMessage' => 'Email atau password tidak ditemukan'
        ]);

        redirect('/auth/login');
    }
    else
    {
        // $getuser = db()->getWhere('user', 'username = :email', 
        //         ['email' => post()->email])
        //     ->getRow();

        $getuser = db()->query("SELECT * FROM user")->execute()->getRow();

        // var_dump($getuser); 
        // var_dump(post()->email); 
        $postPass = post()->password;
        
        // $users = db()->getWhere('user', 'username = :email AND password = :pass', 
        //         ['email' => post()->email, 'pass' => password_verify($postPass, $getuser->password)])
        //     ->getRow();
        $users = password_verify($postPass, $getuser->password);
        // var_dump($users); exit;
        
        if ($users == false)
        {
            setMessages([
                'isError' => true,
                'errorMessage' => 'Email atau password tidak ditemukan'
            ]);

            redirect('/auth/login');
        }
        else
        {   
            // if ($users ==0){
            //     setMessages([
            //         'isError' => true,
            //         'errorMessage' => 'Maaf anda belum melakukan verifikasi'
            //     ]);
            //     redirect('/auth/login');
            // }

            $_SESSION['users'] = (array) $getuser;
            
            redirect('dashboard/index');
        }
    }
});

$app->route('GET /auth/register', function() {

    setOut([
        'pageTitle' => 'Login &mdash; '.APP_NAME
    ]);

    render('auth/register');
});

$app->route('POST /auth/register', function() {

    v()->isNullOrEmpty(post()->name, 'Data masih kosong')
        ->isNullOrEmpty(post()->email, 'Data masih kosong')
        ->isNullOrEmpty(post()->confirmpassword, 'Data masih kosong')
        ->isNullOrEmpty(post()->password, 'Data masih kosong');        
    
    if (!v()->isValid)
    {
        setMessages([
            'isError' => true,
            'errorMessage' => 'Data masih kosong'
        ]);

        redirect('/auth/register');
    }
    if (post()->terms == ''){
        setMessages([
            'isError' => true,
            'errorMessage' => 'Mohon ceklis untuk menyetujui'
        ]);

        redirect('/auth/register');
    }
    
    if (v()->isValid){
        $users = db()->getAll('tbl_users', 'users_email = :email', 
                ['email' => post()->email]);
        
        if (count($users)>0){
            setMessages([
                'isSuccess' => true,
                'successMessage' => 'Email anda sudah terdaftar'
            ]);
            redirect('/auth/register');
        }
        
        $verifyToken = ApiHelper::getRandomString(32);
        $arrusers = array(
            'users_name'=>post()->name,
            'users_username'=>post()->name,
            'users_email'=>post()->email,
            'users_active'=>1,
            'users_role'=>'ADMIN',
            'users_hp'=>' ',
            'users_token'=>' ',
            'users_verifikasi'=>1,
            'users_email_notification'=>1,
            'users_created_at'=>ApiHelper::getDateComplete(),
            'users_reset_at'=>ApiHelper::getDateComplete(),
            'users_password'=>ApiHelper::encrypt(post()->confirmpassword),
            'users_reset_token'=>$verifyToken,
        );
        db()->insert('tbl_users', $arrusers);        

        $data = [
            'admin_fullname' => post()->name,
            'admin_verification_link' => WEB_URL.'auth/verificationadmin/'.$verifyToken,
            // 'admin_contact_email' => ApiHelper::getConfig('CONF_ADMIN_CONTACT_EMAIL')
        ];
        
        $email = [
            'subject' => 'Verifikasi Email Admin Baru KPR Hijrah',
            'toEmail' => post()->email,
            'toName' => post()->name,
            'htmlBody' => EmailFormat::registerAdmin($data),
            'actionType' => 'FORGOT PASSWORD'
        ];
        // ApiHelper::SG_SendApiAdminEmail($email, '../');

        setMessages([
            'isSuccess' => true,
            'successMessage' => 'Pendaftaran berhasil, silahkan verifikasi akun anda melalui link yang telah dikirimkan'
        ]);
        redirect('/auth/login');
    }

});

$app->route('GET /auth/forgotpassword', function() {

    setOut([
        'pageTitle' => 'Login &mdash; '.APP_NAME
    ]);

    render('auth/forgotpassword');
});

$app->route('POST /auth/forgotpassword', function() {
    
    $isExist = db()->getWhere('tbl_users', 'users_email = :uemail', ['uemail' => post()->users_email])->getRow();

    if ($isExist == null)
    {
        setMessages([
            'isError' => true,
            'errorMessage' => 'Email tidak ditemukan'
        ]);
    }
    else
    {
        $resetToken = ApiHelper::getRandomString(32);
        db()->update('tbl_users', ['users_reset_token' => $resetToken, 'users_reset_at' => ApiHelper::getDateComplete()])
            ->where('users_id = :uid', ['uid' => $isExist->users_id])->execute();

        $data = [
            'users_fullname' => $isExist->users_name,
            'users_reset_link' => WEB_URL.'auth/recoverpassword/'.$resetToken,
            'users_contact_email' => ApiHelper::getConfig('CONF_users_CONTACT_EMAIL')
        ];

        
        $email = [
            'subject' => 'Reset Password KPR Hijrah',
            'toEmail' => $isExist->users_email,
            'toName' => $isExist->users_name,
            'htmlBody' => EmailFormat::forgotPass($data),
            'actionType' => 'FORGOT PASSWORD'
        ];
        ApiHelper::SG_SendApiAdminEmail($email, '../');

        setMessages([
            'isSuccess' => true,
            'successMessage' => 'Reset password dikirimkan ke email'
        ]);
    }

    redirect('/auth/login');
});

$app->route('GET /auth/logout', function() {

    session_destroy();
    redirect('/auth/login');

});

$app->route('GET /auth/recoverpassword/@token', function($token) {

    $admin = db()->getWhere('tbl_admin', 'admin_reset_token = :token AND admin_reset_at - INTERVAL 3 HOUR < NOW()', ['token' => $token])->getRow();

    if ($admin == null) redirect('/auth/login');

    setOut([
        'resetToken' => $token,
        'pageTitle' => 'Set Password Baru &mdash; '.APP_NAME
    ]);

    render('auth/recoverpassword');
});

$app->route('GET /auth/verificationadmin/@token', function($token) {

    $admin = db()->getWhere('tbl_admin', 'admin_reset_token = :token', ['token' => $token])->getRow();

    if ($admin == null) redirect('/auth/login'); 

    post()->admin_reset_token = '';
    post()->admin_verified = 1;

    db()->update('tbl_admin', post())->where('admin_id = :uid', ['uid' => $admin->admin_id])->execute();

    setMessages([
        'isSuccess' => true,
        'successMessage' => 'Verifikasi email anda telah berhasil silahkan login untuk masuk ke halaman Dashboard'
    ]);

    redirect('auth/login');
});

$app->route('POST /auth/recoverpassword', function(){

    $admin = db()->getWhere('tbl_admin', 'admin_reset_token = :token AND admin_reset_at - INTERVAL 3 HOUR < NOW()', ['token' => post()->admin_reset_token])->getRow();

    if ($admin == null) redirect('/auth/login');

    v()->isNullOrEmpty(post()->admin_password, 'Password tidak boleh kosong')
        ->isNullOrEmpty(post()->confirm_password, 'Konfirmasi password tidak boleh kosong')
        ->not()->isEqual(post()->admin_password, post()->confirm_password, 'Password dan konfirmasi tidak sama');

    if (!v()->isValid)
    {
        setMessages([
            'isError' => true,
            'errorMessage' => v()->firstError
        ]);

        $_SESSION['data'] = post();

        redirect('/auth/recoverpassword/'.post()->admin_reset_token);
    }
    else
    {
        unset(post()->confirm_password);
        
        post()->admin_reset_token = '';
        post()->admin_reset_at = '1999-09-09 09:09:09';
        post()->admin_password = ApiHelper::encrypt(post()->admin_password);
        post()->admin_modified_at = ApiHelper::getDateComplete();

        db()->update('tbl_admin', post())->where('admin_id = :uid', ['uid' => $admin->admin_id])->execute();

        setMessages([
            'isSuccess' => true,
            'successMessage' => 'Password baru berhasil disimpan'
        ]);

        redirect('/auth/login');
    }
});

$app->route('POST /auth/reset_pass', function() {

    $isExist = db()->getWhere('tbl_admins', 'admin_email = :uemail', ['uemail' => post()->admin_email])->getRow();

    if ($isExist == null)
    {
        setMessages([
            'isError' => true,
            'errorMessage' => 'Email tidak ditemukan'
        ]);
    }
    else
    {
        $resetToken = ApiHelper::getRandomString(32);
        db()->update('tbl_admins', ['admin_reset_token' => $resetToken, 'admin_reset_at' => ApiHelper::getDateComplete()])
            ->where('admin_id = :uid', ['uid' => $isExist->admin_id])->execute();

        $data = [
            'admin_fullname' => $isExist->admin_fullname,
            'admin_reset_link' => WEB_URL.'auth/set_pass/'.$resetToken,
            'admin_contact_email' => ApiHelper::getConfig('CONF_ADMIN_CONTACT_EMAIL')
        ];

        $email = [
            'subject' => 'Reset Password Lelang Syariah',
            'toEmail' => $isExist->admin_email,
            'toName' => $isExist->admin_fullname,
            'htmlBody' => EmailFormat::forgotPass($data),
            'actionType' => 'FORGOT PASSWORD'
        ];
        ApiHelper::SG_SendApiAdminEmail($email, '../');

        setMessages([
            'isSuccess' => true,
            'successMessage' => 'Reset password dikirimkan ke email'
        ]);
    }

    redirect('/auth/login');
});

$app->route('GET /auth/set_pass/@token', function($token) {

    $admin = db()->getWhere('tbl_admins', 'admin_reset_token = :token AND admin_reset_at - INTERVAL 3 HOUR < NOW()', ['token' => $token])->getRow();

    if ($admin == null) redirect('/auth/login');

    setOut([
        'resetToken' => $token,
        'pageTitle' => 'Set Password Baru &mdash; '.APP_NAME
    ]);

    render('auth/set_pass');
});

$app->route('POST /auth/set_pass', function() {

    $admin = db()->getWhere('tbl_admins', 'admin_reset_token = :token AND admin_reset_at - INTERVAL 3 HOUR < NOW()', ['token' => post()->admin_reset_token])->getRow();

    if ($admin == null) redirect('/auth/login');

    v()->isNullOrEmpty(post()->admin_password, 'Password tidak boleh kosong')
        ->isNullOrEmpty(post()->admin_password2, 'Konfirmasi password tidak boleh kosong')
        ->not()->isEqual(post()->admin_password, post()->admin_password2, 'Password dan konfirmasi tidak sama');

    if (!v()->isValid)
    {
        setMessages([
            'isError' => true,
            'errorMessage' => v()->firstError
        ]);

        $_SESSION['data'] = post();

        redirect('/auth/set_pass/'.post()->token);
    }
    else
    {
        unset(post()->admin_password2);
        
        post()->admin_reset_token = '';
        post()->admin_reset_at = '1999-09-09 09:09:09';
        post()->admin_password = ApiHelper::encrypt(post()->admin_password);
        post()->modified_at = ApiHelper::getDateComplete();

        db()->update('tbl_admins', post())->where('admin_id = :uid', ['uid' => $admin->admin_id])->execute();

        setMessages([
            'isSuccess' => true,
            'successMessage' => 'Password baru berhasil disimpan'
        ]);

        redirect('/auth/login');
    }
});