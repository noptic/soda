<?php
namespace daliaIT\soda\php;
use daliaIT\soda\Template;

class ClassTemplate extends Template{

    public function render($data){
        return $this
            ->writeOpenTag($data)
            ->writeNamespace($data)
            ->writeUse($data)
            ->writeDescription($data)
            ->writeClassName($data)
            ->writeExtend($data)
            ->writeImplements($data)
            ->writeBody($data)
            ->flush();
    }
    
    public function writeOpentag($data){
        $this->writeln('<?php');
        return $this;
    }
    
    public function writeNamespace($data){
        if(isset($data['namespace'])) 
            $this->writeln("namespace {$data['namespace']};");
        return $this;
    }
    
    public function writeUse($data){
        if(isset($data['use']) && $data['use']){
            $data['use'] = (array) $data['use'];
            sort($data['use']);
            $this->writeln('use '.implode(",\n    ",$data['use']).';');
        }
        return $this;
    }
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

    public function writeClassName($data){
        $this
            ->writeln()
            ->write("class {$data['name']} ");
        return $this;
    }
    
    public function writeExtend($data){
        if(isset($data['extends'])&& $data['extends'])
            $this->write("extends {$data['extends']} ");
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
    
    public function writeBody($data){
        $this
            ->writeOpenBody($data)
            ->writeProperties($data)
            ->writeMethods($data)
            ->writeCloseBody($data);
        return $this;
    }

    public function writeOpenBody($data){
        $this->writeln('{');
        return $this;
    }
    
    public function writeCloseBody($data){
        $this->writeln('}');
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
}

?>
