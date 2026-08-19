<?php
do_action('qm/debug','helpers sind da');
function rot_get_svg($src = '', $make_ids_unique = false){
	if(!$src){
		$src = get_stylesheet_directory() . '/svg/logo.svg';
	}

	if(
		!is_string($src)
		|| $src === ''
		|| !file_exists($src)
		|| !is_file($src)
		|| !is_readable($src)
		|| strtolower(pathinfo($src, PATHINFO_EXTENSION)) !== 'svg'
	){
		return '<!-- rot_get_svg: invalid or missing svg file -->';
	}

	$svg_str = file_get_contents($src);

	if($svg_str === false || $svg_str === ''){
		return '<!-- rot_get_svg: could not read svg file -->';
	}

	$svg_check = strtolower($svg_str);

	if(
		strpos($svg_check, '<script') !== false
		|| strpos($svg_check, 'javascript:') !== false
		|| strpos($svg_check, '<foreignobject') !== false
		|| preg_match('/\son[a-z]+\s*=/i', $svg_str)
	){
		return '<!-- rot_get_svg: blocked potentially unsafe svg content -->';
	}

	if($make_ids_unique){
		$svg_str = rot_svg_make_ids_unique($svg_str);
	}
	$svg_str = rot_svg_add_class($svg_str, 'rot-svg-svg');

	return '<!--rot svg-->'.$svg_str.'<!--/rot svg-->';
}
add_shortcode('rot_get_svg_short','rot_get_svg_short');
if(!function_exists('rot_get_svg_short')){
	function rot_get_svg_short($atts){
		$defaults = [
			'src_' => false,
			'src_from_stylesheet_dir' => '/svg/logo.svg',
			'class' => 'rot-svg',
		];
		$a = shortcode_atts($defaults, $atts);

		$rel_path = $a['src_'] ?: $a['src_from_stylesheet_dir'];

		if(
			!is_string($rel_path)
			|| $rel_path === ''
			|| strpos($rel_path, '..') !== false
		){
			return '';
		}

		if(substr($rel_path, 0, 1) !== '/'){
			$rel_path = '/' . $rel_path;
		}

		$base_dir = realpath(get_stylesheet_directory());

		if(!$base_dir){
			return '';
		}

		$src_path = realpath($base_dir . $rel_path);

		if(
			!$src_path
			|| strpos($src_path, $base_dir) !== 0
			|| !is_file($src_path)
			|| !is_readable($src_path)
			|| strtolower(pathinfo($src_path, PATHINFO_EXTENSION)) !== 'svg'
		){
			return '';
		}

		$svg = rot_get_svg($src_path);

		if(!$svg){
			return '';
		}

		return '<figure class="rot-svg-relative ' . esc_attr($a['class']) . '">' . $svg . '</figure>';
	}
}


function rot_svg_make_ids_unique($svg_str, $hash = ''){
	if(!$svg_str || !is_string($svg_str)){
		return $svg_str;
	}

	if(!$hash){
		$hash = substr(wp_generate_password(3, false, false), 0, 3);
	}

	preg_match_all('/\sid=(["\'])([^"\']+)\1/i', $svg_str, $matches);

	if(empty($matches[2])){
		return $svg_str;
	}

	$ids = array_unique($matches[2]);

	foreach($ids as $id){
		$new_id = $id . '-' . $hash;

		$svg_str = preg_replace('/(\sid=(["\']))' . preg_quote($id, '/') . '((["\']))/i', '$1' . $new_id . '$3', $svg_str);

		$svg_str = preg_replace('/url\((["\']?)#' . preg_quote($id, '/') . '\1\)/i', 'url(#' . $new_id . ')', $svg_str);

		$svg_str = preg_replace('/(\s(?:href|xlink:href)=(["\']))#' . preg_quote($id, '/') . '((["\']))/i', '$1#' . $new_id . '$3', $svg_str);

		$svg_str = preg_replace('/(\s(?:clip-path|fill|filter|mask|stroke)=([\'"]))url\(#' . preg_quote($id, '/') . '\)((["\']))/i', '$1url(#' . $new_id . ')$3', $svg_str);
	}

	$svg_str = preg_replace_callback('/(\s(?:aria-labelledby|aria-describedby)=(["\']))([^"\']*)(\2)/i', function($m) use($ids, $hash){
		$val = $m[3];

		foreach($ids as $one_id){
			$val = preg_replace('/\b' . preg_quote($one_id, '/') . '\b/', $one_id . '-' . $hash, $val);
		}

		return $m[1] . $val . $m[4];
	}, $svg_str);

	return $svg_str;
}


function rot_svg_add_class($svg_str, $class = 'rot-svg-svg'){
	if(!$svg_str || !is_string($svg_str) || !$class){
		return $svg_str;
	}

	if(!preg_match('/<svg\b/i', $svg_str)){
		return $svg_str;
	}

	if(preg_match('/<svg\b[^>]*\sclass=(["\'])([^"\']*)\1/i', $svg_str)){
		$svg_str = preg_replace('/(<svg\b[^>]*\sclass=(["\']))([^"\']*)(\2)/i', '$1$3 ' . $class . '$4', $svg_str, 1);
	}else{
		$svg_str = preg_replace('/<svg\b/i', '<svg class="' . esc_attr($class) . '"', $svg_str, 1);
	}

	return $svg_str;
}

