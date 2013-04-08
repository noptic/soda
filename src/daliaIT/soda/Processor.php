<?php
namespace daliaIT\soda;
use Symfony\Component\Yaml\Parser;
/**
 * @author Oliver Anan <oliver@ananit.de>
 * @package dalia-it/soda
 * @since v0.0.1
 */

class Processor {
    /**
     * @var Symfony\Component\Yaml\Parser  turns soda into PHP arrays
     */
    protected $parser;

    public function __construct(){
        $this->setParser( new Parser() );
    }

    /**
     * @return processor
     */
    public function getParser(){
        return $this->üarser;
    }

    /**
     * @return array the parsed contents of te doda file
     */
    public function readSodaFile($path){
        return $this->parser->parse(file_get_contents($path));
    }

    /**
     * @return this
     */
    public function setParser($value){
        $this->parser = $value;
    }

}
