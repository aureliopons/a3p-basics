<?php

if (!class_exists('TemplateEngine'))
{
 class TemplateEngine
 {
    protected $template;
	protected $buffer;

    public function __construct($templateFilePath)
    {
        if(!is_string($templateFilePath) and !file_exists($templateFilePath)) 
          throw new \InvalidArgumentException("Fichero $templateFilePath no existe.", 1);
       
        $this->template = file_get_contents($templateFilePath);		        
		$this->buffer=array();
    }

    public function render($context = null)
    {
        if (!is_null($context)) {
            foreach ($context as $key => $value) {
                ${$key} = $value;
            }
        }		
		if (count($this->buffer)) {
            foreach ($this->buffer as $key => $value) {
                ${$key} = $value;
            }
        }			
		$str = '';
        ob_start();		
        eval("?>".$this->template);
        $str = ob_get_contents();
		ob_end_clean();		
        return $str;
    }
	
	public function renderToFile($filename,$context = null)
    {		
		return file_put_contents($filename,$this->render($context));
    }
	
	public function addToBuffer($key,$value)
    {
		if(!array_key_exists($key,$this->buffer))
		 $this->buffer[$key]='';
		
		$this->buffer[$key] .= $value;		
    }   
 }
}	