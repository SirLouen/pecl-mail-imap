--TEST--
imap_mail() rejects header injection
--EXTENSIONS--
imap
--SKIPIF--
<?php if (PHP_OS_FAMILY === 'Windows') die('skip Windows sends through SMTP'); ?>
--INI--
sendmail_path={MAIL:{PWD}/imap_mail_header_injection.eml}
--FILE--
<?php
$file = __DIR__ . '/imap_mail_header_injection.eml';
$injected = "\nBcc: injected@example.com";

var_dump(imap_mail('to@example.com' . $injected, 'Subject', 'Body'));
var_dump(imap_mail('to@example.com', 'Subject' . $injected, 'Body'));
var_dump(imap_mail('to@example.com', 'Subject', 'Body', null, 'cc@example.com' . $injected));
var_dump(imap_mail('to@example.com', 'Subject', 'Body', null, null, 'bcc@example.com' . $injected));
var_dump(imap_mail('to@example.com', 'Subject', 'Body', null, null, null, 'rp@example.com' . $injected));
var_dump(file_exists($file));

// folded headers are still allowed
var_dump(imap_mail('to@example.com', "Folded\r\n subject", 'Body'));
echo file_get_contents($file);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imap_mail_header_injection.eml');
?>
--EXPECTF--
Warning: imap_mail(): header injection attempt in to in %s on line %d
bool(false)

Warning: imap_mail(): header injection attempt in subject in %s on line %d
bool(false)

Warning: imap_mail(): header injection attempt in cc in %s on line %d
bool(false)

Warning: imap_mail(): header injection attempt in bcc in %s on line %d
bool(false)

Warning: imap_mail(): header injection attempt in return_path in %s on line %d
bool(false)
bool(false)
bool(true)
To: to@example.com
Subject: Folded
 subject

Body
