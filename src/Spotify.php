<?php

class Spotify
{
    public $base_url = 'https://api.spotify.com/v1/';
    private $mysql;


    public function __construct($host, $username, $password, $database)
    {
        $this->mysql = mysqli_connect($host, $username, $password, $database);
    }

    public function look_up_artists($artists)
    {
        $spotify_uris = array();

        foreach ($artists as $artist)
        {
            $cache_artist = $this->check_cache($artist);

            if ($cache_artist)
            {
                $spotify_uris[] = $cache_artist['spotify_uri'];
            }
            else
            {
                $search_friendly_artist = urlencode($artist);

                $url = $this->base_url.'search?q='.$search_friendly_artist.'&type=artist';

                $ch = curl_init();
                $timeout = 5;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $data = curl_exec($ch);
                curl_close($ch);

                $decoded_result = json_decode($data, true);

                if ($decoded_result['artists']['items'][0]['uri'])
                {
                    $spotify_uris[] = $decoded_result['artists']['items'][0]['uri'];
                    $this->add_artist_to_cache($artist, $decoded_result['artists']['items'][0]['uri']);
                }
            }
        }

        return $spotify_uris;
    }

    private function check_cache($search_artist)
    {
        $friendly_artist = mysqli_real_escape_string($this->mysql, $search_artist);

        $sql = "SELECT spotify_uri FROM artist_cache WHERE artist = '$friendly_artist' LIMIT 1";

        $res = mysqli_query($this->mysql,$sql);

        while ($row = mysqli_fetch_assoc($res)) {
            $artist = $row;
        }

        if ($artist)
        {
            return $artist;
        }
        else
        {
            return null;
        }
    }

    private function add_artist_to_cache($artist, $spotify_uri)
    {
        $friendly_artist = mysqli_real_escape_string($this->mysql, $artist);

        $sql = "INSERT INTO artist_cache (artist, spotify_uri) VALUES ('$artist', '$spotify_uri')";

        mysqli_query($this->mysql, $sql);
    }
}