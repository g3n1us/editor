<?php
namespace G3n1us\Editor\Traits;

trait Displayable{
  public function display($data = []){
    $template = $this->getTemplate();
//     dd($this->toArray());
//     dd(array_merge($this->toArray(), $data));
    $data = array_merge($this->toArray(), $data);
    $key = snake_case(class_basename(get_class($this)));
    $data[$key] = $this;
    return view($template, array_merge($this->toArray(), $data))->__toString();
  }
  
  private function getTemplate(){
    return isset($this->template) ? $this->template : config('g3n1us_editor.page_template', 'default');
  }
}
