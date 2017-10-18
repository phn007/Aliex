<?php
class GenerateContent {

	function getPostContent($productList, $vars) {
		foreach ($productList as $list ) {
			$list['domain'] = $vars['domain'];

			$art = new Article();
			$article = $art->getArticleFile($vars);
			foreach($article as $key => $val) $list[$key] = $val;

			$ad = new AdText();
			$adText = $ad->getAdText($list['title'], $vars);
			foreach($adText as $key => $text) $list[$key] = trim($adText[$key]);
			
			$layout = $this->getLayout($vars);
			$layout = $this->replaceLayout($layout, $list);

			$data['title'] 		 = $adText['post_title'];
			$data['content'] 	 = $layout;
			$data['postType'] 	 = 'post';
			$data['category']    = $this->getPostCatFromStrCategories($vars['category']);
			$data['postStatus']  = 'publish';
			$data['metaKeyword'] = $adText['meta_keyword'];
			$data['metaDesc']	 = $adText['meta_description'];
			$posts[] = $data;
		}
		$data = array(
			'posts' => $posts,
			'categories' => explode('/', rtrim($vars['category'],'/'))
		);
		return $data;
	}
	function replaceLayout($layout, $list) {
		$layout = preg_replace_callback('/{(?P<template_tag>[^}]+)}/', 
		function($matches) use(&$list){
			extract($matches);
			return isset($list[$template_tag]) ? $list[$template_tag] : $list['default'];
		},$layout);
		return $layout;
	}
	function getLayout($vars) {
		$path = $vars['layoutDir'] . '*.php';
		$layouts = glob($path);

		$cKey = array_keys($layouts);
		shuffle( $cKey);
		$file = $layouts[$cKey[0]];
		return file_get_contents($file);
	}//getLayout
	function getPostCatFromStrCategories($strCats) {
		$arr = explode('/',$strCats);
		$end = end($arr);
		return trim($end);
	}
}

