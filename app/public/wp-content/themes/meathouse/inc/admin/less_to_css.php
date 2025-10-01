<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if(function_exists('jws_add_less')) {
    jws_add_less();
}


/** proces less files and generate meathouse-style.css in uploads folder */
{
    /**
     * Must be called in admin area to process less files on option save
     *
     * @param string $less_file
     * @param string $extension_name
     */

  
    function jws_action_theme_generate_styles()
    {
        
        global $meathouse_less_files, $meathouse_stylesheet_directory, $meathouse_template_directory;

        $meathouse_stylesheet_directory = get_stylesheet();
        $meathouse_template_directory = get_template();
        $meathouse_template_directory_uri = get_template_directory_uri();
        $upload_dir = wp_upload_dir();
        $style_dir = $upload_dir['basedir'];
        $autosave = true;
        $siteid = get_current_blog_id();
        $filename = 'jws-style.css';
      
        /**
         * Less files are generated in one file meathouse-style.css, in upload folder, after options are saved or if
         * meathouse-style.css file doesn't exist yet
         */
        if (!file_exists($style_dir . '/'.$filename.'') || $autosave ) {
          
            $errors = array();
            $style_less = locate_template('assets/css/less/style.less');
            /** show errors in admin if less files not found */
            if (!empty($errors)) {
             
                    /**
                     * backward compatibility if unyson isn't installed.
                     * this function jws_action_theme_generate_styles() is hooked to admin_notices action
                     */
                    ?>
                    <div class="error">
                        <p><?php echo implode("<br />\n", $errors); ?></p>
                    </div>
                    <?php
               

                /** stop trying to process less files if we got error */

                return;
            }
  
            try {
                /** Don't compress generated css file if developer mode is on */
                $options = (!WP_DEBUG) ? array('compress' => true) : array();


                /** Import less files from child theme if it is overwritten */
                $options['import_callback'] = 'verify_less_in_child_theme';
                if (!function_exists('verify_less_in_child_theme')) {
                    function verify_less_in_child_theme($evald)
                    {
                        global $meathouse_stylesheet_directory, $meathouse_template_directory;

                        $evaldPath = $evald->PathAndUri();
                        $full_path = preg_replace('/' . $meathouse_template_directory . '/', $meathouse_stylesheet_directory, $evaldPath[0]);

                        if (file_exists($full_path)) {
                            $uri = preg_replace('/' . $meathouse_template_directory . '/', $meathouse_stylesheet_directory, $evaldPath[1]);

                            return array($full_path, $uri);
                        }
                    }
                }
                
                if (class_exists('Less_Parser')) {
                    $sourceMap = false;
                    $options = array('sourceMap' => $sourceMap);
                    $parser = new Less_Parser($options);
                    $parser->parseFile($style_less, $meathouse_template_directory_uri);
                    if (is_rtl()) {
                        $parser->parseFile($rtl_less, $meathouse_template_directory_uri);
                    }

                    /**
                     * get values from admin styling and overwrite them when processing less files
                     */
                    $meathouse_modified_variables = array();
            


                    /**
                     * Parse all css and write generated styles to meathouse-style.css in upload folder,
                     * where should be granted write permission
                     */

                    // set time limit
                    @set_time_limit(600);

                    $css = $parser->getCss();


                    // init $wp_filesystem
                    {
                        if (!function_exists('request_filesystem_credentials')) {
                            load_template(ABSPATH . '/wp-admin/includes/file.php');
                        }

                        if (!defined('FS_METHOD')) {
                            define('FS_METHOD', 'direct');
                        }

                        if (false === request_filesystem_credentials('', 'direct')) {
                            return false;
                        }

                        if (!WP_Filesystem()) {
                            // our credentials were no good, ask the user for them again
                            request_filesystem_credentials('', 'direct', true);
                            return false;
                        }

                        global $wp_filesystem;
                    }
                    
                    if (!$wp_filesystem->put_contents($style_dir . '/'.$filename.'', $css)) {
                        esc_html_e("error write content", "meathouse");
                    }
                }

            } catch (Exception $e) {
                /** show parser errors in admin */
                    /**
                     * backward compatibility if unyson isn't installed.
                     * this function jws_action_theme_generate_styles() is hooked to admin_notices action
                     */
                    ?>
                    <div class="error">
                        <p><?php printf(esc_html__('lessphp fatal error:  %s', 'meathouse'), $e->getMessage()); ?></p>
                    </div>
                    <?php
               
            }


            /** show parsed files in admin on save options for debugging */
            if (WP_DEBUG) {
                $imported_files = $parser->allParsedFiles();
            }
        }
    }  
add_action ('redux/page/jws_option/load', 'jws_action_theme_generate_styles'); 
add_action ('redux/options/jws_option/saved', 'jws_action_theme_generate_styles'); 
}