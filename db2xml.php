<?php
$files = glob(__DIR__ . '/xml/*');
foreach ($files as $file) {
    unlink($file);
}

$db = new PDO('mysql:dbname=FIXMEDBNAME;host=localhost', 'FIXMEUSER', 'FIXMEPASS');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$q = $db->query('SELECT uid,dataprot FROM tx_templavoila_datastructure');
foreach ($q as $row) {
    file_put_contents(__DIR__ . '/xml/' . $row->uid . '.orig.xml', $row->dataprot);
}
?>
