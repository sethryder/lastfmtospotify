<?php

require 'src/Mustache/Autoloader.php';
require 'src/LastFM.php';
require 'src/Spotify.php';
require 'config.php';

Mustache_Autoloader::register();

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
));

if (mysqli_connect_errno($mysqli))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}