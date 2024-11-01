<?php

/*
Plugin Name: Social Icon Links
Plugin URI: http://facebook.com/svincoll4
Description: To customize the social icon link with your own icons easier.
Author: Phuc Pham
Version: 1.2
*/

class CGI_IconLinkWidget extends WP_Widget {
	
	protected $limit = 10;
	
	function CGI_IconLinkWidget() {
		$widget_options = array(
		'classname'		=>		'cgi-icon-links',
		'description' 	=>		'Widget which puts the social icons and links on your website.'
		);
		
		$control_options = array(
	      'width' =>900
	   );
		
		parent::WP_Widget('cgi-icon-links-widget', 'Icon Links', $widget_options, $control_options);
	}
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		echo $before_widget; 
		
		$title = apply_filters( 'widget_title', isset($instance['title'])?$instance['title']:'' );
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		$html = "<div class='social-links'>";
		
		for($idx=1; $idx<=$this->limit; $idx++){
			$title = esc_attr(isset($instance["title_".$idx])?$instance["title_".$idx]:'');
			$icon_url = esc_attr(isset($instance["icon_".$idx])?$instance["icon_".$idx]:'');
			$social_url = esc_attr(isset($instance["url_".$idx])?$instance["url_".$idx]:'');
            $open = esc_attr(isset($instance["open_".$idx])?$instance["open_".$idx]:'');
            $target = $open == 1 ? "target='_blank'" : "";
            $css_class = esc_attr(isset($instance["cssclass_".$idx])?$instance["cssclass_".$idx]:'');
			
			if( empty($title) && empty($icon_url) && empty($social_url))
				continue;

            $img = "";
            if( !empty($icon_url))
                $img = "<img src='{$icon_url}' alt='{$title}'/>";

			$html .= <<<EEE
			<span class='icon-item {$css_class}'>
				<a href="{$social_url}" {$target}>{$img}</a>
			</span>
			
EEE;
		}
		
		$html .= "</div>";
		
		echo $html;
		
		echo $after_widget;
		
	}

	function update($new, $old) {
		return $new;
	}
	
	function form($instance) {
		
		$html = '
		<p>
			<label for="'.$this->get_field_id('title').'">Title</label>
			<input type="text" id="'.$this->get_field_id('title').'"  name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" class="widefat"/>
		</p>
		';
		$table_id = "tbl-". $this->get_field_id('title');
		$html .= "<table id='".$table_id."'>";
		for($idx=1; $idx<=$this->limit; $idx++){
			
			$title_id = $this->get_field_id('title_'.$idx);
			$icon_id = $this->get_field_id('icon_'.$idx);
			$url_id = $this->get_field_id('url_'.$idx);
            $open_id = $this->get_field_id('open_'.$idx);
            $cssclass_id = $this->get_field_id('cssclass_'.$idx);
			
			$title_name = $this->get_field_name('title_'.$idx);
			$icon_name = $this->get_field_name('icon_'.$idx);
			$url_name = $this->get_field_name('url_'.$idx);
            $open_name = $this->get_field_name('open_'.$idx);
            $cssclass_name = $this->get_field_name('cssclass_'.$idx);

			
			$title_val = esc_attr(isset($instance['title_'.$idx])?$instance['title_'.$idx]:'');
			$icon_val = esc_attr(isset($instance['icon_'.$idx])?$instance['icon_'.$idx]:'');
			$url_val = esc_attr(isset($instance['url_'.$idx])?$instance['url_'.$idx]:'');
            $open_val = esc_attr(isset($instance['open_'.$idx])?$instance['open_'.$idx]:'');
            $cssclass_val = esc_attr(isset($instance['cssclass_'.$idx])?$instance['cssclass_'.$idx]:'');
            $open_checked = $open_val == 1 ? "checked" : "";
			
			if( !empty($icon_val))
				$img = "<img src='{$icon_val}' alt='' width='30'/>";
			else
				$img = "";
			
			$html .= <<<EEE
			<tr>
				<td width="30">
					{$img}
				</td>
				<td>
					<p>
						<label for="{$title_id}">Icon Title {$idx}</label>
						<input type="text" id="{$title_id}"  name="{$title_name}" value="{$title_val}" class="widefat" style="width:150px"/>
					</p>
				</td>
				<td>
					<p>
						<label for="{$icon_id}">Icon URL {$idx}</label>
						<input type="text" id="{$icon_id}"  name="{$icon_name}" value="{$icon_val}" class="widefat icon_url" style="width:250px"/>
					</p>
				</td>
				<td>
					<p>
						<label for="{$url_id}">Icon Link {$idx}</label>
						<input type="text" id="{$url_id}"  name="{$url_name}" value="{$url_val}" class="widefat" style="width:250px"/>
					</p>
				</td>
				<td>
					<p>
						<label for="{$cssclass_id}">CSS class {$idx}</label>
						<input type="text" id="{$cssclass_id}"  name="{$cssclass_name}" value="{$cssclass_val}" class="widefat" style="width:80px"/>
					</p>
				</td>
				<td>
				    <label><input type="checkbox" name="{$open_name}" id="{$open_id}" value="1" {$open_checked}/> Open Window</label>
				</td>
			</tr>
EEE;
			
		}
		
		$html .= "</table>";
		
		$html .= <<<EEE
		<script>
			jQuery('#{$table_id} .icon_url').blur(function(){
				var parent = jQuery(this).parents('tr');
				var url = jQuery(this).val();
				parent.find('td:first').html('<img src="'+url+'" alt="" width="30"/>');
			});
		</script>
		
EEE;
		
		echo $html;
		
	}
}

function cgi_icon_link_widget_init() {
	register_widget('CGI_IconLinkWidget');
}
add_action('widgets_init', 'cgi_icon_link_widget_init');

