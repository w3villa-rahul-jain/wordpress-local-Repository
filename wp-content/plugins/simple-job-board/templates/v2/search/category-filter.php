<?php
/**
 * Template for displaying category filter dropdown
 *
 * Override this template by copying it to yourtheme/simple_job_board/v2/search/category-filter.php
 *
 * @author 	PressTigers
 * @package     Simple_Job_Board
 * @subpackage  Simple_Job_Board/templates/search
 * @version     1.0.0
 * @since       2.3.0   Added "sjb_category_filter_template" filter.
 * @since       2.4.0   Revised the whole HTML template
 */

ob_start();

// Check for setting page option and the term existance
if (sjb_is_category_filter()) {
    $allowed_tags = sjb_get_allowed_html_tags();
    $selected_category = ( NULL != filter_input(INPUT_GET, 'selected_category') ) ? sanitize_text_field( filter_input( INPUT_GET, 'selected_category' ) ) : FALSE;

    /**
     * Creating list on non-empty job category
     * 
     * Job Category Selectbox
     */
    
    // Job Category Arguments
    $category_args = array(
        'show_option_none' => apply_filters( 'sjb_category_filter_title', esc_html__('Category', 'simple-job-board') ),
        'orderby'          => 'NAME',
        'order'            => 'ASC',
        'hide_empty'       => 0,
        'echo'             => FALSE,
        'hierarchical'     => TRUE,
        'name'             => 'selected_category',
        'id'               => 'category',
        'class'            => 'form-control',
        'selected'         => $selected_category,
        'taxonomy'         => 'jobpost_category',
        'value_field'      => 'slug',
    );

    // Display or retrieve the HTML dropdown list of job category
    $category_select = wp_dropdown_categories( apply_filters( 'sjb_category_filter_args', $category_args, $atts ) );
    ?>

    <!-- Category Filter-->        
    <div class="sjb-search-categories <?php echo apply_filters( 'sjb_category_filter_class', 'col-md-3 col-xs-12' ); ?>">
        <div class="form-group">
            <?php
            if (isset($category_select) && (NULL != $category_select )) {
                echo wp_kses( $category_select, $allowed_tags );
            }
            ?>
        </div>
    </div>
    <?php
}

$html_category_filter = ob_get_clean();

/**
 * Modify the Category Filter Template. 
 *                                       
 * @since   2.3.0
 * 
 * @param   html    $html_category_filter   Category Filter HTML.                   
 */
echo apply_filters( 'sjb_category_filter_template', $html_category_filter );