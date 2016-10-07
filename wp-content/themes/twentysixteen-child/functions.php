<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}

function remove_admin_menu_items() {
  $remove_menu_items = array(__('Posts'),__('Links'),__('Pages'), __('Comments'), __('Media'), __('Media'), __('Plugins'), __('Users'), __('Tools'), __('Settings'), __('Custom Fields'), __('Dashboard'), __('Media'));
  global $menu;
  end ($menu);
  while (prev($menu)){
    $item = explode(' ',$menu[key($menu)][0]);
    if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
      unset($menu[key($menu)]);
    }
  }
 //remove_menu_page( 'edit.php?post_type=acf-field-group' );      
 
}
add_action('admin_menu', 'remove_admin_menu_items');



function wpse_136058_remove_menu_pages() {

    remove_menu_page( 'admin.php?page=cptui_manage_post_types' );
    
}
add_action( 'admin_init', 'wpse_136058_remove_menu_pages' );

function delete_custom_post_data() {
		add_menu_page(__( 'Reset All Data', 'reset-all-data' ), 'Reset all data', 'manage_options', 'reset-all-data', 'reset_all_data', 'dashicons-trash');
	}
	add_action('admin_menu', 'delete_custom_post_data');

	function reset_all_data() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		/*$args = array(
	   'public'   => true,
	   '_builtin' => false
	);
	get_post_types($args);*/
	$args = array(
		'post_type' =>array('bfscore','bfuser')
	);
		$black_friday_custom_posts = get_posts($args);
	   foreach( $black_friday_custom_posts as $black_friday_custom_post) {
				 /*if($black_friday_custom_post->post_type == 'bfproduct_images'){
				 		$product_images = get_field('bgproduct_images_group', $black_friday_custom_post->ID);
				 		foreach($product_images as $product_image){
				 			wp_delete_attachment($product_image['ID'], true );
				 		}
			 	}
				if($black_friday_custom_post->post_type == 'bggame_content'){
						wp_delete_attachment(get_field('bf_logo', $black_friday_custom_post->ID)['ID'], true );
						wp_delete_attachment(get_field('bfr_banner_image', $black_friday_custom_post->ID)['ID'], true );
						$bfprizes = get_field('bfprizes', $black_friday_custom_post->ID);
						foreach($bfprizes as $bfprize){
								wp_delete_attachment($bfprize['bfr_prize_image']['ID'], true);
						}
				}*/
		    wp_delete_post( $black_friday_custom_post->ID, true);
		    // Set to False if you want to send them to Trash.
	   }
		echo '<div class="wrap" style="color:red">';
		echo '<p>Your posts data has been deleted.</p>';
		echo '</div>';
}
add_filter( 'post_row_actions', 'remove_row_actions', 10, 1 );
function remove_row_actions( $actions )
{
    if( get_post_type() === 'bfscore' || get_post_type() === 'bfuser'){
        unset( $actions['edit'] );
        unset( $actions['view'] );
        unset( $actions['trash'] );
        unset( $actions['inline hide-if-no-js'] );
		?>
	<script id="remove-links-in-title" type="text/javascript">
		jQuery(document).ready(function($) {
			$('.page-title, .column-meta-1').each(function() {			
				var title_link = $('a' , $(this));				
				var title_text = title_link.text();
				title_link.remove();
				if(title_text != ''){
					$(this).append('<strong>'+title_text+'</strong>');
				}
			});
		});
	</script>
	<?php
	}	
    return $actions;
}
add_filter( 'bulk_actions-' . 'edit-bfscore', '__return_empty_array' );
add_filter( 'bulk_actions-' . 'edit-bfuser', '__return_empty_array' );

function disable_new_posts() {
	// Hide sidebar link
	global $submenu;
	$cpt_array = ['bfuser', 'bfscore'];
	foreach($cpt_array as $cpt_arr ){
		unset($submenu['edit.php?post_type='.$cpt_arr][10]);
		// Hide link on listing page
		if (isset($_GET['post_type']) && $_GET['post_type'] == $cpt_arr) {
			echo '<style type="text/css">#wp-admin-bar-new-content, .page-title-action, .check-column { display:none; }</style>';
		}		
	}
}
add_action('admin_menu', 'disable_new_posts');