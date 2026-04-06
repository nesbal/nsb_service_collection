<?php

declare(strict_types=1);

namespace Drupal\nsb_service_collection\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\nsb_service_collection\Service\CurrencyConverter;
use Drupal\nsb_service_collection\Service\FileContentAnalyzer;
use Drupal\nsb_service_collection\Service\ScoreEvaluator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Demonstrates service usage via query parameters.
 */
class ServiceDemoController extends ControllerBase {

  /**
   * Currency converter service.
   */
  protected CurrencyConverter $currencyConverter;

  /**
   * File content analyzer service.
   */
  protected FileContentAnalyzer $fileAnalyzer;

  /**
   * Score evaluator service.
   */
  protected ScoreEvaluator $scoreEvaluator;

  /**
   * Constructs the controller.
   */
  public function __construct(
    CurrencyConverter $currency_converter,
    FileContentAnalyzer $file_analyzer,
    ScoreEvaluator $score_evaluator,
  ) {
    $this->currencyConverter = $currency_converter;
    $this->fileAnalyzer = $file_analyzer;
    $this->scoreEvaluator = $score_evaluator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('nsb_service_collection.currency_converter'),
      $container->get('nsb_service_collection.file_content_analyzer'),
      $container->get('nsb_service_collection.score_evaluator'),
    );
  }

  /**
   * Returns demo output.
   */
  public function demo(Request $request): array {

    $amount = (float) ($request->query->get('amount') ?? 100);
    $from = (string) ($request->query->get('from') ?? 'USD');
    $to = (string) ($request->query->get('to') ?? 'TRY');

    $scoresParam = (string) ($request->query->get('scores') ?? '70,80,90');
    $scores = array_map('floatval', explode(',', $scoresParam));

    $converted = $this->currencyConverter->convert($amount, $from, $to);

    $filePath = DRUPAL_ROOT . '/modules/custom/nsb_service_collection/files/example.txt';
    $fileResult = $this->fileAnalyzer->analyze($filePath);

    $average = $this->scoreEvaluator->average($scores);
    $grade = $this->scoreEvaluator->grade($average);

    return [
      '#type' => 'container',
      'currency' => [
        '#markup' => "<p><strong>Currency:</strong> {$amount} {$from} → {$to} = {$converted}</p>",
      ],
      'file' => [
        '#markup' => '<strong>File analysis:</strong><br>' . $this->formatFileAnalysis($fileResult),
      ],
      'score' => [
        '#markup' => "<p><strong>Score:</strong> avg={$average}, grade={$grade}</p>",
      ],
      'hint' => [
        '#markup' => '<hr><small>Try: /nsb-services-demo?amount=200&from=EUR&to=TRY&scores=10,20,30</small>',
      ],
    ];
  }

  /**
   * Formats file analysis result for display.
   */
  protected function formatFileAnalysis(array $data): string {

    $output = 'Lines: ' . $data['lines'] . '<br>';
    $output .= 'Words: ' . $data['words'] . '<br>';
    $output .= 'Characters: ' . $data['characters'] . '<br>';

    $output .= '<br><strong>Most frequent words:</strong><br>';

    foreach ($data['most_frequent_words'] as $word => $count) {
      $output .= $word . ': ' . $count . '<br>';
    }

    return $output;
  }

}
