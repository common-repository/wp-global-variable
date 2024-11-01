=== WordPress Custom Global Variable ===
Contributors: biztechc
Tags: Global Variable, WP Global Variable, WordPress global variables, Global Variable for WordPress,Global Content Blocks
Requires at least: 3.6.1
Tested up to: 5.5
Stable tag: 3.0.0
License: GPLv2 or later


== Description ==
1. Using “WordPress Custom Global Variable” you can create your own short codes to add reusable code snippets, PHP or HTML that may include contact forms, iframes, opt-in boxes, Adsense code, etc. 
2. Into your website pages and blog posts as well as widgets and directly into the PHP content also. 
 
== Installation ==

1. Copy the entire /global-variable/ directory into your /wp-content/plugins/ directory.
2. Activate the plugin.
3. New Tab called Custom Variable will be generated.
4. You can add new variable.
5. Use short code at any pages/posts e.g.[global_variable variable_name='TEST1'] or in Code 
`<?php 
if(function_exists('define_variable') || function_exists('global_variable_func'))
            {   
                if(defined('TEST1'))
                {
                    echo TEST1;
                }
			}
?>`
OR
`<?php echo do_shortcode('[global_variable variable_name="TEST1"]');?>`

== Frequently Asked Questions ==
Is this plugin prepared for multisites? Yes.

= Requirements =



== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png


== Changelog ==
= 1.0.0 =
* First release
= 2.0.0 =
* Compatibility with WordPress version 5.3
= 2.0.1 =
* Compatibility with WordPress version 5.4
= 3.0.0 =
* Compatibility with WordPress version 5.5
