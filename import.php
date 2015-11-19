<html>
    <head>
        <title>Last.fm to Spotify Follow Tool</title>
    </head>
<body>

<?php
$page = $_GET['page'];
$user = $_GET['user'];

$current_page = 1;
$page_count = 8;
$artist_array = array();
$artists = array();
$spotify_uris = array();

$raw_artists = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=library.getartists&api_key=72fe81934c0a8b1e45950df864a9a442&user=sethryder&page=$page&format=json");

$artist_array[] = json_decode($raw_artists, true);

/*while ($current_page <= $page_count)
{
    $raw_artists = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=library.getartists&api_key=72fe81934c0a8b1e45950df864a9a442&user=sethryder&page=$current_page&format=json");

    $artist_array[] = json_decode($raw_artists, true);

    $current_page++;

}
*/


foreach ($artist_array as $a)
{
    foreach ($a['artists']['artist'] as $b)
    {
        $artists[] = $b['name'];
    }
}

foreach ($artists as $artist)
{
    $search_friendly = urlencode($artist);
    $raw_search = file_get_contents("https://api.spotify.com/v1/search?q=$search_friendly&type=artist");

    echo "<p>Looking up $artist</p>";

    $decoded_result = json_decode($raw_search, true);

    $spotify_uris[] = $decoded_result['artists']['items'][0]['uri'];
}


?>
    <?php
        foreach ($spotify_uris as $uri)
        {
            echo '<p><iframe src="https://embed.spotify.com/follow/1/?uri='.$uri.'&size=detail&theme=light" width="300" height="56" scrolling="no" frameborder="0" style="border:none; overflow:hidden;" allowtransparency="true"></iframe></p>';
            echo '';
        }
    ?>
</body>
</html>




