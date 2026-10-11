--TEST--
imap_mail() sends the message through sendmail_path
--EXTENSIONS--
imap
--SKIPIF--
<?php if (PHP_OS_FAMILY === 'Windows') die('skip Windows sends through SMTP'); ?>
--INI--
sendmail_path={MAIL:{PWD}/imap_mail_sendmail_path.eml}
--FILE--
<?php
var_dump(imap_mail('to@example.com', 'Test subject', 'Test body', 'X-Test: yes',
    'cc@example.com', 'bcc@example.com', 'return@example.com'));
echo file_get_contents(__DIR__ . '/imap_mail_sendmail_path.eml');
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imap_mail_sendmail_path.eml');
?>
--EXPECT--
bool(true)
From: return@example.com
To: to@example.com
Cc: cc@example.com
Bcc: bcc@example.com
Subject: Test subject
X-Test: yes

Test body
