<?php

/* START pathinfo */
/* 
echo __FILE__  . "<br>";
echo __DIR__ . "<br>";
// echo "<pre>";
print_r(pathinfo(__FILE__));
// echo "</pre>";
*/
/* END pathinfo */

/* START scandir */ 
/*
// $files = scandir(__DIR__);
// $files = scandir(__DIR__,SCANDIR_SORT_DESCENDING);
// $files = scandir(__DIR__,SCANDIR_SORT_ASCENDING);
// $files = scandir(__DIR__,SCANDIR_SORT_NONE);
// echo "<pre>";
//     print_r($files);
// echo "</pre>";
*/ 
/* END scandir */

/* START fread */
// $file = fopen('test.txt','r');
// echo fread($file,26);
// echo fread($file,15);
// echo fread($file,filesize('test.txt'));
/* END fread */

/* START fwrite */
// $file = fopen('test.txt','r+');
// echo fread($file,filesize('test.txt'));
// $write = fwrite($file,' this is my second text');
// echo $write; // return the bytes writen
/* END fwrite */

/* START fseek */
$file = fopen('test.txt','r+');
// for : wal@d elfissaou@ana
// fseek($file,3);
// fwrite($file,'i');
// fwrite($file,'i',SEEK_SET); // by default it 's seek set
// fseek($file,15);
// fwrite($file,'i');

// for : wal@d elfissaou@ana
fseek($file,3);
fwrite($file,'i');
fseek($file,11,SEEK_CUR);
fwrite($file,'i');
/* END fseek */