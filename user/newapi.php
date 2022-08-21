<!--newapi.php-->
<?php
    $secretKey = "secretkey";
    // Generates a random string of ten digits
    $salt = mt_rand();
    // Computes the signature by hashing the salt with the secret key as the key
    $signature = hash_hmac('sha256', $salt, $secretKey, true);
    // base64 encode...
    $encodedSignature = base64_encode($signature);
    // urlencode...
    $encodedSignature = urlencode($encodedSignature);
?>
<form class="apiForm" name="api_key" method="POST" action="updateapi.php">
    <label for="quantity">Your New Api Key is:</label>
    <input type="text" id="api_key_value" name="api_key_value" value="<?php echo($encodedSignature); ?>" readonly>
    <input type="submit" value="Submit">
</form>