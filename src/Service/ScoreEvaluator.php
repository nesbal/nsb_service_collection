<?php

declare(strict_types=1);

namespace Drupal\nsb_service_collection\Service;

/**
 * Evaluates numeric scores and returns normalized results.
 */
class ScoreEvaluator {

  /**
   * Calculates the average of given scores.
   */
  public function average(array $scores): float {
    if (empty($scores)) {
      return 0.0;
    }

    return array_sum($scores) / count($scores);
  }

  /**
   * Returns a grade based on score.
   */
  public function grade(float $score): string {
    if ($score >= 90) {
      return 'A';
    }

    if ($score >= 80) {
      return 'B';
    }

    if ($score >= 70) {
      return 'C';
    }

    if ($score >= 60) {
      return 'D';
    }

    return 'F';
  }

}
