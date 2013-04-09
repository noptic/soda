<?php
namespace daliaIT\soda\php;
/**
 * @author Oliver Anan <oliver@ananit.de>
 * @package dalia-it/soda
 * @since v0.0.1
 */

class InterfaceTemplate extends PhpTemplate {
    /**
     * @return this
     */
    public function writeBody($data){
        $this
          ->writeOpenBody($data)
          ->writeMethods($data)
          ->writeCloseBody($data);
        return $this;
    }

    public function writeCloseBody($data){
        $this->writeln('}');
        return $this;
    }

    public function writeExtends($data){
        if(isset($data['extends'])&& $data['extends'])
          $this->write("extends {$data['extends']} ");
        return $this;
    }

    public function writeMethods($data){
        if( (!isset($data['methods'])) || !$data['methods']) return $this;
        \ksort($data['methods']);
        foreach($data['methods'] as $prefix => $methods){
          ksort($methods);
          foreach ($methods as $declaration => $description){
            $this->writeDocComment($description,1);
            $this->writeln("$prefix function $declaration;",1);
            $this->writeln();
          }
        }
        return $this;
    }

    /**
     * @return this
     */
    public function writeName($data){
        $this
          ->writeln()
          ->write("interface {$data['name']} ")
          ->writeExtends($data);
        return $this;
    }

    public function writeOpenBody($data){
        $this->writeln('{');
        return $this;
    }

}
