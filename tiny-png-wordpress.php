<?php
/*
Plugin Name: Tiny PNG for WordPress
Plugin URI: http://www.thoughtspacedesigns.com/plugins/wordpress-plugins/tiny-png-wordpress
Description: Runs any PNG uploaded to the WordPress media gallery through the super-powered Tiny PNG. (https://tinypng.com)
Version: 1.0.0
Author: Thought Space Designs
Author URI: http://www.thoughtspacedesigns.com

Released under the GPL v.2, http://www.gnu.org/licenses/gpl-2.0.html

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.
*/




/**-----------------------------*
 * Admin Interface
**------------------------------*/


include('admin-interface.php');



/**----------------------------*
 * Optimizer
**-----------------------------*/







function tsd_tpng_filter_uploaded_images( $meta, $id ) {
 
	// Make sure the upload is an image
	if ( $id && false === wp_attachment_is_image( $id ) ) {
		return $meta;
	}
 
	// Fetch the image file path
	$attachment_file_path = get_attached_file( $id );
 
	// See what fields exist
	// Change the properties of $meta as needed
 


	$upload_dir = wp_upload_dir();
	$input = get_attached_file( $id );
	$type = $meta['sizes']['thumbnail']['mime-type'];	



	$key = get_option('tsd_tpng_api_key');
	$output = $input;

	if($key != "" && $type == "image/png" OR $key != "" && $type == "image/jpeg"){

	$url = "https://api.tinypng.com/shrink";
	$options = array(
	  "http" => array(
	    "method" => "POST",
	    "header" => array(
	      "Content-type: image/png",
	      "Authorization: Basic " . base64_encode("api:$key")
	    ),
	    "content" => file_get_contents($input)
	  ),
	  "ssl" => array(
	    /* Uncomment below if you have trouble validating our SSL certificate.
	       Download cacert.pem from: http://curl.haxx.se/ca/cacert.pem */
	    "cafile" => __DIR__ . "/cacert.pem",
	    "verify_peer" => true
	  )
	);

	$result = fopen($url, "r", false, stream_context_create($options));
	if ($result) {
	  /* Compression was successful, retrieve output from Location header. */
	  foreach ($http_response_header as $header) {
	    if (substr($header, 0, 10) === "Location: ") {
	      file_put_contents($output, fopen(substr($header, 10), "rb", false));
	    }
	  }
	} else {
	  /* Something went wrong! */
	  print("Compression failed");
	}
	

	
}

	return $meta;
}
 
add_filter( 'wp_generate_attachment_metadata', 'tsd_tpng_filter_uploaded_images', 10, 2 );




?>
