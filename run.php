<?php
require __DIR__.'/vendor/autoload.php';
$indexer = new daliaIT\soda\Indexer();
$builder = new daliaIT\soda\Builder();
$builder->setTemplate('php.Class', new daliaIT\soda\php\ClassTemplate());
$builder->setTemplate('php.Interface', new daliaIT\soda\php\InterfaceTemplate());
try{
    $index = $indexer->buildIndex('soda');
    $builder->build('src', $index, ['tags' => [
        'author'  => 'Oliver Anan <oliver@ananit.de>',
        'package' => 'dalia-it/soda',
        'since'   => 'v0.0.1'
    ]]);
}
catch(Exception $e){
    $exception = $e;
    while($exception){
        echo "Exption:\n".$exception->getMessage()."\n";
        $exception = $exception->getPrevious();
    }
    echo $e->getTraceAsString();
}

