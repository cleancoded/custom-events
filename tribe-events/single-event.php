<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.3
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = get_the_ID();

?>


<div id="tribe-events-content" class="tribe-events-single container" >
	<!-- Notices -->
	<?php tribe_the_notices() ?>
<!-- Navigation -->	
<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
	
	<h3 class="tribe-events-visuallyhidden">
		<?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?>
	</h3>
	
	<ul class="tribe-events-sub-nav" style="margin-bottom:20px; padding-left:30px; padding-right:15px;">
		<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<i class="fa fa-angle-left"></i> %title%' ) ?></li>
		<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <i class="fa fa-angle-right"></i>' ) ?></li>
	</ul>
  
  </div>
		<!-- .tribe-events-sub-nav -->
  <div class="col-md-8">
  	<div class="col-md-4">
    	<div style="padding: 50px 20px; text-align: center;  background: #42c2e9;">
      		<?php
  				$_e_date = get_post_meta($event_id, "_EventStartDate", true);
      			$date_time = explode(" ",$_e_date);
      			$month = explode("-", $date_time[0]); 
			    $create_month_year_view = DateTime::createFromFormat('!m', $month[1]);
      			printf(__('<h3 style="margin:0; line-height:1;color:#FFF; ">%s</h3>'), $create_month_year_view->format('F') . ' ' . $month[2]); 
      			echo '<hr>';
      			printf(__('<h3 style="margin:0; line-height:1; color:#FFF;">%s</h3>'), $month[0]);
    		?>
    	</div>
  </div>
  <div class="col-md-8">
  	<?php 
	  	the_title( '<h1 style="margin-bottom:10px;" class="tribe-events-single-event-title">', '</h1>' ); 
    	//get location
  		$_location = get_post_meta($event_id, "_EventVenueID", true);
  		$location_info = get_post($_location);
	?>
    <p style="margin:0; font-size:12px; line-height:2"><?php echo $location_info->post_title; ?></p>
	<?php      
    	$_event_date_time = explode("@",tribe_events_event_schedule_details($event_id));
    	printf(__('<p style="margin:0; line-height:2; margin-bottom:10px;font-size:12px;">%s, %s, %s | %s</p>'), date('l',strtotime($date_time[0])), $create_month_year_view->format('F') . ' ' . $month[2].''.($month[2]<=3?"rd":"th"), $month[0], $_event_date_time[1]); ?> 
        <a href="#" class="btn-filled" style="padding:15px 20px; width:auto; display:inline-block; min-height:40px;">Register for Event <i class="fa fa-angle-right"></i></a>
        <a href="#" style="padding:15px 20px; text-decoration:underline; width:auto; display:inline-block; min-height:40px;"><i class="fa fa-calendar"></i> Add Event to calendar </a>
    </div>
    <div class="col-md-12">
	    <hr style="border-color:#42c2e9;">
		<div class="tribe-events-schedule tribe-clearfix">
		<?php if ( tribe_get_cost() ) : ?>
			<span class="tribe-events-cost"><?php //echo tribe_get_cost( null, true ) ?></span>
		<?php endif; ?>
	</div>

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Event featured image, but exclude link -->
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>

			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content">
				<?php the_content(); ?>
			</div>
			<!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>
    </div>
  </div>
  </div>
  <div class="col-md-4">	<!-- Event meta -->
	<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
    
    <?php echo '<div class="tribe-events-meta-group tribe-events-meta-group-gmap">';
		tribe_get_template_part( 'modules/meta/map' );
		echo '</div>'; ?>
    
    <?php
    	// Include organizer meta if appropriate
    	echo '<div class="col-md-7">';
		tribe_get_template_part( 'modules/meta/venue' );
      	echo '</div><div class="col-md-5" style="border-left:1px solid #777;">';
    	if ( tribe_has_organizer() ) {
			tribe_get_template_part( 'modules/meta/organizer' );
		} 
		echo '</div>';
    ?>
      
	<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

	<!-- Event footer -->
  </div>
<div class="col-md-12">  <div id="tribe-events-footer">
		<!-- Navigation -->
		<h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?></h3>
		<div class="text-center">
			<?php tribe_the_prev_event_link( '<button style="padding:10px; border-radius:0; background-color:transparent; color:#42c2e9;" class="btn btn-info"><i class="fa fa-angle-left"></i> Previous Event</button>' ) ?>
			<?php tribe_the_next_event_link( '<button style="padding:10px; border-radius:0; background-color:transparent; color:#42c2e9;" class="btn btn-info">Next Event <i class="fa fa-angle-right"></i></button>' ) ?>
		</div>
		<!-- .tribe-events-sub-nav -->
	</div>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
