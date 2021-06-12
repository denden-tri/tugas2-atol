<?php
function encrypt($id, $passp, $iv){
    $method = "aes-128-cbc";
    openssl_encrypt($id,$method, $passp,0,$iv);
}

function decrypt($id, $passp, $iv){
    $method = "aes-128-cbc";
    openssl_decrypt($id,$method, $passp,0,$iv);
}