--TEST--
imap_open() DISABLE_AUTHENTICATOR only applies to that call
--EXTENSIONS--
imap
--SKIPIF--
<?php
require_once(__DIR__.'/setup/skipif.inc');
// The test needs a server where CRAM-MD5 is the only way to log in, like the one in tests/setup
$imap = @imap_open(IMAP_SERVER_NO_DEBUG, IMAP_MAILBOX_USERNAME, IMAP_MAILBOX_PASSWORD, OP_HALFOPEN, 0, ['DISABLE_AUTHENTICATOR' => 'CRAM-MD5']);
imap_errors();
if ($imap) {
    die('skip the server accepts a login without CRAM-MD5');
}
?>
--FILE--
<?php
require_once(__DIR__.'/setup/imap_include.inc');

function login(array $options = []): void {
    $imap = @imap_open(IMAP_SERVER_NO_DEBUG, IMAP_MAILBOX_USERNAME, IMAP_MAILBOX_PASSWORD, OP_HALFOPEN, 0, $options);
    imap_errors();
    echo json_encode($options), ': ', $imap ? 'logged in' : 'failed', "\n";
}

login(['DISABLE_AUTHENTICATOR' => 'CRAM-MD5']);
login();
login(['DISABLE_AUTHENTICATOR' => ['CRAM-MD5']]);
login();
try {
    imap_open(IMAP_SERVER_NO_DEBUG, IMAP_MAILBOX_USERNAME, IMAP_MAILBOX_PASSWORD, OP_HALFOPEN, 0, ['DISABLE_AUTHENTICATOR' => ['CRAM-MD5', 1]]);
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
login();
?>
--EXPECT--
{"DISABLE_AUTHENTICATOR":"CRAM-MD5"}: failed
[]: logged in
{"DISABLE_AUTHENTICATOR":["CRAM-MD5"]}: failed
[]: logged in
imap_open(): Argument #6 ($options) option "DISABLE_AUTHENTICATOR" must be a string or an array of strings
[]: logged in
