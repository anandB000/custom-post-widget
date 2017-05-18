<?php
/* 
Plugin Name: Custom posts Widget 
Description:  Custom wordpress widget.
Version: 0.1
Author: Anandaraj*/

//CPT
add_action('init','widget_CPT');
function widget_CPT(){
	$singular = 'CWP';$plural = 'CWPs';
	$labels = array( 'name' => $singular, 'singular_name' => $singular, 'add_new' => 'Add New '. $singular, 'add_new_item' => 'Add New', 'edit_item' => 'Edit '.$singular, 'new_item' => 'New '.$singular, 'all_items' => 'All '.$plural, 'view_item' => 'View', 'search_items' => 'Search', 'not_found' =>  'No '.$plural.' found', 'not_found_in_trash' => 'No '.$plural.' found in Trash', 'parent_item_colon' => '', 'menu_name' => $singular );
	
	$args = array( 'labels' => $labels, 'public' => true, 'publicly_queryable' => true, 'show_ui' => true,  'show_in_menu' => true, 'query_var' => true, 'rewrite' => array( 'slug' => 'custom_widget_posts' ), 'capability_type' => 'post', 'has_archive' => true, 'hierarchical' => false,'menu_position' => null,'supports' => array( 'title','editor','thumbnail' ), 'menu_icon' => 'dashicons-shield-alt' );	
	register_post_type( 'custom_widget_post', $args );
}

class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(
'wpb_widget', 

__('Custom Posts Widget'), 

array( 'description' => __( 'Sample post title widget' ), ) 
);
}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
	echo $args['before_title'].'Post Title'.$args['after_title'];
	$CW_postIDs = explode(',',$title);

	$args = array('post__in' => $CW_postIDs,'post_type'=>'custom_widget_post','numberposts' => -1,'orderby'  => 'post_date', 'order'  => 'DESC');
	$posts = get_posts($args);
	if($posts){
		foreach ($posts as $p) {
			echo '<p><a href="'.$p->guid.'">'.$p->post_title.'</a></p>';
		}wp_reset_postdata();
	}else{
		echo 'No post available';
	}

echo $args['after_widget'];
}
		

public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title' );
}

?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Post IDs :' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} 
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );