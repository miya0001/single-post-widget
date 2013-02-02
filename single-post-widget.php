<?php
/*
Plugin Name: Single Post Widget
Description: Display single post from url on sidebar widget.
Author: Takayuki Miyauchi
Version: 0.4.0
Author URI: http://firegoby.jp/
Plugin URI: http://firegoby.jp/wp/single-post-widget
Domain Path: /languages
Text Domain: single-post-widget
*/


class SinglePostWidget extends WP_Widget {

    private $num = 5;
    private $domain = "single-post-widget";

    function __construct() {
		$widget_ops = array(
            'description' => __('Display single selected Post or Page.', $this->domain)
        );
		$control_ops = array('width' => 400, 'height' => 350);
        parent::__construct(
            false,
            __('Single Post', $this->domain),
            $widget_ops,
            $control_ops
        );
    }

    public function form($instance) {
        // outputs the options form on admin
        $postid = (isset($instance['postid'])) ? $instance['postid'] : '';
        $pid = $this->get_field_id('postid');
        $pf = $this->get_field_name('postid');
        echo '<p>';
        echo __("Post or Page URL", $this->domain);
        echo "<br />";
        echo "<input type=\"text\" class=\"widefat\" id=\"{$pid}\" name=\"{$pf}\" value=\"{$postid}\" />";
        echo '</p>';

        $sizes = get_intermediate_image_sizes();
        $size = (isset($instance['size']) && $instance['size']) ? $instance['size'] : '';
        $sfield = $this->get_field_id('size');
        $sfname = $this->get_field_name('size');
        echo '<p>';
        echo __('Image size:', $this->domain);
        echo '<br />';
        $op = '<option value="%s"%s>%s</option>';
        echo "<select class=\"widefat\" id=\"{$sfield}\" name=\"{$sfname}\">";
        printf($op, '', '', '');
        foreach ($sizes as $s) {
            if ($s === $size) {
                printf($op, $s, ' selected="selected"', $s);
            } else {
                printf($op, $s, '', $s);
            }
        }
        echo "</select>";
        echo '</p>';

        $tpl_value = (isset($instance['tpl']) && $instance['tpl']) ? $instance['tpl'] : $this->template();
        $tpl_field = $this->get_field_id('tpl');
        $tpl_fname = $this->get_field_name('tpl');
        echo '<label for="'.$tpl_field.'">';
        echo __("Template:", $this->domain);
        echo '</label><br />';
        printf(
            '<textarea class="widefat" rows="16" cols="20" id="%s" name="%s">%s</textarea>',
            $tpl_field,
            $tpl_fname,
            htmlentities($tpl_value, ENT_QUOTES, 'UTF-8')
        );

        $tags = array(
            "%post_title%",
            "%post_date%",
            "%post_url%",
            "%post_thumb%",
            "%post_excerpt%",
            "%class%",
        );

        echo '<div style="margin:5px 0;">';
        echo '<code>'.join("</code>, <code>", $tags).'</code>';
        echo '</div>';
    }

    public function update($new_instance, $old_instance) {
        // processes widget options to be saved
        return $new_instance;
    }

    public function widget($args, $instance) {
        $pid = null;
        if (isset($instance['postid']) && preg_match("/^[0-9]+$/", $instance['postid'])) {
            $pid = $instance['postid'];
        } elseif (isset($instance['postid']) && $instance['postid']) {
            $pid = url_to_postid($instance['postid']);
        }
        if (!$pid) {
            return '';
        }
        $tpl  = ($instance['tpl']) ? $instance['tpl'] : $this->template();
        if (!isset($instance['size'])) {
            $instance['size'] = '';
        }

        $p = apply_filters('single_post_widget_post', get_post($pid));

        echo $args['before_widget'];
        echo $args['before_title'];
        echo esc_html($p->post_title);
        echo $args['after_title'];

        $class = array(
            $p->post_type.'-'.$pid,
            $p->post_type,
            'single-post-widget'
        );
        if ($instance['size']) {
            $class[] = 'size-'.$instance['size'];
        }
        if ($instance['size']) {
            $post_thumb = get_the_post_thumbnail($pid, $instance['size']);
        } else {
            $post_thumb = '';
        }
        $tpl = str_replace(
            '%post_date%',
            mysql2date(get_option("date_format"), $p->post_date, false),
            $tpl
        );
        $tpl = str_replace('%post_title%', esc_html($p->post_title), $tpl);
        $tpl = str_replace('%post_excerpt%', esc_html($p->post_excerpt), $tpl);
        $tpl = str_replace('%post_thumb%', $post_thumb, $tpl);
        $tpl = str_replace('%post_url%', esc_url(get_permalink($pid)), $tpl);
        $tpl = str_replace('%class%', join(' ', $class), $tpl);
        echo $tpl;
        echo $args['after_widget'];
    }

    private function template()
    {
        $html = '<div class="%class%">';
        $html .= '<div class="post-thumb"><a href="%post_url%">%post_thumb%</a></div>';
        $html .= '<div class="post-excerpt">%post_excerpt% <a href="%post_url%">&raquo; '.__('Read More', $this->domain).'</a></div>';
        $html .= '</div>';
        return apply_filters("single-post-widget-template", $html);
    }
}

class SinglePostWidgetInit {

function __construct()
{
    add_action('widgets_init', array(&$this, "widgets_init"));
    add_action("plugins_loaded", array(&$this, "plugins_loaded"));
    add_action("wp_head", array(&$this, "wp_head"));
}

public function wp_head()
{
    $url = plugins_url("", __FILE__).'/style.css';
    printf(
        '<link rel="stylesheet" type="text/css" media="all" href="%s" />'."\n",
        apply_filters("single-post-widget-stylesheet", $url)
    );
}

public function widgets_init()
{
    return register_widget("SinglePostWidget");
}

public function plugins_loaded()
{
    load_plugin_textdomain(
        "single-post-widget",
        false,
        dirname(plugin_basename(__FILE__)).'/languages'
    );
}

}

new SinglePostWidgetInit();

?>
