<?php
require __DIR__.'/vendor/autoload.php';
$parser =  new Symfony\Component\Yaml\Parser();
$yaml = $parser->parse(file_get_contents(
        __DIR__.'/soda/daliaIT/soda/Indexer.can'
 ));
$yaml['namespace'] = "daliaIT\soda";
$yaml['name'] = "Indexer";
$template = new \daliaIT\soda\php\ClassTemplate();
$code = $template->render($yaml);
$code = str_replace('<?php', '', $code);
eval($code);
$indexer = new daliaIT\soda\Indexer();
var_dump($indexer->getCanFiles('soda'));
?>
