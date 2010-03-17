<?php
/*
Plugin Name: Code Here
Plugin URI: https://github.com/soska/CodeHere-plugin/
Description: Let you upload source code via the file upload dialog, link it in your post and have it syntax-highlighted. It works somewhat gist.github.com. The text highlighting is made by TextHighlighter (http://pear.php.net/package/Text_Highlighter) a Pear class, modified in this plugin to work without Pear.
Author: Armando Sosa
Version: 1.0
Author URI: http://dupermag.com/
License: WTFPL
*/

/*
// CodeHere Plugin
// (c) Armando Sosa
// WTFPL License
// (http://sam.zoy.org/wtfpl/)
*/



// error_reporting(E_ALL);

function codehere_init(){
	$css = WP_PLUGIN_URL . '/codehere/code.css';
	wp_enqueue_style('codehere',$css);
}

function codehere_footer(){
	global $codehereFooter;
	
	if ($codehereFooter) {
		?>
<!-- Code Here Javascript -->
<script type="text/javascript">
	(function($){
		$('.codehere a.show-raw').click(function(e){
			e.preventDefault();
			var $codeHere = $(this.parentNode.parentNode);
			$codeHere.children('div').toggle();
		});
	})(jQuery);
</script>
		<?php
	}
	
}


function codehere($atts){

	global $post, $codehereFooter;		

	if (empty($atts)) {
		return;
	}

	if (isset($atts['file'])) {
		$fileName = $atts['file'];
	}else{
		$fileName = $atts[0];		
	}
	
	
	$fileType = "html";
	if (isset($atts['lang'])) {
		$fileType = $atts['lang'];
	}


	$attachmentPath = '';


	// get the attachments
	$attachments = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'order' => 'ASC', 'orderby' => 'guid') );
	// pr($attachments);
	foreach ($attachments as $attachment) {
		$attBaseName = preg_replace('/_\..+$/','',basename($attachment->guid));
		if ($fileName == $attBaseName){
			$downloadLink = $attachment->guid;
			$uploadDir = wp_upload_dir();
			list($prefix,$sufix) = explode('uploads',$attachment->guid);
			$attachmentPath = $uploadDir['basedir'].$sufix;
			continue;			
		}
	}
	
	if (!empty($attachmentPath) && file_exists($attachmentPath)) {

		// Only now, that we'll use it. Require the highlighter library.
		require_once "Highlighter.php";
		$codehereFooter = true;
		
		// get the attachment contents
		$file = file_get_contents($attachmentPath);
				
		$highlighter = Text_Highlighter::factory(strtoupper($fileType),array('numbers'=>HL_NUMBERS_TABLE));			
		
		$output = "<div class=\"codehere\">";
		$output.= "<p><a class=\"show-raw\" href=\"#\">view raw</a> | ";
		$output.= "<a href=\"$downloadLink\" target=\"_blank\">download $fileName &darr;</a></p>";
		$output.= "<div class=\"higlighted\">";
		$output.= $highlighter->highlight($file);
		$output.= "</div>";
		$output.= "<div class=\"raw\" style=\"display:none;\"><pre>";
		$output.= htmlspecialchars($file);		
		$output.= "</pre></div>";
		$output.= "</div>";
				
		return  $output;
		
	}
	
}


function codehere_types($mimes) {
  $mimes['php'] = 'application/php';
  $mimes['rb'] = 'application/ruby';
  $mimes['py'] = 'application/python';
  return $mimes;
}

add_shortcode('codehere', 'codehere');
add_action('init', 'codehere_init');
add_action('wp_footer', 'codehere_footer');

// I don't know if this could cause a security breach, so it's commented by default.
// add_filter('upload_mimes','codehere_types');


?>
