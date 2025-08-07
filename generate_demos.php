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

    $demos[] = $entry;
}

file_put_contents($base_path . '/demos.json', json_encode($demos, JSON_PRETTY_PRINT));
echo "demos.json generated successfully.\n";

?>