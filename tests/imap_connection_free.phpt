--TEST--
IMAP\Connection objects are created and freed correctly
--EXTENSIONS--
imap
--ENV--
USE_ZEND_ALLOC=0
--FILE--
<?php
// OP_PROTOTYPE returns the driver's prototype stream without connecting
$connections = [];
for ($i = 0; $i < 100; $i++) {
    foreach (['{localhost/imap}', '{localhost/pop3}', '{localhost/nntp}'] as $mailbox) {
        $connections[] = imap_open($mailbox, '', '', OP_PROTOTYPE);
    }
}
var_dump(count($connections), $connections[0] instanceof IMAP\Connection);
$connections = null;
gc_collect_cycles();

try {
    new IMAP\Connection();
} catch (Error $e) {
    echo $e->getMessage(), "\n";
}
echo "Done\n";
?>
--EXPECT--
int(300)
bool(true)
Cannot directly construct IMAP\Connection, use imap_open() instead
Done
