<?php
/**
 * Template Name: Events Page
 * The template for displaying doctor list with review link
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package WordPress
 * @subpackage wp
 * @since 1.0
 * @version 1.0
 */

get_header();

?>
<?php

$posts_per_page = 5;
global $paged;
//define events object
$events_obj = '';
if (isset($_POST['search_events'])) {
    //get events - using tribe_get_events() method defined in the events calendar plugin
    $search_query = array();
    if ($_POST['start_date'] && $_POST['start_date'] !== ' ') {
        $search_query['start_date'] = $_POST['start_date'];
    }
    if ($_POST['end_date'] && $_POST['end_date'] !== ' ') {
        $search_query['end_date'] = $_POST['end_date'];
    }
    if ($_POST['category'] && $_POST['category'] !== ' ') {
        $search_query['tribe_events_cat'] = $_POST['category'];
    }
    if ($_POST['search_keyword'] && $_POST['search_keyword'] !== ' ') {
        $search_query['s'] = $_POST['search_keyword'];
    }
    $events_obj = tribe_get_events($search_query, false);
}else {
  $paged = get_query_var('paged')?get_query_var('paged'):1;
  
  $events_obj = tribe_get_events(array('posts_per_page'=>$posts_per_page, 'paged'=>$paged), false);
}
//get event categories
$event_categories = get_terms(array('taxonomy'=>'tribe_events_cat'));

?>
<div class="events-page container">
  <div class="event-filter">
    <form method="POST"  class="horizontal-form events-page" action="<?php echo $_SERVER['REQUEST_URI']; ?>" >
   				<div class="input-group field-1 large" style="width:25%;">
      <input class="form-control" type="text" name="search_keyword" placeholder="keyword">
      </div>
				<div class="input-group field-2 select-box large" style="width:25%;">
          <i class="fa fa-angle-down"></i>
      <select  class="form-control" name="category" >
        <option value>Choose Category</option>
        <?php
        foreach($event_categories as $category_event){
          echo '<option value="'.$category_event->name.'">'.$category_event->name.'</option>';
        }
        ?>
          </select>
          </div>
				<div class="input-group field-3 small" style="width:16.33%;">
   <input type="text" name="start_date" class="form-control datepicker" placeholder="start date">
          </div>
				<div class="input-group field-4 small" style="width:16.33%">
      <input type="text" name="end_date" class="form-control datepicker" placeholder="end date">
          </div>
				<div class="input-group submit">
          <i class="fa fa-search"></i>
      <input type="submit" class="submit-btn" name="search_events" value="GO">
          </div>
    </form>
    <?php
    //create a array with all euqla start dates events in one array.
    $_array_date_group_events = array(); 
foreach ($events_obj as $event_info) {
  $event_month = explode(" ", $event_info->EventStartDate);
  $event_month = explode("-", $event_info->EventStartDate);
  $event_month = $event_month[0]."-".$event_month[1];
  if(isset($_array_date_group_events[$event_month])){
   $_array_date_group_events[$event_month][] = $event_info;
  }else {
   $_array_date_group_events[$event_month] = array( $event_info );
    
  }
}
    foreach($_array_date_group_events as $key=>$value){
      ?>
  <div class="event col-md-12" style="padding:10px; border-bottom:1px solid #EEE; min-width:100%; display:inline-block; ">   
    <div class="col-md-3"><?php 
      //create a month and year
     // echo $key;
      $_month = explode("-", $key);
     
      $create_month_year_view = DateTime::createFromFormat('!m', $_month[1]);
      printf(__('<h3>%s</h3>'), $create_month_year_view->format('M')); 
      echo '<hr>';
      printf(__('<h3>%s</h3>'), $_month[0]);
      ?></div>
        <div class="col-md-9">
          <?php
      foreach($value as $event_single){
  // print_r (get_post_meta($event_info->ID));
 //get location
  $_location = get_post_meta($event_single->ID, "_EventVenueID", true);
  $location_info = get_post($_location);
  
  ?>
    
 
    <div class="col-md-7">
    
  <?php
     $date_time = $event_single->EventStartDate;
        $date_time = explode(" ",$date_time);
        $month = explode("-", $date_time[0]);
        $time = date('g:i:a', strtotime($date_time[1]));
        $formatted_time = explode(":",$time)[2] == "pm" ? str_replace(":pm","PM", date('g:i:a', strtotime($date_time[1]))) : str_replace(":am","AM", date('g:i:a', strtotime($date_time[1])));
		printf(__('<p>%s</p>'),DateTime::createFromFormat('!m', $month[1])->format('F') . ' ' . $month[2] . ($month[2] <= 3 ? 'rd' : 'th'));
    printf(__('<h3>%s</h3>'), $event_single->post_title);
    printf(__('<p>%s</p>'), $formatted_time. ' ' . date('l', strtotime($date_time[0])));

?>
    </div>
 <?php
  printf(__('<div class="col-md-3">%s <br/><a href="https://www.google.com/maps?q=%s" target="_blank"><i class="fa fa-map-marker"></i></a></div>'), $location_info->post_title, $location_info->post_title);
  ?>
      
    <div class="col-md-2">
      <?php
      printf(__('<a href="%s" class="btn btn-info">Register</a>'), get_the_permalink($event_single->ID));
      ?>
    </div>
  
<?php
}
      ?>
      </div>
    </div>
    <?php
    }
//    print_r($_array_date_group_events);
$max_num_pages = ceil( wp_count_posts('tribe_events')->publish / $posts_per_page );
    
//custom_pagination($max_num_pages, '', $paged);
    echo '<div class="text-center" style="margin-bottom:20px;">';
  previous_posts_link('<button type="button" class="btn btn-info" style="background-color:transparent; color: #35aee0; border-radius:0;">Previous <i class="fa fa-arrow-left"></i></button>');
  next_posts_link('&nbsp;<button type="button" class="btn btn-info"  style="background-color:transparent; color: #35aee0; border-radius:0;">Next <i class="fa fa-arrow-right"></i></button>',$max_num_pages );
wp_reset_postdata();
?>
  </div>
<?php
/*print_r(tribe_get_events(array(
'start_date' => '2017-11-01',
'end_date' => '2017-11-30',
//'s' => 'check'
//'tribe_events_cat'=>'category'
), false));*/

?>
  </div><!-- #primary -->
  <?php
//get_sidebar();
?>
</div><!-- .wrap -->

<script type="text/javascript">
  jQuery(document).ready(function(){
  	jQuery(".datepicker").datepicker();
  });
</script>
<?php
get_footer();
