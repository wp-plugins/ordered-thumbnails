<?php

/*
Plugin Name: Ordered Thumbnails
Plugin URI: http://mekosh.org/projects/ordered-thumbnails
Description: This plugin adds a template tag that will either display a thumbnail for a post or return the thumbnail URL, width, and height based on images that have been attached to a post. In posts with more than one image attached, thumbnail will be chosen based on order set in the gallery for each post.
Version: 1.0
Author: Stephen Mekosh
Author URI: http://mekosh.org/



Copyright 2009 Stephen Mekosh (email : info@speedwebdesign.com)

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
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



// main function for this plugin
function ordered_thumbnails( $display = 'true', $class='' ) {
	
	global $post;
	
	// get all image attachments for this post
	$images = get_children( array( 'post_type' => 'attachment', 'post_parent' => $post->ID, 'post_mime_type' => 'image', order=>"asc" ) );
	
	// if the post has image attachments
	if( $images !== false ) {
		
		// find the image in position 1
		foreach($images as $i) {
			if ( $i->menu_order == 1 ) {
				$img_id = $i->ID;
			}
		}
		
		// if the images were unordered
		if ( $img_id == '' ) {
			$i = array_slice( $images, 0, 1 );
			$img_id = $i[0]->ID;
		}
		
		// get image data
		$image = wp_get_attachment_image_src( $img_id, 'thumbnail' );
		
		$result = array( 
			'url'		=> $image[0],
			'width'		=> $image[1],
			'height'	=> $image[2]
		);
		
		// should the image be displayed or should data be returned as an array?
		if ( $display == 'true' ) {
			return _e( '<img src="'.$result['url'].'" width="'.$result['width'].'" height="'.$result['height'].'" class="'.$class.'" />' );
		} else {
			return $result;
		}
		
	} else {
		
		// post does not have any image attachments
		return (bool) false;
		
	}	
}

// create template tag "ordered_thumbnails"
add_action( 'ordered_thumbnails', 'ordered_thumbnails', 2 );

?>