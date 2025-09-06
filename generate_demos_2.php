<?php

$demos = [];
$base_path = __DIR__; 
$global_plugins_dir = $base_path . '/plugins'; // Central plugins folder

// Build a lookup for available premium plugins in global plugins dir
$available_premium = [];
if (is_dir($global_plugins_dir)) {
    foreach (glob("$global_plugins_dir/*.zip") as $zip_file) {
        $slug = basename($zip_file, '.zip');
        $available_premium[$slug] = 'plugins/' . basename($zip_file);
    }
}

foreach (glob($base_path . '/demos/*') as $folder) {
    $folder_name = basename($folder);
    $entry = [
        'name' => ucfirst(str_replace('_', ' ', $folder_name)),
        'folder' => $folder_name,
        'preview' => $folder_name . '.dev.ananass.fr',
    ];

    // Optional demo files
    if (file_exists("$folder/content.xml")) {
        $entry['content'] = 'content.xml';
    }
    if (file_exists("$folder/widgets.wie")) {
        $entry['widgets'] = 'widgets.wie';
    }
    if (file_exists("$folder/customizer.json")) {
        $entry['customizer'] = 'customizer.json';
    }
    if (file_exists("$folder/preview.jpg")) {
        $entry['preview_image'] = 'preview.jpg';
    }
    if (file_exists("$folder/kit.zip")) {
        $entry['kit'] = true;
        $entry['kit_file'] = 'kit.zip';
    }

    // -------- Plugins Handling --------
    $plugins = [
        'free' => [],
        'premium' => []
    ];

    // Free plugins
    $req_file = "$folder/requirements.txt";
    if (file_exists($req_file)) {
        $plugins['free'] = file($req_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    // Premium plugins
    $premium_file = "$folder/premium.txt";
    if (file_exists($premium_file)) {
        $needed = file($premium_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($needed as $slug) {
            if (isset($available_premium[$slug])) {
                $plugins['premium'][$slug] = $available_premium[$slug];
            }
        }
    }

    if (!empty($plugins['free']) || !empty($plugins['premium'])) {
        $entry['plugins'] = $plugins;
    }
    // ---------------------------------

    $demos[] = $entry;
}

file_put_contents($base_path . '/demos.json', json_encode($demos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "demos.json generated successfully.\n";
