--TEST--
imap_mail() returns true when the mail delivery program queues the message (exit code 75)
--EXTENSIONS--
imap
--SKIPIF--
<?php if (PHP_OS_FAMILY === 'Windows') die('skip Windows sends through SMTP'); ?>
--INI--
sendmail_path="cat >/dev/null; exit 75"
--FILE--
<?php
var_dump(imap_mail('to@example.com', 'Test subject', 'Test body'));
?>
--EXPECT--
bool(true)
