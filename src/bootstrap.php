<?php

require_once __DIR__.'/../vendor/autoload.php';



/* [@tmp methods for testing only...]*/

function pl($mixed)
{
    echo "<br>";
    echo $mixed;
    echo "<br>";
}

function pr($mixed)
{
    echo "<br>";
    print_r($mixed);
    echo "<br>";
}

function vd($mixed)
{
    echo "<br>";
    var_dump($mixed);
    echo "<br>";
}
