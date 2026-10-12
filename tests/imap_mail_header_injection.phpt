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
$valid = [
    'to' => 'to@example.com',
    'subject' => 'Subject',
    'cc' => 'cc@example.com',
    'bcc' => 'bcc@example.com',
    'return_path' => 'rp@example.com',
];

foreach (array_keys($valid) as $name) {
    foreach (["\n", "\r", "\r\n"] as $break) {
        foreach (["{$break}Bcc: injected@example.com", $break] as $suffix) {
            $args = ['to' => 'to@example.com', 'subject' => 'Subject', 'cc' => null, 'bcc' => null, 'return_path' => null];
            $args[$name] = $valid[$name] . $suffix;
            $result = @imap_mail($args['to'], $args['subject'], 'Body', null, $args['cc'], $args['bcc'], $args['return_path']);
            echo $name, ' ', json_encode($suffix), ': ', var_export($result, true), ', ', error_get_last()['message'] ?? 'no warning', "\n";
            error_clear_last();
        }
    }
}
var_dump(file_exists($file));

// folded headers are still allowed
var_dump(imap_mail('to@example.com', "Folded\r\n subject", 'Body'));
echo file_get_contents($file);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imap_mail_header_injection.eml');
?>
--EXPECT--
to "\nBcc: injected@example.com": false, imap_mail(): header injection attempt in to
to "\n": false, imap_mail(): header injection attempt in to
to "\rBcc: injected@example.com": false, imap_mail(): header injection attempt in to
to "\r": false, imap_mail(): header injection attempt in to
to "\r\nBcc: injected@example.com": false, imap_mail(): header injection attempt in to
to "\r\n": false, imap_mail(): header injection attempt in to
subject "\nBcc: injected@example.com": false, imap_mail(): header injection attempt in subject
subject "\n": false, imap_mail(): header injection attempt in subject
subject "\rBcc: injected@example.com": false, imap_mail(): header injection attempt in subject
subject "\r": false, imap_mail(): header injection attempt in subject
subject "\r\nBcc: injected@example.com": false, imap_mail(): header injection attempt in subject
subject "\r\n": false, imap_mail(): header injection attempt in subject
cc "\nBcc: injected@example.com": false, imap_mail(): header injection attempt in cc
cc "\n": false, imap_mail(): header injection attempt in cc
cc "\rBcc: injected@example.com": false, imap_mail(): header injection attempt in cc
cc "\r": false, imap_mail(): header injection attempt in cc
cc "\r\nBcc: injected@example.com": false, imap_mail(): header injection attempt in cc
cc "\r\n": false, imap_mail(): header injection attempt in cc
bcc "\nBcc: injected@example.com": false, imap_mail(): header injection attempt in bcc
bcc "\n": false, imap_mail(): header injection attempt in bcc
bcc "\rBcc: injected@example.com": false, imap_mail(): header injection attempt in bcc
bcc "\r": false, imap_mail(): header injection attempt in bcc
bcc "\r\nBcc: injected@example.com": false, imap_mail(): header injection attempt in bcc
bcc "\r\n": false, imap_mail(): header injection attempt in bcc
return_path "\nBcc: injected@example.com": false, imap_mail(): header injection attempt in return_path
return_path "\n": false, imap_mail(): header injection attempt in return_path
return_path "\rBcc: injected@example.com": false, imap_mail(): header injection attempt in return_path
return_path "\r": false, imap_mail(): header injection attempt in return_path
return_path "\r\nBcc: injected@example.com": false, imap_mail(): header injection attempt in return_path
return_path "\r\n": false, imap_mail(): header injection attempt in return_path
bool(false)
bool(true)
To: to@example.com
Subject: Folded
 subject

Body
