<?php

require_once './vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

$url = 'https://samaphp.com';
$data = [];
$client = new Client(HttpClient::create(['timeout' => 60]));
$crawler = $client->request('GET', $url);

$scrapped = [];
// .view-content is a group of divs of .post
$data_list = $crawler->filter('.view-articles .view-content')->slice();
$data_list->filter('.post')->each(function (\Symfony\Component\DomCrawler\Crawler $post) {
    global $scrapped;
    // h3
    $title = $post->filter('h3')->text();
    // div span.submitted
    $submitted = $post->filter('span.submitted')->text();
    // Since article text is directly inside .post div beside h3 and div of span.submitted
    // we will delete H3 and div.

    // Delete H3 from .post
    $post->filter('h3')->each(function ($dddd) {
        foreach ($dddd as $ddddnode) {
            $ddddnode->parentNode->removeChild($ddddnode);
        }
    });

    // Delete div from .post
    $post->filter('div')->each(function ($dddd) {
        foreach ($dddd as $ddddnode) {
            $ddddnode->parentNode->removeChild($ddddnode);
        }
    });

    $text = $post->text();

    $scrapped[] = [
        'title' => $title,
        'date' => $submitted,
        'text' => $text,
    ];
});

print_r($scrapped);exit;
