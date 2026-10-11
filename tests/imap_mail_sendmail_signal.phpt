--TEST--
imap_mail() returns false when the mail delivery program is killed by a signal
--EXTENSIONS--
imap
--SKIPIF--
<?php if (PHP_OS_FAMILY === 'Windows') die('skip Windows sends through SMTP'); ?>
--INI--
sendmail_path="cat >/dev/null; kill \$\$"
--FILE--
<?php
var_dump(imap_mail('to@example.com', 'Test subject', 'Test body'));
?>
--EXPECTF--
Warning: imap_mail(): Sendmail killed by signal 15 (%s) in %s on line %d
bool(false)
