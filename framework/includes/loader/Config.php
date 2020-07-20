<?php

$lifetime = 14400;
session_set_cookie_params($lifetime);
ini_set('session.gc_maxlifetime', $lifetime);

session_name('lhmclhpoudseio');
session_start();
session_regenerate_id();

const APP_NAME = 'Admin CoCreative';
define('JS_VER', uniqid());

const DBHOST = 'localhost';
// const DBHOST = 'kprhijra-dev-do-user-6053491-0.a.db.ondigitalocean.com';
// const DBHOST = 'db-mysql-dev-do-user-6997091-0.db.ondigitalocean.com';
// const DBNAME = 'kprhijrah_dev';
const DBNAME = 'cocrea_db';
// const DBUSER = 'root';
// const DBUSER = 'userdev';
const DBUSER = 'root';
const DBPORT = '8889';
// const DBPASS = '123456';
// const DBPASS = 'devme123';
const DBPASS = 'root';
const SSLMODE = 'Require';

define('DOC_ROOT_PUBLIC', $_SERVER['DOCUMENT_ROOT'].'/adminkpr/webpublic/');
// define('DOC_ROOT_ADMIN', $_SERVER['DOCUMENT_ROOT'].'/kprhijrah-core/webadmin/');
define('DOC_ROOT_ADMIN', $_SERVER['DOCUMENT_ROOT'].'/happyco3/webadmin/');

const SECURITY_SALT = 'x13mk2pcxb9d1a';
const SEC_SALT_ID = '74686174277320616c6c20796f75206e656564';

const RECAPTCHA_SITE_KEY = '6LdwcgkUAAAAAOPbrPkbXygpMyPDK5brVIV2zCe7';
const RECAPTCHA_SECRET_KEY = '6LdwcgkUAAAAAEdBmCSAF85z7UTjy68Us_XxlJxp';

const OSS_ACCESS_KEY_ID = 'LTAI4FiLiiCr9HoP23gxj477';
const OSS_ACCESS_KEY_SECRET = 'sxW9RrMkJ4dMsa6ZBOH3DkVY59rlfE';
const OSS_BUCKET = 'kprhijrah';
const OSS_BUCKET_DOMAIN = 'https://kprhijrah.oss-ap-southeast-5.aliyuncs.com';
const OSS_ENDPOINT = 'http://oss-ap-southeast-5.aliyuncs.com';
const OSS_DROPZONE_THUMBNAIL = '?x-oss-process=image/auto-orient,1/resize,m_pad,w_120,h_120/quality,q_850';