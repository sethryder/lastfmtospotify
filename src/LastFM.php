<?php

class LastFM
{
    public $current_page = 1;
    public $total_pages = 1;
    public $total_artists = 0;
    public $limit = 50;
    private $base_url = 'http://ws.audioscrobbler.com/2.0/';
    private $api_key;

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    public function get_artists($username, $page, $limit=50)
    {
        $artists = array();

        $url = $this->base_url.'?method=library.getartists&api_key='.$this->api_key.'&user='.$username.'&page='.$page.'&format=json';

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);

        $raw_json_array[] = json_decode($data, true);

        $this->current_page = $raw_json_array['0']['artists']['@attr']['page'];
        $this->total_pages = $raw_json_array['0']['artists']['@attr']['totalPages'];
        $this->total_artists = $raw_json_array['0']['artists']['@attr']['totalPages'];

        foreach ($raw_json_array as $a)
        {
            foreach ($a['artists']['artist'] as $b)
            {
                $artists[] = $b['name'];
            }
        }

        return $artists;
    }

    public function get_total_pages()
    {
        return $this->total_pages;
    }
}