<?php

/**
 * Template Functions
 *
 * Template functions specifically created for job listings.
 *
 * @link        https://wordpress.org/plugins/simple-job-board
 * 
 * @package 	Simple_Job_Board
 * @subpackage 	Simple_Job_Board/Templates
 * @since       2.1.0
 */
if (!function_exists('get_simple_job_board_template')) {

    /**
     * Get and include template files.
     * 
     * @since   2.1.0
     * 
     * @param   mixed   $template_name
     * @param   array   $args (default: array())
     * @param   string  $template_path (default: '')
     * @param   string  $default_path (default: '')
     * @return  void
     */
    function get_simple_job_board_template($template_name, $args = array(), $template_path = 'simple_job_board', $default_path = '') {

        if ($args && is_array($args)) {
            extract($args);
        }

        include( locate_simple_job_board_template($template_name, $template_path, $default_path) );
    }

}

if (!function_exists('locate_simple_job_board_template')) {

    /**
     * Locate a template and return the path for inclusion.
     *
     * This is the load order:
     *
     * yourtheme		/	$template_path	/	$template_name
     * yourtheme		/	$template_name
     * $default_path	/	$template_name
     *
     * @since   2.1.0
     * 
     * @param   string      $template_name
     * @param   string      $template_path (default: 'simple_job_board')
     * @param   string|bool $default_path (default: '') False to not load a default
     * @return  string      $template_name
     */
    function locate_simple_job_board_template($template_name, $template_path = 'simple_job_board', $default_path = '') {

        if (FALSE !== get_option('job_post_layout_settings')) {
            $jobpost_layout_option = get_option('job_post_layout_settings');
            if ('job_post_layout_version_one' === $jobpost_layout_option)
                $job_post_layout_version = 'v1/';

            if ('job_post_layout_version_two' === $jobpost_layout_option)
                $job_post_layout_version = 'v2/';
        } else {
            $job_post_layout_version = 'v1/';
        }

        // Look within passed path within the theme - this is priority

        $template = locate_template(
                array(
                    trailingslashit($template_path) . $job_post_layout_version . $template_name,
                )
        );

        // Get default template
        if (!$template && $default_path !== false) {
            $default_path = $default_path ? $default_path : untrailingslashit(plugin_dir_path(dirname(__DIR__))) . '/templates/' . $job_post_layout_version;

            if (file_exists(trailingslashit($default_path) . $template_name)) {
                $template = trailingslashit($default_path) . $template_name;
            }
        }

        // Return what we found
        return apply_filters('simple_job_board_locate_template', $template, $template_name, $template_path);
    }

}

if (!function_exists('get_simple_job_board_template_part')) {

    /**
     * Get template part (for templates in loops).
     *
     * @since   2.1.0
     * 
     * @param   string      $slug
     * @param   string      $name (default: '')
     * @param   string      $template_path (default: 'simple_job_board')
     * @param   string|bool $default_path (default: '') False to not load a default
     */
    function get_simple_job_board_template_part($slug, $name = '', $template_path = 'simple_job_board', $default_path = '') {

        $template = '';

        if ($name) {
            $template = locate_simple_job_board_template("{$slug}-{$name}.php", $template_path, $default_path);
        }

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/simple_job_board/slug.php
        if (!$template) {
            $template = locate_simple_job_board_template("{$slug}.php", $template_path, $default_path);
        }

        if ($template) {
            load_template($template, FALSE);
        }
    }

}

if (!function_exists('sjb_body_classes')) {

    /**
     * Add custom body classes
     * 
     * @since   2.1.0
     * @since   2.9.5   Added (array) with $classes variable.
     * 
     * @param   array   $classes
     * @return  array
     */
    function sjb_body_classes($classes) {

        $classes = (array) $classes;
        $classes[] = trim(sanitize_title(wp_get_theme()));

        if (is_sjb()) {
            $classes[] = 'sjb';
        }

        return array_unique($classes);
    }

}

add_filter('body_class', 'sjb_body_classes');

if (!function_exists('sjb_the_job_category')) {

    /**
     * Job Categories.
     * 
     * @since   2.1.0
     * @since   2.9.5   Added (array) with sjb_get_the_job_category($post).
     * @since   2.10.0   Replaces &nbps with space.
     */
    function sjb_the_job_category($post = NULL) {

        $job_categories = '';
        if ($job_categories = (array) sjb_get_the_job_category($post)) {
            $count = sizeof($job_categories);
            foreach ($job_categories as $job_category) {
                echo esc_attr($job_category->name);
                if ($count > 1) {
                    echo esc_html(', ');
                }
                $count--;
            }
        }
    }

}

if (!function_exists('sjb_get_the_job_category')) {

    /**
     * sjb_get_the_job_category function.
     *
     * @since   2.1.0 
     * @access  public
     * 
     * @param   mixed   $post (default: null)
     * @return  void
     */
    function sjb_get_the_job_category($post = NULL) {

        $post = get_post($post);
        if ($post->post_type !== 'jobpost') {
            return;
        }

        $categories = wp_get_post_terms($post->ID, 'jobpost_category');

        /**
         * Job Categories.
         * 
         * @since   2.1.0
         * 
         * @param   $categories   Job Categories.
         * @param   $post         Post Object.
         */
        return apply_filters('sjb_the_job_category', $categories, $post);
    }

}

