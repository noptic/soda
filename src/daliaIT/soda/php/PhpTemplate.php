<?php
namespace daliaIT\soda\php;
use daliaIT\soda\Template;
/**
 * @author Oliver Anan <oliver@ananit.de>
 * @package dalia-it/soda
 * @since v0.0.1
 */

class PhpTemplate extends Template {
    /**
     * @return string
     */
    public function render($data){
        return $this
          ->writeOpenTag($data)
          ->writeNamespace($data)
          ->writeUse($data)
          ->writeDescription($data)
          ->writeName($data)
          ->writeBody($data)
          ->flush();
    }

    /**
     * @return this
     */
    public function writeBody($data){
        return $this;
    }

    /**
     * @return this
     */
    public function writeDescription($data){
        $text = '';
          if(isset($data['description'])){
            $text .= $data['description'];
          }
          if (isset($data['tags'])){
            foreach ($data['tags'] as $name => $value):
              $value = (array) $value;
              sort($value);
              foreach ($value as $valueMemebr):
                $text .= "@$name $valueMemebr\n";
              endforeach;
            endforeach;
          }
          return $this->writeDocComment($text);
    }

    public function writeDocComment($text='',$indent=0){
        $text = \str_replace("\n", "\n * ", trim($text));
        $this->writeln("/**\n * $text\n */",$indent);
        return $this;
    }

    /**
     * @return this
     */
    public function writeName($data){
        return $this;
    }

    /**
     * @return this
     */
    public function writeNamespace($data){
        if(isset($data['namespace'])) 
          $this->writeln("namespace {$data['namespace']};");
        return $this;
    }

    /**
     * @return this
     */
    public function writeOpenTag($data){
        //prevent following code from being parse as php
        $tag = implode('',array('<','?','p','h','p'));
        $this->write($tag."\n");
        return $this;
    }

    /**
     * @return this
     */
    public function writeUse($data){
        if(isset($data['use']) && $data['use']){
          $data['use'] = (array) $data['use'];
          sort($data['use']);
          $this->writeln('use '.implode(",\n    ",$data['use']).';');
        }
        return $this;
    }

}
