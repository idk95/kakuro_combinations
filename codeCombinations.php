<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the form submission
    $sum = intval($_POST['sum']);
    $numDigits = intval($_POST['numDigits']);
    $excludeDigits = $_POST['excludeDigits'] ?? [];
    $sumRight = !empty($_POST['sumRight']) ? intval($_POST['sumRight']) : null;
    $sumLeft = !empty($_POST['sumLeft']) ? intval($_POST['sumLeft']) : null;
    $sumCenter = !empty($_POST['sumCenter']) ? intval($_POST['sumCenter']) : null;
    $noPair = !isset($_POST['noPair']);
    $fixed = array_map(function ($value) {
        return ($value == '') ? null : intval($value);
    }, $_POST['fixed']);

    // Call the function to find combinations
    $result = findCombinations($sum, $numDigits, $fixed, $excludeDigits, $sumRight, $sumLeft, $sumCenter, $noPair);

    echo json_encode($result);
}

/**
 * @param int $sum
 * @param int $numDigits
 * @param array $fixed
 * @param array $excludeDigits
 * @param null|int $sumRight
 * @param null|int $sumLeft
 * @param null|int $sumCenter
 * @param bool $noPair
 * @return array
 */
function findCombinations(
    int      $sum,
    int      $numDigits,
    array    $fixed,
    array    $excludeDigits,
    null|int $sumRight,
    null|int $sumLeft,
    null|int $sumCenter,
    bool     $noPair
): array
{
    $combinations = [];
    $maxDigit = 9;
    $indexSum = [
        4 => ['left' => [0, 2], 'center' => [1, 2], 'right' => [2, 2]],
        5 => ['left' => [0, 3], 'center' => [1, 3], 'right' => [2, 3]]
    ];

    $maxRange = pow($maxDigit + 1, $numDigits);

    foreach (range(0, $maxRange) as $i) {
        $current = $i;
        $currentCombination = [];
        $validCombination = true;

        foreach (range($numDigits - 1, 0) as $j) {
            $digit = $current % 10;

            // Check if the current digit is excluded
            if (in_array($digit, $excludeDigits)) {
                $validCombination = false;
                break;
            }

            if ($current > 10) {
                $lastDigit = $current % 10;

                $secondLastDigit = ((int)($current / 10)) % 10;
                if ($lastDigit < $secondLastDigit) {
                    $validCombination = false;
                    break;
                }
            }

            if ($fixed[$j] !== null && $digit !== $fixed[$j]) {
                $validCombination = false;
                break;
            }

            array_unshift($currentCombination, $digit);
            $current = intdiv($current, 10);
        }

        if (isset($sumRight) && array_sum(array_slice($currentCombination, $indexSum[$numDigits]['right'][0], $indexSum[$numDigits]['right'][1])) !== $sumRight
            || isset($sumLeft) && array_sum(array_slice($currentCombination, $indexSum[$numDigits]['left'][0], $indexSum[$numDigits]['left'][1])) !== $sumLeft
            || isset($sumCenter) && array_sum(array_slice($currentCombination, $indexSum[$numDigits]['center'][0], $indexSum[$numDigits]['center'][1])) !== $sumCenter
        ) {
            $validCombination = false;
        }

        if ($validCombination
            && array_sum($currentCombination) === $sum
            && countDuplicates($currentCombination) <= (int)$noPair
        ) {
            $combinations[] = $currentCombination;
        }
    }

    return $combinations;
}

/**
 * @param $array
 * @return int
 */
function countDuplicates($array): int
{
    $count = array_count_values($array);
    $duplicates = 0;

    foreach ($count as $value) {
        if ($value > 1) {
            $duplicates += $value - 1;
        }
    }

    return $duplicates;
}