if (!function_exists('sjb_the_job_type')) {

    /**
     * sjb_the_job_type function.
     * 
     * @since   2.1.0
     * @since   2.9.5   Added (array) with sjb_get_the_job_type($post).
     * 
     * @access  public
     * 
     * @return  void
     */
    function sjb_the_job_type($post = NULL) {

        if ($job_types = (array) sjb_get_the_job_type($post)) {
            $count = sizeof($job_types);
            foreach ($job_types as $job_type) {
                echo esc_attr(strip_tags($job_type->name));

                if ($count > 1) {
                    echo ', ';
                }
                $count--;
            }
        }
    }

}

if (!function_exists('sjb_get_the_job_type')) {

    /**
     * sjb_get_the_job_type function.
     *
     * @since   2.1.0 
     * @access  public
     * 
     * @param   mixed   $post (default: null)
     * @return  void
     */
    function sjb_get_the_job_type($post = NULL) {

        $post = get_post($post);

        if ($post->post_type !== 'jobpost') {
            return;
        }
        $types = wp_get_post_terms($post->ID, 'jobpost_job_type');

        /**
         * Job Type.
         * 
         * @since   2.1.0
         * 
         * @param   $types  Job Types.
         * @param   $post   Post Object.
         */
        return apply_filters('sjb_the_job_type', $types, $post);
    }

}

if (!function_exists('sjb_the_job_location')) {

    /**
     * sjb_the_job_location function.
     * 
     * @since   2.1.0 
     * @since   2.10.0   Replaces &nbps with space.
     * 
     * @return void
     */
    function sjb_the_job_location($post = NULL) {

        $post = get_post($post);
        if ($job_locations = (array) sjb_get_the_job_location($post)) {
            $count = sizeof($job_locations);
            foreach ($job_locations as $location) {
                echo esc_attr(strip_tags($location->name));
                if ($count > 1) {
                    echo esc_html(', ');
                }
                $count--;
            }
        }
    }

}

if (!function_exists('sjb_get_the_job_location')) {

    /**
     * sjb_get_the_job_location function.
     * 
     * @since   2.1.0 
     * 
     * @param   mixed $post (default: NULL)
     * @return  void
     */
    function sjb_get_the_job_location($post = NULL) {

        $post = get_post($post);

        if ($post->post_type !== 'jobpost') {
            return;
        }

        $locations = wp_get_post_terms($post->ID, 'jobpost_location');

        /**
         * Job Location.
         * 
         * @since   2.1.0
         * 
         * @param   $locations  Job Locations.
         * @param   $post       Post Object
         */
        return apply_filters('sjb_the_job_location', $locations, $post);
    }

}

if (!function_exists('sjb_the_company_name')) {

    /**
     * Display or retrieve the current company name with optional content.
     *
     * @since   2.1.0 
     * 
     * @param   mixed   $id (default: null)
     * @return  void
     */
    function sjb_the_company_name( $before = '', $after = '', $echo = true, $post = NULL) {

        $company_name = sjb_get_the_company_name($post);

        if (strlen($company_name) == 0)
            return;

        $company_name = esc_attr(strip_tags($company_name));
        $company_name = $before . $company_name . $after;

        if ($echo)
            echo wp_kses_post( $company_name );
        else
            return $company_name;
    }

}

if (!function_exists('sjb_get_the_company_name')) {

    /**
     * sjb_get_the_company_name function.
     *
     * @since   2.1.0
     * 
     * @param   int     $post (default: null)
     * @return  string
     */
    function sjb_get_the_company_name($post = NULL) {

        $post = get_post($post);
        if ($post->post_type !== 'jobpost') {
            return '';
        }

        /**
         * Company Name.
         * 
         * @since   2.1.0
         * 
         * @param   $post->simple_job_board_company_name  Company Name.
         * @param   $post   Post Object
         */
        return apply_filters('sjb_the_company_name', $post->simple_job_board_company_name, $post);
    }

}

if (!function_exists('sjb_the_job_posting_time')) {

    /**
     * Display or retrieve the job posting time.
     *
     * @since  2.1.0
     * 
     * @param  mixed $id (default: null)
     * @return void
     */
    function sjb_the_job_posting_time($post = NULL) {

        $job_posting_time = sjb_get_the_job_posting_time($post);

        if (strlen($job_posting_time) == 0)
            return;

        echo esc_attr(strip_tags($job_posting_time));
    }

}

