<?php
require 'main.php';

$last = new LastFM('72fe81934c0a8b1e45950df864a9a442');
$bands = $last->get_artists('sethryder', 1);

print_r($bands);