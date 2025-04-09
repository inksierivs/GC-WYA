<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// Hide deprecation warnings
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

try {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['idToken'])) {
        echo json_encode(["success" => false, "error" => "Missing ID token"]);
        exit;
    }

    // Optional debug
    file_put_contents("debug-log.txt", print_r($input, true));

    $idToken = $input['idToken'];
    $email = $input['email'] ?? '';
    $id = $input['id'] ?? '';
    $contact = $input['number'] ?? '';
    $yearLevel = $input['yearLevel'] ?? '';
    $block = $input['block'] ?? '';
    $fname = $input['firstName'] ?? '';
    $lname = $input['lastName'] ?? '';
    

    $factory = (new Factory)->withServiceAccount('testing-ceaad-firebase-adminsdk-fbsvc-8869da00e1.json');
    $auth = $factory->createAuth();
    $firestore = $factory->createFirestore();
    $db = $firestore->database();

    $verifiedIdToken = $auth->verifyIdToken($idToken);
    $uid = $verifiedIdToken->claims()->get('sub');

    try {
        $db->collection('users')->document($uid)->set([
            'uid' => $uid,
            'email' => $email,
            'id' => $id,
            'contact' => $contact,
            'yearLevel' => $yearLevel,
            'block' => $block,
            'firstName' => $fname, // This should be set correctly
            'lastName' => $lname,  // This should be set correctly
            'createdAt' => (new DateTime())->format(DateTime::ATOM)        
        ]);
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => "Firestore write error: " . $e->getMessage()]);
    }

} catch (FailedToVerifyToken $e) {
    echo json_encode(["success" => false, "error" => "Invalid ID token"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Server error: " . $e->getMessage()]);
}
