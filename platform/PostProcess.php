<?php

declare(strict_types=1);

const ENTRYPOINTS = './public/build';

$dirs = scandir(ENTRYPOINTS);

function extractEntrypoints($directory)
{
    $entrypointsContent = [];

    foreach ($directory as $dir) {
        if (! is_dir($dirName = ENTRYPOINTS . '/' . $dir) || in_array($dir, ['.', '..'])) {
            continue;
        }

        // check if entrypoints.json exists
        if (! file_exists($entrypointJson = $dirName . '/entrypoints.json')) {
            break;
        }

        $content = json_decode(file_get_contents($entrypointJson), true);
        array_push($entrypointsContent, $content);
    }

    return $entrypointsContent;
}

function generateMainEntrypoint($entries)
{
    $generated = [];

    foreach ($entries as $key => $content) {
        $app = array_key_first(current($content));
        $generated['entrypoints'][$app] = $content['entrypoints'][$app] ?? null;
    }

    return $generated;
}


$output = json_encode(generateMainEntrypoint(extractEntrypoints($dirs)), JSON_PRETTY_PRINT);

file_put_contents(ENTRYPOINTS . '/entrypoints.json', $output);

echo PHP_EOL;
