<?php

require __DIR__ . '/../pChart/Barcodes/Linear/b2of5.php';

$encoder = new pChart\Barcodes\Linear\b2of5();

$alphabet = [
    '0' => [1, 1, 2, 2, 1],
    '1' => [2, 1, 1, 1, 2],
    '2' => [1, 2, 1, 1, 2],
    '3' => [2, 2, 1, 1, 1],
    '4' => [1, 1, 2, 1, 2],
    '5' => [2, 1, 2, 1, 1],
    '6' => [1, 2, 2, 1, 1],
    '7' => [1, 1, 1, 2, 2],
    '8' => [2, 1, 1, 2, 1],
    '9' => [1, 2, 1, 2, 1],
];

function encodedDigits(pChart\Barcodes\Linear\b2of5 $encoder, string $payload, array $alphabet): string
{
    $modules = $encoder->encode($payload, ['mode' => 'Interleaved+'])[0]['m'];
    $modules = array_slice($modules, 4, count($modules) - 8);
    $digits = '';
    foreach (array_chunk($modules, 10) as $pair) {
        $bars = [];
        $spaces = [];
        foreach ($pair as $i => $module) {
            if ($i % 2 === 0) {
                $bars[] = $module[1];
            } else {
                $spaces[] = $module[1];
            }
        }
        $digits .= array_search($bars, $alphabet, true) . array_search($spaces, $alphabet, true);
    }

    return $digits;
}

$cases = [
    '12' => '0123',
    '1' => '17',
    '123' => '1236',
    '51515' => '515153',
];

$failed = false;
foreach ($cases as $payload => $expected) {
    $actual = encodedDigits($encoder, (string) $payload, $alphabet);
    if ($actual !== $expected) {
        fwrite(STDERR, $payload . ' encoded ' . $actual . ', expected ' . $expected . PHP_EOL);
        $failed = true;
    }
}

exit($failed ? 1 : 0);
