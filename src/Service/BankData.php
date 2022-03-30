<?php

namespace App\Service;

use App\Entity\ExchangeRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BankData
{
    public function __construct(private HttpClientInterface $client,
                                private DecoderInterface $decoder,
                                private EntityManagerInterface $entityManager)
    {
    }

    public function fetchBankData(): ?array
    {
        $response = $this->client->request(
            'GET',
            'https://www.bank.lv/vk/ecb_rss.xml'
        );

        if ($response->getStatusCode() != 200) {
            return null;
        }

        $xmlData = $this->decoder->decode($response->getContent(), 'xml');

        if (!isset($xmlData['channel'])) {
            return null;
        }

        $data = [];

        foreach ($xmlData['channel']['item'] as $item) {
            $dateTime = new \DateTime($item['pubDate']);

            $items = $this->convertToArray($item['description']);

            $data[] = [
                'dateTime' => $dateTime,
                'items'    => $items,
            ];
        }

        return $data;
    }

    public function fetchBankDataForDate($date): ?array
    {
        $dateTime = new \DateTime($date);
        $date = $dateTime->format('Ymd');

        $response = $this->client->request(
            'GET',
            'https://www.bank.lv/vk/ecb.xml?date=' . $date
        );

        if ($response->getStatusCode() != 200) {
            return null;
        }

        $xmlData = $this->decoder->decode($response->getContent(), 'xml');

        if (!isset($xmlData['Currencies']['Currency']) || !isset($xmlData['Date'])) {
            return null;
        }

        $data[0]['dateTime'] = new \DateTime($xmlData['Date']);

        foreach ($xmlData['Currencies']['Currency'] as $item) {
            $data[0]['items'][] = [
                'currency' => $item['ID'],
                'rate'     => $item['Rate'],
            ];
        }

        return $data;
    }

    private function convertToArray($string): array
    {
        $separator = ' ';
        $count = 2;

        return array_filter(array_map(function ($item) use ($separator) {
            return !isset($item[1]) ? '' : ['currency' => $item[0], 'rate' => $item[1]];
        }, array_chunk(explode($separator, $string), $count)));
    }

    public function uploadToDatabase($bankData)
    {
        foreach ($bankData as $data) {
            foreach ($data['items'] as $item) {
                $entity = new ExchangeRate();
                $entity->setIdExchangeRate($data['dateTime']->format('ymd') . '-' . $item['currency']);
                $entity->setCurrency($item['currency']);
                $entity->setDate($data['dateTime']);
                $entity->setRate($item['rate']);

                $this->entityManager->merge($entity);
                $this->entityManager->flush();
            }
        }
    }
}
