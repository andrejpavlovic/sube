<?php

// Database connection info
define('_DB_HOST', 'localhost');
define('_DB_NAME', 'uwsube');
define('_DB_USER', 'root');
define('_DB_PASS', '');

// Define email settings
define('_EMAIL_FROM_NAME', 'SUBE');
define('_EMAIL_FROM_ADDRESS', 'info@localhost');
define('_EMAIL_ERROR_ADDRESS', 'error@localhost');
define('_EMAIL_SUPPORT_ADDRESS', 'info@localhost');

// Define mailer settings
define('_MAILER_TYPE', 'mail');
//define('_MAILER_TYPE', 'sendmail');
//define('_MAILER_SENDMAIL_CMD', '/usr/sbin/sendmail -t');
//define('_MAILER_TYPE', 'smtp');
//define('_MAILER_SMTP_HOST', '127.0.0.1');

// Define amazon api settings
define('_AMAZON_ENABLED', false);
define('_AMAZON_PRIVATE_KEY', dirname(__FILE__) . '/amazon/pk-amazon-private-key.pem');
define('_AMAZON_CERT_FILE', dirname(__FILE__) . '/amazon/cert-amazon-cert.pem');
define('_AMAZON_AWS_ACCESS_KEY_ID', 'AKIAIM2T4FM6QWJDHDZQ');
define('_AMAZON_ASSOCIATE_TAG', '?????-??');
