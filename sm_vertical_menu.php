<?php 
/**
Plugin Name:SM Vertical Menu
Plugin URI: http://www.mahabub.me
Author: Mahabubur Rahman
Author URI: http://www.mahabub.me
Version:1.1.0
Description: Wordpress widget menu for show menu vertically in your site side bar.
*/
class SMVerticalMenu extends WP_Widget
{
	
	/**
	 * Register widget with WordPress.
	 */
	function __construct()
	{
		
		$widget_ops = array( 
			'classname' => 'SMVerticalMenu',
			'description' => __( 'Create your vertical menu.', 'sm_vertical_menu' )
		);
		parent::__construct( 'sm_vertical_menu', __( 'SM Vertical Menu', 'sm_vertical_menu' ), $widget_ops );
	}




	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$select_menu = isset( $instance['select_menu'] ) ? $instance['select_menu'] : 0;
		$menu_theme = isset( $instance['menu_theme'] ) ? $instance['menu_theme'] : 'green';
		$menu_color = isset( $instance['menu_color'] ) ? $instance['menu_color'] : '#333333';
		// $getterm=get_term($select_menu, 'nav_menu' );
		?>
		<style type="text/css">
			.navigation{
				background-color: <?=$menu_color;?>;
			}
		</style>
		<?php
		$menu_items = wp_get_nav_menu_items($select_menu);

			function create_custom_menu($menuarray,$menu_theme='green'){
				echo "<div class='navigation ".$menu_theme."'><ul>";
				foreach( $menuarray as $menu_item ) {
					if( $menu_item->menu_item_parent == 0 ) {
						$parent = $menu_item->ID;
						$link=$menu_item->url;
						if(have_child($menuarray,$parent)){
							echo "<li class='has-sub'><a href='".$link."'>".$menu_item->title."</a>";
								create_custom_sub_menu($menuarray,$parent);
							echo "</li>";
						}else{
							echo "<li><a href='".$link."'>".$menu_item->title."</a></li>";							
						}
					}
				}
				echo "</ul></div>";
			}
			function create_custom_sub_menu($menuarray,$parent_id){
				echo "<ul>";
				foreach( $menuarray as $menu_item ) {
					if( $menu_item->menu_item_parent == $parent_id ) {
						$parent = $menu_item->ID;
						$link=$menu_item->url;
						if(have_child($menuarray,$parent)){
							echo "<li class='has-sub'><a href='".$link."'>".$menu_item->title."</a>";
								create_custom_sub_menu($menuarray,$parent);
							echo "</li>";
						}else{
							echo "<li><a href='".$link."'>".$menu_item->title."</a></li>";							
						}
					}
				}
				echo "</ul>";
			}

			function have_child($menuarray,$parent_id){
				foreach( $menuarray as $menu_item ) {
					if( $menu_item->menu_item_parent == $parent_id ) {
						return true;
					}
				}
				return false;
			}

			create_custom_menu($menu_items,$menu_theme);
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );

		$select_menu = isset( $instance['select_menu'] ) ? $instance['select_menu'] : '';

		$menu_theme = isset( $instance['menu_theme'] ) ? $instance['menu_theme'] : '';

		$menu_color = isset( $instance['menu_color'] ) ? $instance['menu_color'] : '#333333';

		// Get menus
		$menus = wp_get_nav_menus();
		// var_dump($menus);
		?>
		<style type="text/css">
			.wp-picker-container{
				display: block !important;
				margin-top: 5px !important;
			}
		</style>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'select_menu' ) ); ?>"><?php _e( esc_attr( 'Select Menu:' ) ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'select_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'select_menu' ) ); ?>">
				<option>Select Menu</option>
				<?php foreach ($menus as $key => $menu) {
					if($select_menu==$menu->term_id){
						echo "<option value='".$menu->term_id."' selected>".$menu->name."</option>";						
					}else{
						echo "<option value='".$menu->term_id."'>".$menu->name."</option>";	
					}
				} ?>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'menu_theme' ) ); ?>"><?php _e( esc_attr( 'Menu Theme:' ) ); ?></label> 
			<?php 
			$menu_themes=array(
				array('id'=>'green','name'=>'Green'),
				array('id'=>'blue','name'=>'Blue'),
				array('id'=>'orenge','name'=>'Orenge'),
				)
			 ?>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'menu_theme' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'menu_theme' ) ); ?>">
				<option>Select Menu Theme</option>
				<?php foreach ($menu_themes as $key => $theme) {
					if($menu_theme==$theme['id']){
						echo "<option value='".$theme['id']."' selected>".$theme['name']."</option>";						
					}else{
						echo "<option value='".$theme['id']."'>".$theme['name']."</option>";	
					}
				} ?>
			</select>
		</p>

		<p>		
			<label for="<?php echo esc_attr( $this->get_field_id( 'menu_color' ) ); ?>"><?php _e( esc_attr( 'Menu Background Color:' ) ); ?></label> 
			<input class="widefat menu_color" id="<?php echo esc_attr( $this->get_field_id( 'menu_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'menu_color' ) ); ?>" type="text" value="<?php echo esc_attr( $menu_color ); ?>">		
			<!-- <input type="text" value="#333333" class="menu_color" /> -->
		</p>

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['select_menu'] = ( ! empty( $new_instance['select_menu'] ) ) ? strip_tags( $new_instance['select_menu'] ) : '';
		$instance['menu_theme'] = ( ! empty( $new_instance['menu_theme'] ) ) ? strip_tags( $new_instance['menu_theme'] ) : '';
		$instance['menu_color'] = ( ! empty( $new_instance['menu_color'] ) ) ? strip_tags( $new_instance['menu_color'] ) : '#333333';

		return $instance;
	}

} // class Foo_Widget

add_action( 'widgets_init', function(){
	register_widget( 'SMVerticalMenu' );
});

function sm_vertical_menu_includes(){
	wp_enqueue_style( 'slider', plugin_dir_url( __FILE__ ) . '/assets/css/style.css',false,'1.1','all');
}

add_action( 'wp_enqueue_scripts', 'sm_vertical_menu_includes' );

add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('/assets/js/custom.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}