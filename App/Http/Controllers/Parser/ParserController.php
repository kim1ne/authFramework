<?php

namespace App\Http\Controllers\Parser;

use App\Http\Parser\Parser;
use Zend\Diactoros\ServerRequestFactory;

class ParserController
{
    public function index()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://example.com']);
        $response = $client->request('GET');
        debug($response->getBody()->getContents());

        die;

        return view('parser/index', ['title'=> 'Парсинг']);
    }

    public function query()
    {
        $post = ServerRequestFactory::fromGlobals()->getParsedBody();
        if (empty($post)) die('пустой запрос');
        $url = $post['url'];
        Parser::parse($url);
    }
}