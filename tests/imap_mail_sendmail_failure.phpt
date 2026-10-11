--TEST--
imap_mail() returns false when the mail delivery program fails
--EXTENSIONS--
imap
--SKIPIF--
<?php if (PHP_OS_FAMILY === 'Windows') die('skip Windows sends through SMTP'); ?>
--INI--
sendmail_path="cat >/dev/null; exit 1"
--FILE--
<?php
var_dump(imap_mail('to@example.com', 'Test subject', 'Test body'));
?>
--EXPECTF--
Warning: imap_mail(): Sendmail exited with non-zero exit code 1 in %s on line %d
bool(false)
