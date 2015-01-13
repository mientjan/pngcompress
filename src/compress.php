<?php

$options = getopt("i:o:", array('in:', 'out:'));

if (!isset($options['in']) || !isset($options['out'])) {
    throw new Exception('--in / --out are not defined. ' . var_export($options, true));
}

// get data
$data = file_get_contents($options['in']);
$size = strlen($data);

$bytes = array();
$str = '';
for ($i = 0; $i < $size; $i++) {
    $str = substr($data, $i, 1);
    if (!mb_detect_encoding($str, 'ASCII', true)) {
        var_dump($str, $i);
    }
    $bytes[] = ord(substr($data, $i, 1));
}

$width = intval(ceil(sqrt($size)));
$height = intval(ceil(sqrt($size)));

$im = imagecreate($width, $height);

$colors = array();
for ($i = 0; $i < 256; $i++) {
    $colors[$i] = imagecolorallocate($im, $i, 0, 0);
}

$i = 0;
$col = null;
for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        if (isset($bytes[$i])) {
            $col = $colors[$bytes[$i]];
        } else {
            $col = $colors[0];
        }

        imagesetpixel($im, $x, $y, $col);
        $i++;

    }
}

imagepng($im, $options['out'], 9);
imagedestroy($im);
