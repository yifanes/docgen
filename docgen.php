<?php

# This file passes the content of the Readme.md file in the same directory
# through the Markdown filter. You can adapt this sample code in any way
# you like.

# Install PSR-0-compatible class autoloader

spl_autoload_register(function($class){
	require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});

# Get Markdown class
use \Michelf\MarkdownExtra;

# Read file and pass content through the Markdown parser
$text = file_get_contents('doc.md');
$content = MarkdownExtra::defaultTransform($text);

//解析affx数据

$content_array = file('doc.md');
$tpl_affx_data = array();
$i = 0;

foreach ($content_array as $value) {

	if($value[0] == '#' && $value[1] != '#'){
				$i++;
		preg_match('#^\#(.*) {(.*)}#', $value, $match);
		//var_dump($match);
		$tpl_affx_data[$i][$match[2]] = $match[1];

	}
	if($value[0] == '#' && $value[1] == '#'){
		preg_match('#^\#\#(.*) {(.*)}#', $value, $match);
		$tpl_affx_data[$i][$match[2]] = $match[1];
	}

}

//var_dump($tpl_affx_data);


$affx_html = '';
foreach ($tpl_affx_data as $v) {
	$flag = 0;
	$affx_html .= '<li>';
	foreach ($v as $key => $value) {
			if(!$flag){
				$affx_html .= '  <a href="'.$key.'">'.$value.'</a>';
				if(count($v) > 1 && !$flag){
					$affx_html .= '<ul class="nav">';
				}
				$flag = 1;
			}else{
				$affx_html .= '<li><a href="'.$key.'">'.$value.'</a></li>';
			}
	}

	if(count($v) > 1)
		$affx_html .= '</ul>';
	$affx_html .= '</li>';
}

$tpl_content = file_get_contents('api/index.tpl');
$tpl_content = preg_replace('#{{content}}#', $content, $tpl_content);
$tpl_content = preg_replace('#{{affx}}#', $affx_html, $tpl_content);

file_put_contents('api/index.html', $tpl_content);

