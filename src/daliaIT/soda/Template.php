<?php
namespace daliaIT\soda;
/**
 * Base class for soda code generators.
 * Provides methods for buffered text generation.
 * @author Oliver Anan <oliver@ananit.de>
 * @license MIT
 */

class Template {
    /**
     * @var string stores output during code generation.
     */
    protected $buffer = array();

    /**
     * @var string used for 1 level of indent.
     */
    protected $indent = '    ';

    /**
     * get the content of the write buffer and empty it. 
     * @return string
     */
    public function flush (){
        $result = \implode('',$this->buffer);
        $this->buffer = array();
        return $result;
    }

    /**
     * get the string representing 1 level of indent
     * @return string
     */
    public function getIndent (){
        return $this->indent;
    }

    /**
     * @return this
     */
    public function setIndent ($value){
        $this->indent = $value;
        return $this;
    }

    public function write ($text=''){
        $this->buffer[] .= $text;
        return $this;
    }

    public function writeln ($text='', $indent=''){
        if($text === '' || $text === null):
            $this->write(PHP_EOL);
            return $this;
        endif;
        $text = \rtrim($text);
        foreach (\explode("\n",$text) as $line):
            if($indent) $line = str_repeat($this->indent, $indent).$line;
            $this->write($line.PHP_EOL);
        endforeach;
        return $this;
    }

}