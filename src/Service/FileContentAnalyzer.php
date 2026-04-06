<?php

namespace Drupal\nsb_service_collection\Service;

/**
 * Provides basic file content analysis.
 */
class FileContentAnalyzer {

  /**
   * Analyzes file content and returns basic metrics.
   */
  public function analyze(string $filePath): array {
    if (!file_exists($filePath) || !is_readable($filePath)) {
      throw new \InvalidArgumentException('File not found or not readable.');
    }

    $content = file_get_contents($filePath);

    return [
      'lines' => $this->countLines($content),
      'words' => $this->countWords($content),
      'characters' => $this->countCharacters($content),
      'most_frequent_words' => $this->getMostFrequentWords($content),
    ];
  }

  /**
   * Counts lines in content.
   */
  protected function countLines(string $content): int {
    return substr_count($content, PHP_EOL) + 1;
  }

  /**
   * Counts words in content.
   */
  protected function countWords(string $content): int {
    return str_word_count($content);
  }

  /**
   * Counts characters in content.
   */
  protected function countCharacters(string $content): int {
    return mb_strlen($content);
  }

  /**
   * Returns most frequent words.
   */
  protected function getMostFrequentWords(string $content, int $limit = 5): array {
    $content = mb_strtolower($content);

    $content = preg_replace('/[^\p{L}\p{N}\s]/u', '', $content);

    $words = preg_split('/\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);

    $wordCounts = array_count_values($words);

    arsort($wordCounts);

    return array_slice($wordCounts, 0, $limit, TRUE);
  }

}
