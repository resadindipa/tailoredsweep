<?php
// require_once "../php/config.php";

// Prevent direct access
if (!defined('ALLOW_ACCESS')) {
    die("Direct access is not allowed.");
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Link</title>
</head>

<body>
    <p>Hello There</p>
    <!-- $DEFAULT_BASE_LINK_CMS = localhost/tailoredsweep/ventoras-clients-login -->
    <p>We received a request to reset your password. To proceed, please click the link below and visit it from a browser to securely reset your password:</p>
    <p><a href="<?php echo $DEFAULT_BASE_LINK_CMS; ?>/password-reset/reset-password.php?
key=<?php echo $reset_key; ?>&action=reset" target="_blank">
            <?php echo $DEFAULT_BASE_LINK_CMS; ?>/password-reset/reset-password.php?key=<?php echo $reset_key; ?>&action=reset</a></p>
    <p>If you did not request this reset, you can safely ignore this email. For any concerns, please contact our support team.</p>
    <p>Best Regards,</p>
    <p>Ventoras Clients Management System</p>
</body>

</html>