if (!function_exists('sjb_get_the_job_posting_time')) {

    /**
     * sjb_get_the_job_posting_time function.
     *
     * @since   2.1.0
     * 
     * @param   int     $post (default: null)
     * @return  string
     */
    function sjb_get_the_job_posting_time($post = NULL) {

        $post = get_post($post);
        if ($post->post_type !== 'jobpost') {
            return '';
        }

        /**
         * Job Posted Date.
         * 
         * @since   2.1.0
         * 
         * @param   human_time_diff(get_post_time('U'), current_time('timestamp'))  Job Posted Date.
         * @param   $post   Post Object
         */
        return apply_filters('sjb_the_job_posting_time', human_time_diff(get_post_time('U'), current_time('timestamp')), $post);
    }

}

if (!function_exists('sjb_get_the_company_website')) {

    /**
     * sjb_get_the_company_website function.
     *
     * @since   2.1.0
     * 
     * @param   int     $post (default: null)
     * @return  void
     */
    function sjb_get_the_company_website($post = NULL) {

        $post = get_post($post);

        if ($post->post_type !== 'jobpost') {
            return;
        }
        $website = $post->simple_job_board_company_website;

        if ($website && !strstr($website, 'http:') && !strstr($website, 'https:')) {
            $website = 'http://' . $website;
        }

        /**
         * Company Website.
         * 
         * @since   2.1.0
         * 
         * @param   $website    Company Website
         * @param   $post       Post Object
         */
        return apply_filters('sjb_the_company_website', $website, $post);
    }

}

if (!function_exists('sjb_the_company_tagline')) {

    /**
     * Display or retrieve the current company tagline with optional content.
     *
     * @since   2.1.0
     * @since   2.9.5   Added (string) with sjb_get_the_company_tagline($post).
     * 
     * @param   mixed   $id (default: null)
     * @return  void
     */
    function sjb_the_company_tagline($before = '', $after = '', $echo = TRUE, $post = NULL) {

        $company_tagline = (string) sjb_get_the_company_tagline($post);

        if (strlen($company_tagline) == 0)
            return;

        $company_tagline = esc_attr(strip_tags($company_tagline));
        $company_tagline = $before . $company_tagline . $after;

        if ($echo)
            echo wp_kses_post( $company_tagline );
        else
            return $company_tagline;
    }

}

if (!function_exists('sjb_get_the_company_tagline')) {

    /**
     * sjb_get_the_company_tagline function.
     *
     * @since   2.1.0
     * 
     * @param   int $post (default: 0)
     * @return  void
     */
    function sjb_get_the_company_tagline($post = NULL) {

        $post = get_post($post);

        if ($post->post_type !== 'jobpost')
            return;
        /**
         * Company Tagline
         * 
         * @since   2.1.0
         * 
         * @param   $post->simple_job_board_company_tagline    Company Tagline
         * @param   $post                                      Post Object
         */
        return apply_filters('sjb_the_company_tagline', $post->simple_job_board_company_tagline, $post);
    }

}

if (!function_exists('sjb_the_company_logo')) {

    /**
     * sjb_the_company_logo function.
     *
     * @since   2.1.0
     * 
     * @param   string  $size (default: 'full')
     * @param   array or object array $atts {
     *  associative array with  "id" & "class" indexes
     * 
     *  @type string id => "logo id"
     * 
     *  @type string class => "logo class"
     * }  (default: null) 
     * @param  mixed  $default (default: null)
     * @return void
     */
    function sjb_the_company_logo($size = 'full', $atts = NULL, $default = NULL, $post = NULL) {

        $logo = sjb_get_the_company_logo($post);
        $id = NULL;
        $class = NULL;

        /* Get logo attributes */
        if (!empty($atts)) {
            if (is_array($atts)) {
                $id = isset($atts['id']) ? $atts['id'] : '';
                $class = isset($atts['class']) ? $atts['class'] : '';
            } else {
                $id = isset($atts->id) ? $atts->id : '';
                $class = isset($atts->class) ? $atts->class : '';
            }
        }

        if (!empty($logo) && ( strstr($logo, 'http') || file_exists($logo) )) {
            if ($size !== 'full') {
                $logo = sjb_get_resized_image($logo, $size);
            }
            echo '<img src="' . esc_attr($logo) . '" alt="' . esc_attr(sjb_get_the_company_name($post)) . '" class="sjb-img-responsive ' . esc_attr($class) . '" id="' . esc_attr($id) . '"/>';
        } elseif ($default) {
            echo '<img src="' . esc_attr($default) . '" alt="' . esc_attr(sjb_get_the_company_name($post)) . '" class="sjb-img-responsive  ' . esc_attr($class) . '" id="' . esc_attr($id) . '"/>';
        } else {
            echo '<img src="' . esc_attr(apply_filters('simple_job_board_default_company_logo', plugin_dir_url(dirname(__FILE__)) . 'images/company.png')) . '" alt="' . esc_attr(sjb_get_the_company_name($post)) . '" class="sjb-img-responsive ' . esc_attr($class) . '" id="' . esc_attr($id) . '"/>';
        }
    }

}

