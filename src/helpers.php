<?php
  
  if(!function_exists('output_nav')){
    function output_nav(){
// /* 1:40:40 PM Localhost emammal_laravel */ SELECT * FROM `editor_pages` WHERE `path` NOT LIKE '%/%' LIMIT 0,10000;
//       $pages = G3n1us\Editor\Page::where('path', 'not like', '%/%')->get();
      $pages = G3n1us\Editor\Page::whereNull('parent_page_id')
        ->where('metadata->hidden_from_nav', '!=', "1")
        ->orWhereNull('metadata')
        ->get();
      return $pages;
    }
  }