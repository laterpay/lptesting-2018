<?php

// Create token header as a JSON string
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

// this var should be filled with your input, unique per article (perhaps from CMS)
$articleId = 'something123';
// this var might be a constant in the beginning, or a custom CMS field
$price = 39;
// ideally fill via CMS article title
$title = 'my article title';

// insert your merchant secret (web.sandbox.uselaterpaytest.com/merchant -> Developer section) here USING Omaha World Herald
$secret = '96eb7e66d6274d3593557860dd75991c';

// Create token payload as a JSON string
$payload = json_encode(
    [
        'purchase_options' => array(
            array(
                'article_id' => $articleId,
                'price' => array(
                    'amount' => $price,
                    'currency' => 'USD',
                    'payment_model' => 'pay_later'
                ),
                'sales_model' => 'single_purchase',
                'title' => $title
            )
        )
    ]
    );

// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

// Create Meta-Tag
$metaTag = '<meta property="laterpay:connector:config_token" content="' . $jwt . '">';

echo $metaTag;

?>