<?php

namespace App\Service;

use App\Entity\House;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Goutte\Client;

class SsScraperService
{
    private Client $client;
    private HouseRepository $house_repository;

    public function __construct(
        private ManagerRegistry        $doctrine,
        private EntityManagerInterface $entity_manager,
    )
    {
        $this->client = new Client();
        $this->client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0');
        $this->house_repository = $this->doctrine->getRepository(House::class);
    }

    public function getHouseDataRigaRegion(): array
    {
        $headers_get = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0',
            'HTTP_REFERER' => 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga-region/all/sell/',
        ];
        $headers_post = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0',
            'HTTP_REFERER' => 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga-region/all/sell/',
            'HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ];
        $form_data = [
            'topt[8][min]' => '',
            'topt[8][max]' => '150000',
            'topt[3][min]' => '',
            'topt[3][max]' => '',
            'topt[57][min]' => '',
            'topt[57][max]' => '',
            'topt[58][min]' => '4',
            'topt[58][max]' => '',
            'topt[60][min]' => '',
            'topt[60][max]' => '',
            'sid' => '/lv/real-estate/homes-summer-residences/riga-region/all/sell/filter/',
            'topt[11]' => '',
        ];
        $uri_get = 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga-region/all/sell/';
        $uri_post = 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga-region/all/sell/filter/';

        return $this->processHouseCategory($uri_get, $uri_post, $headers_get, $headers_post, $form_data);
    }

    public function getHouseDataRiga(): array
    {
        $headers_get = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0',
            'HTTP_REFERER' => 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga/all/',
        ];
        $headers_post = [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0',
            'HTTP_REFERER' => 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga/all/',
            'HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        ];
        $form_data = [
            'topt[8][min]' => '',
            'topt[8][max]' => '150000',
            'topt[3][min]' => '',
            'topt[3][max]' => '',
            'topt[57][min]' => '',
            'topt[57][max]' => '',
            'topt[58][min]' => '4',
            'topt[58][max]' => '',
            'topt[60][min]' => '',
            'topt[60][max]' => '',
            'sid' => '/lv/real-estate/homes-summer-residences/riga/all/sell/filter/',
            'topt[11]' => '',
        ];
        $uri_get = 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga/all/sell/';
        $uri_post = 'https://www.ss.lv/lv/real-estate/homes-summer-residences/riga/all/sell/filter/';

        return $this->processHouseCategory($uri_get, $uri_post, $headers_get, $headers_post, $form_data);
    }

    private function processHouseCategory(string $uri_get, string $uri_post, array $headers_get, array $headers_post, array $form_data): array
    {
        $output = [];
        $this->client->request('GET', $uri_get, server: $headers_get);
        $this->client->request('POST', $uri_post, parameters: $form_data, server: $headers_post);
        $crawler = $this->client->request('GET', $uri_post, server: $headers_get);
        $rows = $crawler->filter('table[align="center"]')->filter('tr');
        /**
         * @var \DOMElement $row
         */
        foreach ($rows as $row) {
            if ($row->attributes[0]?->textContent === 'head_line') {
                continue;
            }
            if (8 !== count($row->childNodes)) {
                continue;
            }
            $href = $row->childNodes[1]->childNodes[0]->attributes[0]->nodeValue;
            $description = $row->childNodes[2]->nodeValue;
            $address = $this->parseChildren($row->childNodes[3]);
            $m2 = $row->childNodes[4]->nodeValue;
            $floors = $row->childNodes[5]->nodeValue;
            $fulL_area = $row->childNodes[6]->nodeValue;
            $price = $row->childNodes[7]->nodeValue;

            $exists = $this->house_repository->findBy(['description' => $description]);
            if (0 === count($exists)) {
                $house = new House();
                $house->setDescription($description);
                $house->setAddress($address);
                $house->setFloors($floors);
                $house->setArea($m2);
                $house->setTotalArea($fulL_area);
                $house->setPrice($price);
                $house->setDateAdded(new \DateTimeImmutable());
                $house->setHref($href);
                $this->entity_manager->persist($house);
                $output[] = [
                    'description' => $description,
                    'address' => $address,
                    'price' => $price,
                    'href' => sprintf('https://www.ss.lv%s',$href),
                ];
            }
        }
        $this->entity_manager->flush();
        $this->entity_manager->clear();
        return $output;
    }

    private function parseChildren(\DOMElement $data): string
    {
        $output = [];
        if ($data->hasChildNodes()) {
            foreach ($data->childNodes as $node) {
                $output[] = $node->nodeValue;
            }
            return implode(" ", $output);
        }
        return $data->nodeValue;
    }
}