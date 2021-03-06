<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite864228999a8fa8c85d9dad74b3be5af
{
    public static $files = array (
        'fe81f4db7ca976f7e113f097866cf957' => __DIR__ . '/../..' . '/lib/BaseInterface.php',
        '3f8bdd3b35094c73a26f0106e3c0f8b2' => __DIR__ . '/../..' . '/lib/SendGrid.php',
        '0596c9fb5a9dba3cf8abc2fdaa05141c' => __DIR__ . '/../..' . '/lib/TwilioEmail.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SendGrid\\Stats\\' => 15,
            'SendGrid\\Mail\\' => 14,
            'SendGrid\\Contacts\\' => 18,
            'SendGrid\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SendGrid\\Stats\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/stats',
        ),
        'SendGrid\\Mail\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/mail',
        ),
        'SendGrid\\Contacts\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/contacts',
        ),
        'SendGrid\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/php-http-client/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite864228999a8fa8c85d9dad74b3be5af::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite864228999a8fa8c85d9dad74b3be5af::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