if (!function_exists('sjb_get_the_company_logo')) {

    /**
     * sjb_get_the_company_logo function.
     *
     * @since   2.1.0
     * 
     * @param   mixed   $post (default: null)
     * @return  string  $post->simple_job_board_company_logo  Company logo
     */
    function sjb_get_the_company_logo($post = NULL) {

        $post = get_post($post);
        if ($post->post_type !== 'jobpost')
            return;
        /**
         * Company Logo
         * 
         * @since   2.1.0
         * 
         * @param   $post->simple_job_board_company_logo    Company Logo
         * @param   $post                                   Post Id
         */
        return apply_filters('sjb_the_company_logo', $post->simple_job_board_company_logo, $post);
    }

}

if (!function_exists('sjb_get_resized_image')) {

    /**
     * Resize and get url of the image
     * 
     * @since   2.1.0
     * 
     * @param   string  $logo
     * @param   string  $size
     * @return  string  $logo  Company logo
     */
    function sjb_get_resized_image($logo, $size) {

        global $_wp_additional_image_sizes;

        if ($size !== 'full' && strstr($logo, WP_CONTENT_URL) && (isset($_wp_additional_image_sizes[$size]) || in_array($size, array('thumbnail', 'medium', 'large'
                )) )) {

            if (in_array($size, array('thumbnail', 'medium', 'large'))) {
                $img_width = get_option($size . '_size_w');
                $img_height = get_option($size . '_size_h');
                $img_crop = get_option($size . '_size_crop');
            } else {
                $img_width = $_wp_additional_image_sizes[$size]['width'];
                $img_height = $_wp_additional_image_sizes[$size]['height'];
                $img_crop = $_wp_additional_image_sizes[$size]['crop'];
            }

            $upload_dir = wp_upload_dir();
            $logo_path = str_replace(array($upload_dir['baseurl'], $upload_dir['url'], WP_CONTENT_URL), array($upload_dir['basedir'], $upload_dir['path'], WP_CONTENT_DIR), $logo);
            $path_parts = pathinfo($logo_path);
            $resized_logo_path = str_replace('.' . $path_parts['extension'], '-' . $size . '.' . $path_parts['extension'], $logo_path);

            if (strstr($resized_logo_path, 'http:') || strstr($resized_logo_path, 'https:')) {
                return $logo;
            }

            if (!file_exists($resized_logo_path)) {
                ob_start();
                $image = wp_get_image_editor($logo_path);
                if (!is_wp_error($image)) {
                    $resize = $image->resize($img_width, $img_height, $img_crop);
                    if (!is_wp_error($resize)) {
                        $save = $image->save($resized_logo_path);
                        if (!is_wp_error($save)) {
                            $logo = dirname($logo) . '/' . basename($resized_logo_path);
                        }
                    }
                }
                ob_get_clean();
            } else {
                $logo = dirname($logo) . '/' . basename($resized_logo_path);
            }
        }

        return $logo;
    }

}

if (!function_exists('sjb_get_the_excerpt')) {

    /**
     * Custom Excerpt Function.
     *
     * @since   1.0.0 
     * @since   2.9.5   Added filter sjb_get_the_excerpt_len for excerpt length
     * 
     * @param   string  $charlength     Character length.
     * @param   string  $readmore       Read more Enable.
     * @param   string  $readmore_text  Read more Text. 
     * @return  string  $excerpt        Excerpt of Job Description
     */
    function sjb_get_the_excerpt() {

        $excerpt_text = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_excerpt()));
        $excerpt_text = substr($excerpt_text, 0, 200);
        $excerpt_length = strlen($excerpt_text);


        if ($excerpt_length > apply_filters('sjb_get_the_excerpt_len', 199)) {
            $excerpt_text .= '...';
        }

        $more = '<div class="sjb-apply-now-btn"><p>' . sjb_get_the_apply_now_btn() . '</p></div>';

        $excerpt = '<p>' . $excerpt_text . '</p>';

        if (FALSE !== get_option('job_post_layout_settings')) {
            $jobpost_layout_option = get_option('job_post_layout_settings');
            if ('job_post_layout_version_one' === $jobpost_layout_option)
                $job_post_layout_version = 'v1';

            if ('job_post_layout_version_two' === $jobpost_layout_option)
                $job_post_layout_version = 'v2';
        } else {
            $job_post_layout_version = 'v1';
        }

        if ($job_post_layout_version == 'v1') {
            return apply_filters('sjb_get_the_excerpt', $excerpt . $more, $excerpt, $more);
        } else {
            return apply_filters('sjb_get_the_excerpt', $excerpt);
        }
    }

}

