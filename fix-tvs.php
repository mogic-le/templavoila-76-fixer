<?php
if ($argc < 2) {
    echo "XML file path missing\n";
    exit(1);
}
$file = $argv[1];
if (!file_exists($file)) {
    echo "XML file does not exist\n";
    exit(1);
}

if (filesize($file) == 0) {
    //empty, that's ok
    exit(0);
}

$sx = simplexml_load_file($file);
if (!$sx instanceof SimpleXMLElement) {
    file_put_contents('php://stderr', "Invalid XML in $file\n");
    exit(2);
}

//move <type> inside <tx_templavoila>
$elems = $sx->xpath('//*[tx_templavoila and type and not(section)]');
foreach ($elems as $elem) {
    $elem->tx_templavoila->type = $elem->type;
    unset($elem->type);
}

//add <title> ourside <tx_templavoila> for containers
// first only for containers within sections
$elems = $sx->xpath('//*[@type="array" and tx_templavoila/title and not(title) and ../../section="1"]');
foreach ($elems as $elem) {
    $elem->title = $elem->tx_templavoila->title;
}

//fix browse_links.php
foreach ($sx->xpath('//link[script]') as $elem) {
    $script = (string)$elem->script;
    if ($script == 'browse_links.php?mode=wizard') {
        unset($elem->script);
        $m =  $elem->addChild('module');
        $m['type'] = 'array';
        $m->addChild('name', 'wizard_element_browser');
        $up = $m->addChild('urlParameters');
        $up['type'] = 'array';
        $up->addChild('mode', 'wizard');

    } else {
        file_put_contents('php://stderr', 'Unsupported wizard script: ' . $script);
        exit(10);
    }
}


//format XML as it was before
//echo $sx->asXML();
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($sx->asXML());
$xml = $dom->saveXML(null, LIBXML_NOEMPTYTAG);
echo preg_replace_callback(
    '#^( )+#m',
    function ($matches) {
        return str_repeat("\t", strlen($matches[0]) / 2);
    },
    $xml
);
?>