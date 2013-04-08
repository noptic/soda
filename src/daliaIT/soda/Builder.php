<?php
namespace daliaIT\soda;
use Exception,
    OutOfBoundsException,
    RuntimeException;
/**
 * Uses templates to turn soda code into native source code. 
 * @author Oliver Anan <oliver@ananit.de>
 * @package dalia-it/soda
 * @since v0.0.1
 */

class Builder extends Processor {
    /**
     * @var array of templates, used to render soda code as native code.
     */
    protected $templates = array();

    /**
     * return this
     */
    public function build ($targetDirectory,array $index,array $defaults){
        try{
          foreach ($index as $name => $meta){
            $template = $this->getTemplate($meta['type']);
            $soda = array_replace($defaults, $meta, $this->readSodaFile($meta['path']));
            $classCode = $template->render($soda);
            $classPath = "$targetDirectory/".str_replace('.', '/', $name).'.php';
            $this->writeFile($classPath, $classCode);
          }
        }
        catch(Exception $e){
          throw new RuntimeException("Build failed",0,$e);
        }
    }

    /**
     * return template
     */
    public function getTemplate($type){
        if(! $this->hasTemplate($type)) throw new OutOfBoundsException(
          "No template registered for the type '{$meta['type']}'"
        );
        $templates = $this->getTemplates();
        return $templates[$type];
    }

    /**
     * return array
     */
    public function getTemplates(){
        return $this->templates;
    }

    /**
     * return bool
     */
    public function hasTemplate($type){
        $templates = $this->getTemplates();
        return isset($templates[$type]);
    }

    /**
     * return Builder
     */
    public function setTemplate($type, Template $template){
        $templates = $this->getTemplates();
        $templates[$type] = $template;
        $this->setTemplates($templates);
        return $this;
    }

    /**
     * return BUilder
     */
    public function setTemplates(array $templates){
        $this->templates = $templates;
        return $this;
    }

    public function writeFile($path, $contents){
        $parts = explode('/',$path);
        $file = array_pop($parts);
        $directory = implode('/',$parts);
        if(!file_exists($directory)){
          mkdir($directory,0777,true);
        }
        file_put_contents($path,$contents);
    }

}
