<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BankData
{
    public function __construct(private HttpClientInterface $client, private DecoderInterface $decoder)
    {
    }

    public function fetchBankData(): ?array
    {
        $response = $this->client->request(
            'GET',
            'https://www.bank.lv/vk/ecb_rss.xml'
        );

        if ($response->getStatusCode() != 200)
        {
            return null;
        }

        $xmlData = $this->decoder->decode($response->getContent(), 'xml');

        if (!isset($xmlData['channel']))
        {
            return null;
        }

        $data = [];

        foreach ($xmlData['channel']['item'] as $item)
        {
            $dateTime = new \DateTime($item['pubDate']);

            $items = $this->convertToArray($item['description']);

            $data[] = [
                'dateTime' => $dateTime,
                'items'    => $items,
            ];
        }

        return $data;
    }

    private function convertToArray($string): array
    {
        $separator = ' ';
        $count = 2;

        return array_filter(array_map(function($item) use ($separator)
        {
            return !isset($item[1]) ? '' : ['currency' => $item[0], 'rate' => $item[1]];
        }, array_chunk(explode($separator, $string), $count)));
    }
}
