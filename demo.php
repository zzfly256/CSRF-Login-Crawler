<?php
/**
 * CSRFLogin Demo
 * User: Rytia
 * Date: 2018/8/21
 * Time: 17:27
 */
require_once 'CSRFLogin.Class.php';
$demo = new CSRFLogin(
    "http://chengji.zjyz.org/student/index/login.html",
    "http://chengji.zjyz.org/student/index/login.html",
    "http://chengji.zjyz.org/student/index/index",
    "http://chengji.zjyz.org"
);
echo $demo->post("20200001", "*******");