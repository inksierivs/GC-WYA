<?php
session_start();  // Start the session to store user data

require 'vendor/autoload.php';

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

try {
    // Initialize Firebase
    $factory = (new Factory)->withServiceAccount('testing-ceaad-firebase-adminsdk-fbsvc-2bd6389010.json');
    $auth = $factory->createAuth();
    $firestore = $factory->createFirestore();
    $db = $firestore->database();

    // Get the ID token from the request
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['idToken'])) {
        echo json_encode(['success' => false, 'error' => 'No token provided']);
        exit;
    }

    $idToken = $data['idToken'];
    $verifiedToken = $auth->verifyIdToken($idToken);
    $uid = $verifiedToken->claims()->get('sub');
    $email = $verifiedToken->claims()->get('email');

    // Store the user's ID and email in session
    $_SESSION['uid'] = $uid;
    $_SESSION['email'] = $email;

    // Retrieve user data from Firestore
    $userDoc = $db->collection('users')->document($uid)->snapshot();
    if ($userDoc->exists()) {
        // If user data exists in Firestore, set session variables for first and last names
        $userData = $userDoc->data();
        $_SESSION['firstName'] = $userData['firstName'] ?? '';
        $_SESSION['lastName'] = $userData['lastName'] ?? '';
        
        // Return the user's data
        echo json_encode(['success' => true, 'uid' => $uid, 'email' => $email, 'firstName' => $_SESSION['firstName'], 'lastName' => $_SESSION['lastName']]);
    } else {
        // If user does not exist in Firestore, send an error
        echo json_encode(['success' => false, 'error' => 'User not found in Firestore']);
    }

} catch (InvalidToken $e) {
    // If the token is invalid, respond with an error
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid token: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Handle other errors
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>
