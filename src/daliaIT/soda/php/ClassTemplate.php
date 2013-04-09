<?php
namespace daliaIT\soda\php;
/**
 * @author Oliver Anan <oliver@ananit.de>
 * @package dalia-it/soda
 * @since v0.0.1
 */

class ClassTemplate extends InterfaceTemplate {
    /**
     * @return this
     */
    public function writeBody($data){
        $this
          ->writeOpenBody($data)
          ->writeProperties($data)
          ->writeMethods($data)
          ->writeCloseBody($data);
        return $this;
    }

    public function writeImplements($data){
        if(isset($data['implements']) && $data['implements']) {
          $data['implements'] = (array) $data['implements'];
          sort($data['implements']);
          $this->write('implements '.implode(', ',$data['implements']).' ');
        }
        return $this;
    }

    public function writeMethods($data){
        if( (!isset($data['methods'])) || !$data['methods']) return $this;
        \ksort($data['methods']);
        foreach($data['methods'] as $prefix => $methods)
          ksort($methods);
          foreach ($methods as $declaration => $code):
            $lines = \explode("\n", $code);
            $lineCount = count($lines);
            $commentLines = array();
            for($i=0; $i<$lineCount; $i++){
              $line = ltrim(array_shift($lines));
              if(ltrim($line{0} === '*')){
                $commentLines[] = ltrim($line, "* "); 
              } else {
                array_unshift($lines, $line);
                break;
              }
            }
            $docComment = trim(implode("\n", $commentLines));
            $code = implode("\n",$lines);
            if($docComment) $this->writeDocComment($docComment,1);
            $this->writeln("$prefix function $declaration{",1);
            $this
              ->writeln("$code",2)
              ->writeln('}',1)
              ->writeln();
          endforeach;
        enforeach;
        return $this;
    }

    /**
     * @return this
     */
    public function writeName($data){
        $this
          ->writeln()
          ->write("class {$data['name']} ")
          ->writeExtends($data)
          ->writeImplements($data);
        return $this;
    }

    public function writeProperties($data){
        if((! (isset($data['properties']) && $data['properties']))) 
            return $this;
        ksort($data['properties']);
        foreach ($data['properties'] as $prefix => $properties):
            ksort($properties);
            foreach ($properties as $declaration => $description):
                $this
                    ->writeDocComment("@var $description",1)
                    ->writeln("$prefix \$$declaration;",1)
                    ->writeln();
            endforeach;
        endforeach;
        
        return $this;
    }

}
