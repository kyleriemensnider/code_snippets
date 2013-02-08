// pull gravity form /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

<?php echo do_shortcode('[gravityform id="1" name="Contact" title="false" description="false" ajax="true"]'); ?>


// create nav menu of child pages /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
<?php if(!Is_page('Contact')){
		
			echo '<div id="secondary_nav">';
		
			//get top level parent
			if ($post->post_parent) {
				$ancestor = get_post_ancestors($post->ID);
				$root = count($ancestor)-1;
				$parent= $ancestor[$root];
			} else {
				$parent = $post->ID;
			};
			
			//title of top level parent
			echo '<h4><a href="'.get_permalink($parent).'">'.get_the_title($parent).'</a></h4>';
			
			//cycle through children and echo secondary nav
			$child_pages = wp_list_pages(array('child_of' => $parent, 'title_li' => ''));
			if($child_pages){
				echo '<ul>';
				foreach($child_pages as $child){
					echo '<li>'.$child.'</li>';
				};
				echo '</ul>';
			};
			echo '</div>';
	} ?>
	
	
// pull complete title information /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

<?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :: '; } ?><?php bloginfo('name'); if(is_home()) { echo ' :: '; bloginfo('description'); } ?>

// query by meta and acf ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

<?php	$study = array('grow', 'relocate', 'innovate', 'start_up');
					foreach ($study as $studies){
						query_posts( array( 'post_type' => 'case_studies',
							'posts_per_page' => 1,
							'meta_query'=> array(
								array(
									"key" => "featured_case_study",
									"value" => 'a:1:{i:0;s:8:"featured";}'
								)
							),
							'tax_query' => array(
								array(
								'taxonomy' => 'types',
								'field' => 'slug',
								'terms' => $studies
								)
							)
							) );
						if (have_posts()) : while (have_posts()) : the_post();
							
							echo '<div id="'.$studies.'" class="three columns mobile-one phone-two"><div id="" class="case_study">';
							echo '<div id="'.$studies.'_excerpt" class="case_study_excerpt"><a class="close" href="#">x</a>';
							echo '<h4 class="excerpt_header">'.get_the_title().'</h4>';
							echo '<p>'.get_the_excerpt().'</p>';
							echo '<a class="read_more" href="'.get_permalink().'">Read More</a></div>';
							echo '<div id="'.$studies.'_click" class="featured_click">';
							echo '<a id="'.$studies.'_link" href="'.get_permalink().'">';
							echo '<div class="img_surround">';
							the_post_thumbnail('thumb-239');
							echo '</div>';
							echo '<div class="featured_title">';
							echo '<h3>'.preg_replace('/_/', ' ', $studies).'</h3>';
							echo '<p>'.get_the_title().'</p>';
							echo '</div></a></div></div></div>';
						endwhile; endif; wp_reset_query();
					} ?>
					
					
/////////////////////// get rid of admin bar /////////////////////////////////////////////////////////////////////////////////////////

<?php
/* Disable the Admin Bar. */
add_filter( 'show_admin_bar', '__return_false' );
?>

///////////////////// script to update category count ////////////////////////////////////////////////////////////////////////

<?php
// Place this file in your root directory and navigate to it.  Script will correct counts for comments and categories.
include("wp-config.php");

if (!mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {  die('Could not connect: ' . mysql_error());  }
if (!mysql_select_db(DB_NAME)) {  die('Could not connect: ' . mysql_error());  }

$result = mysql_query("SELECT term_taxonomy_id FROM ".$table_prefix."term_taxonomy");
while ($row = mysql_fetch_array($result)) {
  $term_taxonomy_id = $row['term_taxonomy_id'];
  echo "term_taxonomy_id: ".$term_taxonomy_id." count = ";
  $countresult = mysql_query("SELECT count(*) FROM ".$table_prefix."term_relationships WHERE term_taxonomy_id = '$term_taxonomy_id'");
  $countarray = mysql_fetch_array($countresult);
  $count = $countarray[0];
  echo $count."<br />";
 mysql_query("UPDATE ".$table_prefix."term_taxonomy SET count = '$count' WHERE term_taxonomy_id = '$term_taxonomy_id'");
		}

$result = mysql_query("SELECT ID FROM ".$table_prefix."posts");
while ($row = mysql_fetch_array($result)) {
  $post_id = $row['ID'];
  echo "post_id: ".$post_id." count = ";
  $countresult = mysql_query("SELECT count(*) FROM ".$table_prefix."comments WHERE comment_post_ID = '$post_id' AND comment_approved = 1");
  $countarray = mysql_fetch_array($countresult);
  $count = $countarray[0];
  echo $count."<br />";
  mysql_query("UPDATE ".$table_prefix."posts SET comment_count = '$count' WHERE ID = '$post_id'");
		}
?>

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Custom excerpt length by passing arg
<?php
function grav_excerpt($new_length = 20) {
  add_filter('excerpt_length', function () use ($new_length) {
    return $new_length;
  }, 999);
  
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  $output = '<p>' . $output . '</p>';
  echo $output;
}
?>

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///Pagination for custom query

<?php  
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;  
	query_posts('post_type=news&paged='.$paged);
?>

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Get current object name 

<?php $termname = $wp_query->queried_object->name; 
		echo $termname;?>