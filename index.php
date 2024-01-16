<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kakuro Combinations</title>
</head>
<body>
<h1>Kakuro Combinations Finder</h1>

<form method="post" action="">
    <div class="number-row">
        <?php
        foreach (range(0, 9) as $i) {
            echo '<label class="number-checkbox">
                    <input type="checkbox" name="excludeDigits[]" value="' . $i . '">' . $i . '
                </label>';
        }
        ?>
    </div>

    <div>
        <label for="sum">Enter the sum:</label>
        <input type="number" id="sum" name="sum" required min="1">
    </div>

    <div>
        <label for="numDigits">Enter the number of digits (1-5):</label>
        <input type="number" id="numDigits" name="numDigits" required min="1" max="5">
    </div>

    <div>
        <label for="fixedPositions">Enter positions of fixed digits (comma-separated):</label>
        <input type="text" id="fixedPositions" name="fixedPositions" placeholder="e.g., 1,3,5">
    </div>

    <div>
        <label for="fixedValues">Enter values of fixed digits (comma-separated):</label>
        <input type="text" id="fixedValues" name="fixedValues" placeholder="e.g., 3,1,2">
    </div>

    <button type="submit">Find Combinations</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the form submission
    $sum = intval($_POST['sum']);
    $numDigits = intval($_POST['numDigits']);
    $fixedPositions = isset($_POST['fixedPositions']) ? explode(',', $_POST['fixedPositions']) : [];
    $fixedValues = isset($_POST['fixedValues']) ? explode(',', $_POST['fixedValues']) : [];
    $excludeDigits = $_POST['excludeDigits'] ?? [];

    // Validate the input
    if (count($fixedPositions) !== count($fixedValues)) {
        echo "<p>Number of fixed positions must match the number of fixed values.</p>";
    } else {
        // Call the function to find combinations
        $result = findCombinations($sum, $numDigits, $fixedPositions, $fixedValues, $excludeDigits);

        echo "<h2>Combinations for sum $sum in $numDigits digits:</h2>";
        if (empty($result)) {
            echo "<p>No combinations found.</p>";
        } else {
            foreach ($result as $combination) {
                echo implode(', ', $combination) . "<br>";
            }
        }
    }
}

/**
 * @param int $sum
 * @param int $numDigits
 * @param array $fixedPositions
 * @param array $fixedValues
 * @param array $excludeDigits
 * @return array
 */
function findCombinations(
    int       $sum,
    int       $numDigits,
    array     $fixedPositions,
    array     $fixedValues,
    array     $excludeDigits,
): array
{
    $combinations = [];
    $maxDigit = 9;

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

            // Check if the current position is fixed
            if (in_array($j + 1, $fixedPositions)) {
                // If fixed, check if the value matches
                if ($digit !== intval($fixedValues[array_search($j + 1, $fixedPositions)])) {
                    $validCombination = false;
                    break;
                }
            }

            array_unshift($currentCombination, $digit);
            $current = intdiv($current, 10);
        }

        if ($validCombination
            && array_sum($currentCombination) === $sum
            && countDuplicates($currentCombination) <= 1
        ) {
            $combinations[] = $currentCombination;
        }
    }

    return $combinations;
}

/**
 * @param array $array
 * @return int
 */
function countDuplicates(array $array): int
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

?>

<style>
    html {
        background: #000000d1;
        color: white;
        font-family: system-ui;
    }

    button {
        background: black;
        color: white;
        padding: 5px 5px;
    }

    button:hover {
        background: #345564;
    }

    div {
        margin: 10px 0;
    }

    .number-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .number-checkbox {
        width: 40px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        border: 1px solid #ccc;
        cursor: pointer;
    }
</style>
</body>
</html>
