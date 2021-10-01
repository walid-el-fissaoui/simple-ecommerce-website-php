<?php

// setcookie('walid','content',time()+3600,'/','localhost',TRUE,TRUE); IN THIS CASE I DON'T HAVE ANY COOKIE BECAUSE MY CONNECTION IS NOT SECURE (NOT HTTPS)

setcookie('test2','content',time()+3600,'/','localhost',FALSE,TRUE);

if(count($_COOKIE) > 0)
{
    echo "cookies enabled";
}
else
{
    echo "cookies disabled";
}

// print array 
// print_r($_COOKIE['walid']);
print_r($_COOKIE);