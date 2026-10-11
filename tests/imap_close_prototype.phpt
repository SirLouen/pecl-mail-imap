--TEST--
imap_close() with CL_EXPUNGE does not close a prototype stream
--EXTENSIONS--
imap
--FILE--
<?php
foreach (['{localhost/imap}', '{localhost/pop3}', '{localhost/nntp}'] as $mailbox) {
    $imap = imap_open($mailbox, '', '', OP_PROTOTYPE);
    var_dump(imap_close($imap, CL_EXPUNGE));
}
echo "Done\n";
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
Done
