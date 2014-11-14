<?php
/*
Plugin Name: Fix WP default rss widget
Version: 1.0
Plugin URI: http://www.edouardlabre.com
Description: Fix Google Alerts rss url and Yahoo Pipes rss url for Wordpress Default RSS plugin
Author: Edouard Labre
Author URI: http://www.edouardlabre.com

----

Copyright 2014 - Edouard Labre

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
*	Fix Google alerts rss url (no https) and Yahoo Pipes 
*	(set _render=rss at first _GET params) by filtering $instance['url'] param
*	for use with Flux : The default Wordpress rss widget
*	
*	@author Edouard Labre
*	@param array $instance
*	@param array $new_instance
*	@param array $old_instance
*	@param WP_Widget $widget
*	@return array $instance
*/
function fix_wp_rss_widget_url( $instance, $new_instance, $old_instance, $widget ) {
	// Action on WP RSS widget only
	if( $widget->id_base !== 'rss' ) {
		return $instance;
	}
	// Google Alerts : no https
	if( substr ( $instance['url'], 0, 18 ) == 'https://www.google' ) {
		$instance['url'] = str_replace('https://www.google', 'http://www.google', $instance['url'] );
		
	// Yahoo pipes : add _render=rss on beginning of arguments
	} else if( strstr( $instance['url'], '/pipe.run?_id=' ) ) {
		$instance['url'] = str_replace('pipe.run?', 'pipe.run?_render=rss&', $instance['url'] );
	}
	// Return instance
	return $instance;
}
// Add filter on widget parameter saving action
add_filter( 'widget_update_callback', 'fix_wp_rss_widget_url', 99, 4 );