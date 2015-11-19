<?php

require 'main.php';

$username = $_GET['u'];
$page = $_GET['p'];
$data = array();

$last_fm = new LastFM('72fe81934c0a8b1e45950df864a9a442');
$spotify = new Spotify($mysql_host, $mysql_username, $mysql_password, $mysql_database);

$data['artists'] = $last_fm->get_artists($username, $page);
$total_pages = $last_fm->get_total_pages();

$data['spotify_uris'] = $spotify->look_up_artists($data['artists']);


if ($page > 1)
{
    $data['last_page'] = $page - 1;
}

if ($page < $total_pages)
{
    $data['next_page'] = $page + 1;
}

echo $m->render('run', $data);
