<?php
namespace App\Controller;

use App\Repository\ExchangeRateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateList extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ExchangeRateRepository $exchangeRateRepository): Response
    {
        $exchangeRates = $exchangeRateRepository->findAll();

        $exchangeRateSorted = $dates = $currencyRates = $currencies = [];

        foreach ($exchangeRates as $exchangeRate)
        {
            $currencyRates[$exchangeRate->getDate()->format('Y-m-d')][$exchangeRate->getCurrency()] = $exchangeRate->getRate();
            $dates[$exchangeRate->getDate()->format('Y-m-d')] = $exchangeRate->getDate()->format('d.m.Y');
            $currencies[$exchangeRate->getCurrency()] = $exchangeRate->getCurrency();
        }

        ksort($dates);
        sort($currencies);

        foreach ($dates as $date => $showDate)
        {
            foreach ($currencies as $currency)
            {
                $exchangeRateSorted[$currency][] = $currencyRates[$date][$currency] ?? '-';
            }
        }

        return $this->render('index.html.twig', [
            'exchangeRate' => $exchangeRateSorted,
            'dates'        => $dates,
        ]);
    }

    #[Route('/currency/{code}', name: 'currency')]
    public function currency(ExchangeRateRepository $exchangeRateRepository, Request $request): Response
    {
        $exchangeRates = $exchangeRateRepository->findBy(['currency' => $request->get('code')], ['date' => 'ASC']);

        $currencyRates = [];

        foreach ($exchangeRates as $exchangeRate)
        {
            $currencyRates[$exchangeRate->getDate()->format('Y-m-d')] = $exchangeRate->getRate();
        }

        return $this->render('currency.html.twig', [
            'currency'     => $request->get('code'),
            'exchangeRate' => $currencyRates
        ]);
    }
}
