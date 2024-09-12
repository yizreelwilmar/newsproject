<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config = array(
    'protocol'  => 'smtp',                      // Mail protocol (smtp, mail, sendmail, etc.)
    'smtp_host' => 'smtp.hostinger.com', // SMTP server address (e.g., smtp.gmail.com for Gmail)
    'smtp_port' => 465,                         // SMTP port (587 for TLS, 465 for SSL, 25 for non-secure)
    'smtp_user' => 'no_reply@cvbsmtrans.com', // SMTP username (your email address)
    'smtp_pass' => 'Admin1981022!',       // SMTP password
    'mailtype'  => 'html',                      // Email format (html or text)
    'charset'   => 'iso-8859-1',                // Email character set
    'wordwrap'  => TRUE,                        // Enable word wrapping
    'smtp_crypto' => 'tls'                      // Encryption (ssl or tls)
);
