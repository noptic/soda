<?php
require __DIR__.'/vendor/autoload.php';
$indexer = new daliaIT\soda\Indexer();
$index = $indexer->buildIndex('soda');
$builder = new daliaIT\soda\Builder();
$builder->setTemplate('php.Class', new daliaIT\soda\php\ClassTemplate());
$builder->build('src', $index, ['tags' => [
    'author'  => 'Oliver Anan <oliver@ananit.de>',
    'package' => 'dalia-it/soda',
    'since'   => 'v0.0.1'
]]);