if (!function_exists('sjb_get_the_apply_now_btn')) {

    /**
     * Custom Apply Now Button Function.
     *
     * @since   1.0.0 
     * 
     * @return  string  $more           Apply now button
     */
    function   sjb_get_the_apply_now_btn() {
        global $post;
        $job_id = $post->ID;

        // Get Quick Apply Button text
        if (FALSE !== get_option('quick_apply_btn_text')) {
            $qck_btn_text = get_option('quick_apply_btn_text');
             
            if( '' == $qck_btn_text){
                $qck_btn_text = esc_html__('Quick Apply', 'simple-job-board');
            }
            
        } else {
            $qck_btn_text = esc_html__('Quick Apply', 'simple-job-board');
        }

        // Get Read More Button text
        
        $read_more_btn_text = esc_html__('Read More', 'simple-job-board');
        if (FALSE !== get_option('read_more_btn_text')) {
            $read_more_btn_text = get_option('read_more_btn_text');
            
             if( '' == $read_more_btn_text){
                $read_more_btn_text = esc_html__('Read More', 'simple-job-board');
            }
        }

        // Get Apply Now Button text
        $apply_now_text = esc_html__('Apply Now', 'simple-job-board');
        if (FALSE !== get_option('apply_now_btn_text')) {
            $apply_now_text = get_option('apply_now_btn_text');
            
            if( '' == $apply_now_text){
                $apply_now_text = esc_html__('Apply Now', 'simple-job-board');
            }
        }
        
        $quick_apply = '';
        if(get_option('sjb_quick_apply') == 'enable-quick-apply' )
            $quick_apply = '<a href="javascript:void(0)" id="quick-apply-btn" class="btn btn-primary '. apply_filters('sjb-listing-button-class', esc_attr( '' )).'" job_id="'. $job_id .'">' . esc_html__( $qck_btn_text, 'simple-job-board') . ' </a>';
        
        $read_more_btn = $read_more_btn_text != '' ? '<a href="' . get_the_permalink() . '" class="btn btn-primary '. apply_filters('sjb-listing-button-class', esc_attr( '' )).'">' . esc_html__( $read_more_btn_text, 'simple-job-board')  .'</a>': '';
        if (FALSE !== get_option('job_post_layout_settings')) {
            $jobpost_layout_option = get_option('job_post_layout_settings');
            if ('job_post_layout_version_one' === $jobpost_layout_option)
                $read_more_btn = $read_more_btn_text != '' ? '<a href="' . get_the_permalink() . '" class="btn btn-primary '. apply_filters('sjb-listing-button-class', esc_attr( '' )).'">' . esc_html__( $read_more_btn_text, 'simple-job-board')  .'</a>': '';

            if ('job_post_layout_version_two' === $jobpost_layout_option)
                $read_more_btn = $apply_now_text != '' ? '<a href="' . get_the_permalink() . '" class="btn btn-primary '. apply_filters('sjb-listing-button-class', esc_attr( '' )).'">' . esc_html__( $apply_now_text, 'simple-job-board')  .'</a>': '';
        }
        $more = $quick_apply . $read_more_btn ;
        $more = '<p>' . $more . '</p>';
        return apply_filters('sjb_get_the_apply_now_btn', $more);
    }

}

if (!function_exists('sjb_keywords_search_by_title')) {

    /**
     * Search SQL filter for matching against post title only.
     *
     * @since   2.1.4
     * @since   2.10.1  Changed the name of the search results returning variable from $search to $results
     * 
     * @global  Object  WP_Query    $wp_query
     * 
     * @param   string  $search     Searched Keyword
     * @return  string  $results     Search Results from Post Title
     */
    function sjb_keywords_search_by_title($search, $wp_query) {

        global $wpdb;

        $q = $wp_query->query_vars;
        
        if (!empty($search) && !empty($wp_query->query_vars['search_terms']) && isset($wp_query->query['post_type']) && 'jobpost' == $wp_query->query['post_type']) {

            $n = !empty($q['exact']) ? '' : '%';
            $search = array();
            foreach ((array) $q['search_terms'] as $term)
                $search[] = $wpdb->prepare("$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like($term) . $n);

            if (!is_user_logged_in())
                $search[] = "$wpdb->posts.post_password = ''";

            $search = ' AND ' . implode(' AND ', $search);
        }

        return apply_filters( 'sjb_keywords_search_by_title_results', $search, $wp_query );
    }

}

/* Hook -> Keywords Search By Title */
add_filter('posts_search', 'sjb_keywords_search_by_title', 10, 2);

if(!function_exists('sjb_keyword_search_by_meta_value')){
    
    /**
     * Search SQL filter for matching against post meta value.
     *
     * @since   2.9.6
     * 
     * @param   Object  $query     WP_Query
     */
    function sjb_keyword_search_by_meta_value($query) {

        global $pagenow;
        $post_type = 'jobpost_applicants';
        $q_vars = &$query->query_vars;
        if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && (isset($_GET['s']) && !empty($_GET['s']) )) 
        {
            global $wpdb;
            $sanitized_value = '%'.sanitize_text_field( $_GET['s'] ).'%';
            $prepare_guery = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta where meta_value like %s", $sanitized_value );
            $get_values = $wpdb->get_col( $prepare_guery );
            $q_vars['post__in'] = $get_values;
            unset($q_vars['s']);
        }
    }
}

/* Hook -> Keywords Search By Meta Value */
add_filter('parse_query', 'sjb_keyword_search_by_meta_value', 10, 1);

if (!function_exists('sjb_is_checked')) {

    /**
     * Assign Default Radio button Check
     * 
     * @since   2.1.0
     */
    function sjb_is_checked($i) {
        $checked = ( $i == 0 ) ? "checked" : NULL;
        return $checked;
    }

}

