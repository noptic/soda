<?php
require __DIR__.'/vendor/autoload.php';
Class Check{
    public static function that($result){
      if($result instanceof Exception) throw $result;
    }
}

$parser =  new Symfony\Component\Yaml\Parser();
$yaml = $parser->parse(file_get_contents(
        __DIR__.'/soda/daliaIT/soda/Indexer.yaml'
 ));
$yaml['namespace'] = "daliaIT\soda";
$yaml['name'] = "Indexer";
$template = new \daliaIT\soda\php\ClassTemplate();
$code = str_replace('<?php', '', $template->render($yaml));
eval($code);
$indexer = new daliaIT\soda\Indexer();
echo "Build files";
foreach ($indexer->buildIndex('soda') as $name => $meta){
    echo $name."\n";
    if($meta['type'] === 'php.Class'){
        $soda = $parser->parse(file_get_contents($meta['path']));
        $soda = array_replace($meta, $soda);
        $classCode = $template->render($soda);
        $classPath = 'src/'.str_replace('.', '/', $name).'.php';
       file_put_contents($classPath, $classCode);
    }
}

