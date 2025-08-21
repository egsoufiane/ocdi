<?php

$demos = [];
$base_path = __DIR__; // Adjust if needed

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
    if (file_exists("$folder/customizer.dat")) {
        $entry['customizer'] = 'customizer.dat';
    }
    if (file_exists("$folder/preview.jpg")) {
        $entry['preview_image'] = 'preview.jpg';
    }
    if (file_exists("$folder/kit.zip")) {
        $entry['kit'] = true;
        $entry['kit_file'] = 'kit.zip';
    }

    // -------- Plugins Handling --------
    $plugins_dir = "$folder/plugins";
    $plugins = [
        'free' => [],
        'premium' => []
    ];

    // Free plugins from requirements.txt
    $req_file = "$plugins_dir/requirements.txt";
    if (file_exists($req_file)) {
        $slugs = file($req_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $plugins['free'] = $slugs;
    }

    // Premium plugin zips
    if (is_dir($plugins_dir)) {
        foreach (glob("$plugins_dir/*.zip") as $zip_file) {
            $plugin_slug = basename($zip_file, '.zip'); // keep version in name
            $plugins['premium'][$plugin_slug] = 'plugins/' . basename($zip_file);
        }
    }

    if (!empty($plugins['free']) || !empty($plugins['premium'])) {
        $entry['plugins'] = $plugins;
    }
    // ---------------------------------

    $demos[] = $entry;
}

file_put_contents($base_path . '/demos.json', json_encode($demos, JSON_PRETTY_PRINT));
echo "demos.json generated successfully.\n";

?>