if (!function_exists('sjb_job_listing_meta_display')) {

    /**
     * Displays job meta data on the single job page.
     * 
     * @since   2.1.0
     */
    function sjb_job_listing_meta_display() {

        get_simple_job_board_template('single-jobpost/content-single-job-listing-meta.php', array());
    }

}

add_action('sjb_single_job_listing_start', 'sjb_job_listing_meta_display', 20);

if (!function_exists('sjb_job_listing_features')) {

    /**
     * Displays job features data on the single job page.
     * 
     * @since   2.1.0
     */
    function sjb_job_listing_features() {

        get_simple_job_board_template('single-jobpost/job-features.php', array());
    }

}

add_action('sjb_single_job_listing_end', 'sjb_job_listing_features', 20);

if (!function_exists('sjb_job_listing_application_form')) {

    /**
     * Displays job application form on the single job page
     * 
     * @since   2.1.0
     */
    function sjb_job_listing_application_form() {

        get_simple_job_board_template('single-jobpost/job-application.php', array());
    }

}

add_action('sjb_single_job_listing_end', 'sjb_job_listing_application_form', 30);

if (!function_exists('sjb_job_listing_wrapper_start')) {

    /**
     * Output Content Wrapper start div's
     * 
     * @since   2.2.0
     */
    function sjb_job_listing_wrapper_start() {

        get_simple_job_board_template('global/content-wrapper-start.php');
    }

}

add_action('sjb_before_main_content', 'sjb_job_listing_wrapper_start', 10);

if (!function_exists('sjb_job_listing_wrapper_end')) {

    /**
     * Output Content Wrapper end div's
     * 
     * @since   2.2.0 
     */
    function sjb_job_listing_wrapper_end() {

        get_simple_job_board_template('global/content-wrapper-end.php');
    }

}

add_action('sjb_after_main_content', 'sjb_job_listing_wrapper_end', 10);

if (!function_exists('sjb_job_listing_view')) {

    /**
     * Job Listing View 
     * 
     * This function displays the user defined job listing view.  
     * 
     * @since   2.2.3 
     */
    function sjb_job_listing_view() {

        // Display the user defined job listing view
        if ('grid-view' === get_option('job_board_listing_view')) {
            get_simple_job_board_template('content-job-listing-grid-view.php');
        } else {
            get_simple_job_board_template('content-job-listing-list-view.php');
        }
    }

}

// Hook -> Job Listing View
add_action('sjb_job_listing_view', 'sjb_job_listing_view', 10);

if (!function_exists('sjb_job_features_count')) {

    /**
     * Return Count of Job Features. 
     * 
     * @since   2.2.0
     */
    function sjb_job_features_count() {

        global $post;
        $keys = get_post_custom_keys(get_the_ID());
        $count = 0;

        if ($keys != NULL):
            foreach ($keys as $key):
                if (substr($key, 0, 11) == 'jobfeature_') {
                    $val = get_post_meta($post->ID, $key, TRUE);
                    $val = maybe_unserialize($val);
                    if (!empty($val['value'])) {
                        $count++;
                    }
                }
            endforeach;
        endif;
        return $count;
    }

}

if (!function_exists('sjb_front_end_scripts')) {

    /**
     * Enqueue Frontend Styles & Scripts. 
     * 
     * @since   2.2.3
     */
    function sjb_front_end_scripts() {

        // Enqueue Scripts
        wp_enqueue_script('simple-job-board-validate-telephone-input');
        wp_enqueue_script('simple-job-board-validate-telephone-input-utiliy');
        wp_enqueue_script('simple-job-board-front-end');
    }

}

// Action -> Enqueue Frontend Styles & Scripts.
add_action('sjb_enqueue_scripts', 'sjb_front_end_scripts');

if (!function_exists('sjb_get_slugs')) {

    /**
     * Get Current Page Slug. 
     * 
     * @since   2.2.4
     */
    function sjb_get_slugs() {
        global $post;

        if (is_archive()) {
            $link = get_post_type_archive_link('jobpost');
        } else {
            $link = get_permalink($post->ID);
        }

        if (empty($link)) {
            return FALSE;
        } else {
            $link = str_replace(home_url('/'), '', $link);
            return $link;
        }
    }

}

if (!function_exists('sjb_is_keyword_search')) {

    /**
     * Is Category Filter
     * 
     * @since   2.4.0
     */
    function sjb_is_keyword_search() {
        $is_search = ('yes' === get_option('job_board_search_bar') ) ? TRUE : FALSE;
        return $is_search;
    }

}

if (!function_exists('sjb_is_category_filter')) {

    /**
     * Is Category Filter
     * 
     * @since   2.4.0
     */
    function sjb_is_category_filter() {
        $is_cat = ( NULL != get_terms('jobpost_category') && ( 'yes' === get_option('job_board_category_filter') ) ) ? TRUE : FALSE;
        return $is_cat;
    }

}

