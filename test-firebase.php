<?php


require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount('testing-ceaad-firebase-adminsdk-fbsvc-2bd6389010.json');

echo "✅ Firebase Admin SDK loaded successfully!";
