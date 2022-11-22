<?php

/**
 * The template for displaying job content in grid view within loops.
 *
 * Override this template by copying it to yourtheme/simple_job_board/v2/content-job-listing-grid-view.php
 *
 * @author      PressTigers
 * @package     Simple_Job_Board
 * @subpackage  Simple_Job_Board/Templates
 * @version     2.0.0
 * @since       2.2.0
 * @since       2.2.3   Added @hook sjb_job_listing_heading_after.
 * @since       2.3.0   Added "sjb_grid_view_template" filter.
 * @since       2.4.0   Revised whole HTML template
 */
ob_start();
global $counter, $post_count;

if (1 === $counter) {
    echo '<div class="row">';
}

/**
 * Fires at start of a job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_grid_view_start');
?>

<!-- Start Jobs Grid View 
================================================== -->
<div class="col-md-4 col-sm-6 grid-item">
    <div class="list-data">
        <div class="v2 <?php echo apply_filters('sjb-listing-classes', esc_attr( '' )); ?>">
            <!-- Grid view header -->
            <header>
                <div class="row">
                    <?php

                    /**
                     * Fires before type, location, date displayed
                     * 
                     * @since 2.10.1
                    */        

                    do_action('sjb_grid_view_type_location_posted_date_before');

                    /**
                     * Template -> Logo:
                     * 
                     * - Company Logo
                     */
                    get_simple_job_board_template('listing/grid-view/logo.php');

                    /**
                     * Template -> Title:
                     * 
                     * - Job Title
                     */
                    get_simple_job_board_template('listing/grid-view/job-title-company.php');

                    /**
                     * Template -> Type:
                     * 
                     * - Job Type
                     */
                    get_simple_job_board_template('listing/grid-view/type.php');

                    /**
                     * Template -> Location:
                     * 
                     * - Job Location
                     */
                    get_simple_job_board_template('listing/grid-view/location.php');

                    /**
                     * Template -> Post Date:
                     * 
                     * - Job Post Date
                     */
                    get_simple_job_board_template('listing/grid-view/posted-date.php');

                    /**
                     * Fires after type, location, date displayed
                     * 
                     * @since 2.10.1
                    */        
                    do_action('sjb_grid_view_type_location_posted_date_after');
                    
                    ?>
                </div>
            </header>

            <?php
            /**
             * Template -> Short Description:
             * 
             * - Job Description
             */
            get_simple_job_board_template('listing/grid-view/short-description.php');

            /**
             * Template -> Short Description:
             * 
             * - Job Description
             */
            get_simple_job_board_template('listing/grid-view/apply.php');
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!-- ==================================================
End Jobs Grid View -->

<?php
/**
 * Fires at the end of a job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_grid_view_end');

if ($post_count === $counter) {
    echo '</div>';
}
$counter++;

$html_grid_view = ob_get_clean();

/**
 * Modify the Job Listing Grid View Template. 
 *                                       
 * @since   2.3.0
 * 
 * @param   html    $html_grid_view   Job Listing Grid View HTML.                   
 */
echo apply_filters('sjb_grid_view_template', $html_grid_view);
