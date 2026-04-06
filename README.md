# NSB Service Collection

A collection of Drupal service examples focused on reusable backend logic.

## What it shows

-   **CurrencyConverter**: Converts amounts between currencies using external data --- see [usage](#currency-converter-usage)
-   **FileContentAnalyzer**: Analyzes file content (lines, words, characters) --- see [usage](#file-content-analyzer-usage)
-   **ScoreEvaluator**: Evaluates numeric scores and returns normalized results (e.g. average, grade) --- see [usage](#score-evaluator-usage)

------------------------------------------------------------------------

## Demo

Interactive demo available at:

    /nsb-services-demo

You can pass query parameters:

    /nsb-services-demo?amount=200&from=EUR&to=TRY&scores=10,20,30

------------------------------------------------------------------------

## Currency Converter Usage

```php
$converter = \Drupal::service('nsb_service_collection.currency_converter');

$result = $converter->convert(100, 'USD', 'TRY');
```

------------------------------------------------------------------------

## File Content Analyzer Usage

```php
$analyzer = \Drupal::service('nsb_service_collection.file_content_analyzer');

$result = $analyzer->analyze('/path/to/file.txt');

/*
Example output:
[
  'lines' => 10,
  'words' => 120,
  'characters' => 850,
  'most_frequent_words' => [
    'drupal' => 5,
    'service' => 4,
  ],
]
*/
```

------------------------------------------------------------------------

## Score Evaluator Usage

```php
$average = $this->scoreEvaluator->average([80, 90, 100]);
$grade = $this->scoreEvaluator->grade($average);
```

------------------------------------------------------------------------

## Notes

-   All services are registered via `services.yml`
-   Uses dependency injection where applicable
