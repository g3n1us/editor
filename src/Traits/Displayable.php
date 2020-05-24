<?php
namespace G3n1us\Editor\Traits;

trait Displayable{
  public function display($data = []){
    $template = $this->getTemplate();
    $data = array_merge($this->toArray(), $data);
    $key = snake_case(class_basename(get_class($this)));
    $data[$key] = $this;
    return view($template, array_merge($this->toArray(), $data))->__toString();
  }
  
  private function getTemplate(){
	  if(view()->exists($this->template)){
		  return $this->template;
	  }
	  return config('g3n1us_editor.page_template', 'default');
  }
  
	public function getTemplateAttribute(){
		if($this->metadata){
			return @$this->metadata->template;
		}
		
		return null;
	}
}
