<?php
namespace daliaIT\soda;
use LogicExcption,
    RecursiveDirectoryIterator,
    RecursiveIteratorIterator,
    RuntimeException,
    YaLinqo\Enumerable;
/**
 * Scans source directorys and creates a index.
 * The index contains: 
 * @author Oliver Anan <oliver@ananit.de>
 */

class Indexer extends Processor {
    /**
     * @var string  used as regex pattern to detrminate if  a file should be indexed
     */
    protected $indexFilePattern = '|\.yaml$|';

    public function buildIndex($directorys){
        $index = array();
        $filesPerDirectory = $this->getCanFiles($directorys);
        foreach ($filesPerDirectory as $directory => $files){
          foreach($files as $file){
            $path = "$directory/$file";
            if(! is_readable($path) ) throw new RuntimeException(
              "Source file is not readable: '$path'"
            );
            $rawContents = file_get_contents($path);
            $can = $this->parser->parse( $rawContents );
            if(! isset($can['type']) ) throw new RuntimeException(
              "Missing type information: $path"
            );
            if (isset($can['name'])){
              $canName = $can['name'];
            } else {
              $canName = substr(
                str_replace('/','.',$file),
                0,
                strrpos($file,'.')
              );
            }
            $nameParts = explode('.',$canName);
            $shortName = array_pop($nameParts);
            $namespace = implode('\\',$nameParts);
            $index[$canName] = array(
              'type'      => $can['type'],
              'path'      => $path,
              'hash'      => md5(serialize($can)),
              'namespace' => $namespace,
              'name'      => $shortName
            );
          }
        }
        return $index;
    }

    /**
     * return array
     */
    public function getCanFiles ($directorys){
        $directorys = (array) $directorys;
        $files = array();
        foreach($directorys as $directory){
          $realDirectory = realpath($directory);
          $pathLength = strlen($realDirectory);
          $files[$directory] = array();
          $objects = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($realDirectory), 
            RecursiveIteratorIterator::SELF_FIRST
          );
          foreach($objects as $name => $object){
            $match = preg_match($this->indexFilePattern,$name);
            if ($match === false){ 
              throw new LogicException(
                "regex error, please check your pattern: "
                .$this->indexFilePattern
              );
            }
            if($match){
              $files[$directory][] = str_replace(
              DIRECTORY_SEPARATOR,
              '/',
              substr($name, $pathLength+1)
              );
            }
          }
        }
        return $files;
    }

}