if (!function_exists('sjb_is_type_filter')) {

    /**
     * Is Job Type Filter
     * 
     * @since   2.4.0
     * 
     */
    function sjb_is_type_filter() {
        $is_type = ( NULL != get_terms('jobpost_job_type') && 'yes' === get_option('job_board_jobtype_filter') ) ? TRUE : FALSE;
        return $is_type;
    }

}

if (!function_exists('sjb_is_location_filter')) {

    /**
     * Is Job Location Filter
     * 
     * @since   2.4.0
     */
    function sjb_is_location_filter() {
        $is_loc = ( NULL != get_terms('jobpost_location') && 'yes' === get_option('job_board_location_filter') ) ? TRUE : FALSE;
        return $is_loc;
    }

}

if (!function_exists('sjb_is_filter_dropdowns')) {

    /**
     * Is Job Filters
     * 
     * @since   2.4.0
     */
    function sjb_is_filter_dropdowns() {
        $filters_dropdowns = ( sjb_is_category_filter() || sjb_is_type_filter() || sjb_is_location_filter() ) ? TRUE : FALSE;
        return apply_filters('sjb_is_filter_dropdowns', $filters_dropdowns);
    }

}

if (!function_exists('is_sjb')) {

    /**
     * is_sjb - Returns TRUE when Viewing the Jobpost Pages.
     * 
     * @since   2.4.0
     * 
     * @return bool
     */
    function is_sjb() {
        return apply_filters('is_sjb', ( is_jobpost() || is_jobpost_archive() || is_jobpost_taxonomy() || is_jobpost_shortcode() ) ? TRUE : FALSE);
    }

}

if (!function_exists('is_jobpost')) {

    /**
     * is_jobpost - Returns TRUE when Viewing the Jobpost Single Page.
     * 
     * @since   2.4.0
     * 
     * @return bool
     */
    function is_jobpost() {
        return is_singular(array('jobpost'));
    }

}

if (!function_exists('is_jobpost_archive')) {

    /**
     * is_jobpost_archive - Returns TRUE when Viewing the Job Archive Page.
     * 
     * @since   2.4.0
     * 
     * @return bool
     */
    function is_jobpost_archive() {
        return ( is_post_type_archive('jobpost') );
    }

}

if (!function_exists('is_jobpost_taxonomy')) {

    /**
     * is_jobpost_taxonomy - Returns TRUE when Viewing the Job Taxonomies.
     * 
     * @since   2.4.0
     * 
     * @return bool
     */
    function is_jobpost_taxonomy() {
        return is_tax(get_object_taxonomies('jobpost'));
    }

}

if (!function_exists('is_jobpost_shortcode')) {

    /**
     * is_jobpost_shortcode - Returns TRUE when Viewing the Job Listing.
     * 
     * @since   2.4.0
     * 
     * @return bool
     */
    function is_jobpost_shortcode() {
        global $post;

        return is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'jobpost');
    }

}

if (!function_exists('sjb_the_title')) {

    /**
     * Display Job Title
     * 
     * @since   2.4.4
     * @since   2.9.5   Added (string) with sjb_get_the_title($post).
     * 
     * @param   string      $before    Add string befor title.
     * @param   string      $after     Add string after title.
     * @param   bool        $echo      TRUE/FALSE
     * @param   object      $post      Post Object
     * @return  $job_title  Job Title
     */
    function sjb_the_title($before = '', $after = '', $echo = TRUE, $post = NULL) {


        $job_title = (string) sjb_get_the_title($post);

        if (strlen($job_title) == 0)
            return;

        $job_title = esc_attr(strip_tags($job_title));
        $job_title = $before . $job_title . $after;

        if ($echo)
            echo wp_kses_post( $job_title );
        else
            return $job_title;
    }

}

if (!function_exists('sjb_get_the_title')) {

    /**
     * Return Job Title
     * 
     * @since   2.4.4
     * 
     * @param   object      $post      Post Object
     * @return  $job_title  Job Title
     */
    function sjb_get_the_title($post = NULL) {

        global $post;
        $title = get_the_title();

        /**
         * Job Title
         * 
         * @since   2.4.4
         * 
         * @param   $title  Job Title
         * @param   $post   Post Id
         */
        return apply_filters('sjb_the_title', $title, $post);
    }

}

/**
 * Single Job Content Start
 * 
 * @since   2.5.0
 */
if (!function_exists('sjb_single_job_content_start')) {

    function sjb_single_job_content_start() {
        get_simple_job_board_template('single-jobpost/single-job-wrapper-start.php');
    }

}

add_action('sjb_single_job_content_start', 'sjb_single_job_content_start');

/**
 * Single Job Content End
 * 
 * @since   2.5.0
 */
if (!function_exists('sjb_single_job_content_end')) {

    function sjb_single_job_content_end() {
        get_simple_job_board_template('single-jobpost/single-job-wrapper-end.php');
    }

}

/**
 * Get allowed HTML tags function
 * 
 * @since   2.8.2
 * @since   2.9.5   Added support for <br> tag.
 * @since   2.9.6   Added support for attribute 'class' for <option> tag.
 */
