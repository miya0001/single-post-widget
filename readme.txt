=== Single Post Widget ===
Contributors: miyauchi
Donate link: http://wpist.me/
Tags: widget
Requires at least: 3.2
Tested up to: 3.5
Stable tag: 0.4.0

Display single post from url on sidebar widget.

== Description ==

Display single post from url on sidebar widget.

[This plugin maintained on GitHub.](https://github.com/miya0001/single-post-widget)

= Some features: =

* Display single post from url on sidebar widget.
* You can customize HTML output.
* You can customize default HTML template on your plugin.

= filter hooks example =

Filter for default template.

`<?php
    add_filter("single-post-widget-template", "my_template");
    function my_template($template) {
        return '<div class="%class%"><a href="%post_url%">%post_thumb%</a></div>';
    }
?>`

Filter for stylesheet URI.

`<?php
    add_filter("single-post-widget-stylesheet", "my_style");
    function my_style($url) {
        return 'http://example.com/path/to/style.css';
    }
?>`

= Translators =

* Japanese(ja) - [Takayuki Miyauchi](http://firegoby.theta.ne.jp/)

Please contact to me.

* http://wpist.me/ (en)
* http://firegoby.jp/ (ja)
* @miya0001 on twitter.
* https://github.com/miya0001/single-post-widget

= Contributors =

* [Takayuki Miyauchi](http://firegoby.jp/)

== Installation ==

* A plug-in installation screen is displayed on the WordPress admin panel.
* It installs it in `wp-content/plugins`.
* The plug-in is made effective.

== Changelog ==

= 0.1.0 =
* The first release.

== Credits ==

This plug-in is not guaranteed though the user of WordPress can freely use this plug-in free of charge regardless of the purpose.
The author must acknowledge the thing that the operation guarantee and the support in this plug-in use are not done at all beforehand.

== Contact ==

twitter @miya0001
