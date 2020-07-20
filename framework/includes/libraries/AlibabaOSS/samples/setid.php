<?php
// It is highly risky to log on with AccessKey of an Alibaba Cloud account because the account has permissions on all the APIs in OSS. We recommend that you log on as a RAM user to access APIs or perform routine operations and maintenance. To create a RAM account, log on to https://ram.console.aliyun.com.
$accessKeyId = "LTAI4FiLiiCr9HoP23gxj477";
$accessKeySecret = "sxW9RrMkJ4dMsa6ZBOH3DkVY59rlfE";
// This example uses endpoint China East 1 (Hangzhou). Specify the actual endpoint based on your requirements.
$endpoint = "http://oss-ap-southeast-5.aliyuncs.com";
$bucket= "akhirbucket";
$bucketdomain = "https://akhirbucket.oss-ap-southeast-5.aliyuncs.com";
$style = "x-oss-process=image/auto-orient,1/resize,m_fill,w_200,h_200/quality,q_90/format,jpeg/watermark,text_VFVNQk5BSUw,color_ffffff,size_36,g_center,t_50,x_10,y_10";
$folder = "tumbnail/";