if (!function_exists('sjb_get_allowed_html_tags')) {

    function sjb_get_allowed_html_tags() {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
                'target' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'br' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'role' => array(),
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
            'option' => array(
                'class' => array(),
                'value' => array(),
                'selected' => array(),
            ),
            'input' => array(
                'type' => array(),
                'value' => array(),
                'class' => array(),
                'id' => array(),
                'name' => array(),
                'placeholder' => array(),
            ),
            'select' => array(                
                'value' => array(),
                'class' => array(),
                'id' => array(),
                'name' => array(),                
            ),
            'small' => array(),
            'i' => array(
                'class' => array(),
            ),
        );

        return apply_filters('sjb_allowed_html_tags', $allowed_tags);
    }
}

add_action('sjb_single_job_content_end', 'sjb_single_job_content_end');

if (!function_exists('sjb_string_to_bool')) {

    /**
     * Converts a string (e.g. 'yes' or 'no') to a bool.
     *
     * @since   2.6.0
     * 
     * @param   string $string String to convert.
     * @return  bool
     */
    function sjb_string_to_bool($string) {
        return is_bool($string) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
    }

}

if (!function_exists('sjb_http_responses_code')) {

    /**
     * Return message against HTTP status code.
     *
     * @since   2.9.6
     * 
     * @param   int  $code Status code.
     * @return  bool
     */
    function sjb_http_responses_code($code = NULL) {

        $text = '';
        switch ($code) {
            case 100: $text = __('Continue.', 'simple-job-board'); break;
            case 101: $text = __('Switching Protocols.', 'simple-job-board'); break;
            case 203: $text = __('Non-Authoritative Information', 'simple-job-board'); break;
            case 204: $text = __('No Content', 'simple-job-board'); break;
            case 205: $text = __('Reset Content', 'simple-job-board'); break;
            case 206: $text = __('Partial Content', 'simple-job-board'); break;
            case 300: $text = __('Multiple Choices', 'simple-job-board'); break;
            case 301: $text = __('Moved Permanently', 'simple-job-board'); break;
            case 302: $text = __('Moved Temporarily', 'simple-job-board'); break;
            case 303: $text = __('See Other', 'simple-job-board'); break;
            case 304: $text = __('Not Modified', 'simple-job-board'); break;
            case 305: $text = __('Use Proxy', 'simple-job-board'); break;
            case 400: $text = __('Bad Request. Please try again.', 'simple-job-board'); break;
            case 401: $text = __('Unauthorized Access. Please try again.', 'simple-job-board'); break;
            case 402: $text = __('Payment Required', 'simple-job-board'); break;
            case 403: $text = __('Access Forbidden. Please try again.', 'simple-job-board'); break;
            case 404: $text = __('Not Found. Please try again.', 'simple-job-board'); break;
            case 405: $text = __('Method Not Allowed. Please try again.', 'simple-job-board'); break;
            case 406: $text = __('Not Acceptable. Please try again.', 'simple-job-board'); break;
            case 407: $text = __('Proxy Authentication Required. Please try again.', 'simple-job-board'); break;
            case 408: $text = __('Request Time-out. Please try again.', 'simple-job-board'); break;
            case 409: $text = __('Conflict. Please try again.', 'simple-job-board'); break;
            case 410: $text = __('Gone', 'simple-job-board'); break;
            case 411: $text = __('Length Required. Please try again.', 'simple-job-board'); break;
            case 412: $text = __('Precondition Failed. Please try again.', 'simple-job-board'); break;
            case 413: $text = __('Request Entity Too Large. Please try again.', 'simple-job-board'); break;
            case 414: $text = __('Request-URI Too Large. Please try again.', 'simple-job-board'); break;
            case 415: $text = __('Unsupported Media Type. Please try again.', 'simple-job-board'); break;
            case 500: $text = __('Internal Server Error. Please try again.', 'simple-job-board'); break;
            case 501: $text = __('Not Implemented. Please try again.', 'simple-job-board'); break;
            case 502: $text = __('Bad Gateway. Please try again.', 'simple-job-board'); break;
            case 503: $text = __('Service Unavailable. Please try again.', 'simple-job-board'); break;
            case 504: $text = __('Gateway Time-out. Please try again.', 'simple-job-board'); break;
            case 505: $text = __('HTTP Version not supported. Please try again.', 'simple-job-board'); break;
            default:
                $text = 'Unknown http status code "' . htmlentities($code) . '"';
            break;
        }

        return $text;

    }
}

if (!function_exists('sjb_job_archives_title')) {

    /**
     * Update Job Archive page title.
     *
     * @since   2.10.0
     * 
     * @param   string  $title                  Job Archive page title.
     * @return  string  $job_archives_name      Updated Job Archive page title.
     */
    function sjb_job_archives_title($title) {

        $job_archives_name = get_option('job_archives_name') !== false ? get_option('job_archives_name') : $title;

        return $job_archives_name;
    }

}

/* Hook -> Job Archive page title */
add_filter('sjb_jobs_archive_title', 'sjb_job_archives_title', 10, 2);
