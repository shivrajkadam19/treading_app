<?php

// Function to generate a JWT
function generateJWT($username, $email)
{
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    // Set the token to expire in 1 hour (3600 seconds)
    $iat = time();
    $exp = $iat + 3600;

    $payload = json_encode([
        'username' => $username,
        'email' => $email,
        'iat' => $iat,
        'exp' => $exp
    ]);

    // Encode the header and payload to base64 URL format
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    $secret = 'your-256-bit-secret';  // Replace with your own secret key

    // Create the signature
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // Create the JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    return $jwt;
}

// Function to validate a JWT
function validateJWT($jwt)
{
    $secret = 'your-256-bit-secret'; 
    
    // Split the JWT into its three parts: header, payload, and signature
    $tokenParts = explode('.', $jwt);
    if (count($tokenParts) !== 3) {
        return false; // Invalid token structure
    }

    list($headerEncoded, $payloadEncoded, $signatureProvided) = $tokenParts;

    // Decode the header and payload
    $header = json_decode(base64_decode($headerEncoded), true);
    $payload = json_decode(base64_decode($payloadEncoded), true);

    // Verify the signature
    $signatureBase64Url = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(
        hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $secret, true)
    ));

    if ($signatureBase64Url !== $signatureProvided) {
        return false; // Signature is invalid
    }

    // Verify the expiration time (exp)
    $currentTime = time();
    if (isset($payload['exp']) && $payload['exp'] < $currentTime) {
        return false; // Token has expired
    }

    // If everything checks out, return the payload (which contains the username, email, etc.)
    return $payload;
}
