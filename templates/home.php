<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slim Example</title>

    <?php
    $assetsDir = '/var/www/html/app/assets';
    $assetsUrl = '/assets/';

    $assetFiles = scandir($assetsDir);
    foreach ($assetFiles as $assetFile) {
        if ( strpos($assetFile, '.js') == strlen($assetFile) - 3 ) {
            echo '<script a="b" type="module" crossorigin src="'.$assetsUrl.$assetFile.'"></script>'.PHP_EOL;
        }
        if ( strpos($assetFile, '.css') == strlen($assetFile) - 4 ) {
            echo '<link rel="stylesheet" crossorigin href="'.$assetsUrl.$assetFile.'">'.PHP_EOL;
        }
    }
    ?>
</head>
<body>
<?php ///** @var string $name template parameter */ ?>
<!--<h1>Hello, --><?php //= htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><!--</h1>-->

<div id="root"></div>
</body>
</html>
