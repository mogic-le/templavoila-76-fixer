<?php
$db = new PDO('mysql:dbname=FIXMEDBNAME;host=localhost', 'FIXMEUSER', 'FIXMEPASS');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
$stmtUpdate = $db->prepare(
    'UPDATE tx_templavoila_datastructure'
    . ' SET dataprot = :dataprot WHERE uid = :uid'
);

$files = glob(__DIR__ . '/xml/*.fixed.xml');
foreach ($files as $file) {
    list($uid) = explode('.', basename($file));
    $stmtUpdate->execute(
        [':dataprot' => file_get_contents($file), ':uid' => $uid]
    );
}
?>
