<?php

add_action('admin_menu', 'tsd_plugin_settings');

function tsd_plugin_settings() {

    add_options_page('Tiny PNG for WordPress', 'Tiny PNG for WordPress', 'administrator', 'tiny_png_settings', 'tiny_png_settings_display');

}

function tiny_png_settings_display(){
        $api_key = (get_option('tsd_tpng_api_key') != '') ? get_option('tsd_tpng_api_key') : '';

        $html = '
                <div class="wrap">
                        <form action="options.php" method="post" name="options">
                                <h2>Tiny PNG for WordPress</h2>
                                '.wp_nonce_field('update-options').'<hr />
                                <label><strong>API Key</strong></label>
                                <p>Please place your Tiny PNG API key in this area. You can sign up for an API key by going to https://tinypng.com/developers.</p>
                                <input type="text" name="tsd_tpng_api_key" value="'.$api_key.'" />

                                <input type="hidden" name="action" value="update" />

                                <input type="hidden" name="page_options" value="tsd_tpng_api_key" />

                                <input type="submit" name="Submit" value="Update" class="button button-primary button-large" />


                        </form>
                </div>
        ';
        echo $html;



}

?>
