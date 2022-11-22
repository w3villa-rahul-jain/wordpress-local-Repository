<?php
/**
 * The template for displaying job content in list view within loops.
 *
 * Override this template by copying it to yourtheme/simple_job_board/v1/content-job-listing-list-view.php
 *
 * @author      PressTigers
 * @package     Simple_Job_Board
 * @subpackage  Simple_Job_Board/Templates
 * @version     2.0.0
 * @since       2.2.0
 * @since       2.2.3   Added @hook sjb_job_listing_heading_after.
 * @since       2.3.0   Added "sjb_list_view_template" filter.
 * @since       2.4.0   Revised whole HTML template
 */
ob_start();
global $post;

/**
 * Fires before job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_list_view_before');
?>

<!-- Start Jobs List View 
================================================== -->
<div class="list-data">
    <div class="v1 <?php echo apply_filters('sjb-listing-classes', esc_attr( '' )); ?>">   
        
        <!-- Jobs List view header -->
        <header>
            <div class="row">
                <?php
                /**
                 * Template -> Logo:
                 * 
                 * - Company Logo
                 */
                get_simple_job_board_template('listing/list-view/logo.php');
                
                $class = ( 'logo-detail' === get_option( 'job_board_listing' ) || 'without-detail' === get_option( 'job_board_listing' ) )?
                        'col-md-11 col-sm-10':'col-md-12 col-sm-10';

                ?>    
                <div class="<?php echo esc_attr( $class ); ?>">
                    <div class="row">

                    <?php 

                        /**
                         * Fires before type, location, date displayed
                         * 
                         * @since 2.10.1
                        */
                        do_action('sjb_job_type_location_date_before') ;
                                         
                        /**
                         * Template -> Job Title & Company Name:
                         * 
                         * - Job Title
                         * - Company Name
                         */
                        get_simple_job_board_template('listing/list-view/job-title-company.php');
                        
                        /**
                         * Template -> Type:
                         * 
                         * - Job Type
                         */
                        get_simple_job_board_template('listing/list-view/type.php');

                        /**
                         * Template -> Location:
                         * 
                         * - Job Location
                         */
                        get_simple_job_board_template('listing/list-view/location.php');

                        /**
                         * Template -> Posted Date:
                         * 
                         * - Job Posted Date
                         */
                        get_simple_job_board_template('listing/list-view/posted-date.php');

                        /**
                         * Fires after type, location, date displayed
                         * 
                         * @since 2.10.1
                        */
                        do_action('sjb_job_type_location_date_after'); ?>
                        
                    </div> 
                </div>
            </div>
        </header>

        <?php
        /**
         * Template -> Short Description:
         * 
         * - Job Description
         */
        get_simple_job_board_template('listing/list-view/short-description.php');
        ?>
    </div>
</div>
<!-- ==================================================
End Jobs List View -->

<div class="clearfix"></div>

<?php
/**
 * Fires after job listing on job listing page.
 * 
 * @since   2.2.0
 */
do_action('sjb_job_listing_list_view_after');

$html_list_view = ob_get_clean();

/**
 * Modify the Job Listing List View Template. 
 *                                       
 * @since   2.3.0
 * 
 * @param   html    $html_list_view   Job Listing List View HTML.                   
 */
echo apply_filters('sjb_list_view_template', $html_list_view);