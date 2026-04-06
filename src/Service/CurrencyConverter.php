<?php

namespace Drupal\nsb_service_collection\Service;

use GuzzleHttp\ClientInterface;

/**
 * Converts currencies using an external API.
 */
class CurrencyConverter {

  /**
   * HTTP client service.
   */
  protected ClientInterface $httpClient;

  /**
   * Constructs the CurrencyConverter.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * Converts amount between currencies.
   */
  public function convert(float $amount, string $from, string $to): float {
    $rate = $this->getRate($from, $to);

    return round($amount * $rate, 2);
  }

  /**
   * Fetches exchange rate from API.
   */
  protected function getRate(string $from, string $to): float {
    $url = "https://open.er-api.com/v6/latest/{$from}";

    $response = $this->httpClient->request('GET', $url);

    $data = json_decode($response->getBody()->getContents(), TRUE);

    if (!isset($data['rates'][$to])) {
      throw new \RuntimeException('Invalid API response.');
    }

    return (float) $data['rates'][$to];
  }

}
