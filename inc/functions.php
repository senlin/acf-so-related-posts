<?php
/**
 * The actual SO Related Posts plugin files start here
 * For the function so_register_meta_boxes below I have made the box exactly how it is supposed to be and then I generated the export code via the Custom Fields > Tools menu.
 *
 * @since 1.0
 */

/**
 * Register meta box
 *
 * @since 1.0
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {

acf_add_local_field_group( 
	array (
		'key' => 'acfsorp',
		'title' => __( 'ACF SO Related Posts', 'acf-so-related-posts' ),
		'fields' => array (
			// Define the checkbox to show the related posts for this Post on the frontend
			array (
				'key' => 'acfsorp_show',
				'label' => 'Show Related Posts',
				'name' => 'acfsorp_show',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => __( 'Tick the box to show Related Posts for this Post.', 'acf-so-related-posts' ),
				'default_value' => 0,
			),
			// Define the repeater field that let's the user choose the related posts
			array (
				'key' => 'acfsorp_selector',
				'label' => __( 'Related Posts Selector', 'acf-so-related-posts' ),
				'name' => 'acfsorp_selector',
				'type' => 'repeater',
				'instructions' => __( 'If you want to add related posts under this Post, then go ahead by selecting them here.', 'acf-so-related-posts' ),
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'acfsorp_show',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'collapsed' => '',
				'min' => '',
				'max' => '',
				'layout' => 'row',
				'button_label' => __( 'Add Related Post', 'acf-so-related-posts' ),
				'sub_fields' => array (
					array (
						'key' => 'acfsorp_related_post',
						'label' => __( 'Related Post', 'acf-so-related-posts' ),
						'name' => 'acfsorp_related_post',
						'type' => 'page_link',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array (
							0 => 'post',
						),
						'taxonomy' => array (
						),
						'allow_null' => 0,
						'multiple' => 0,
					),
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	),
	// ACF SO Related Posts Plugin Settings Page
	array (
		'key' => 'acfsorp',
		'title' => 'ACF SO Related Posts Settings',
		'fields' => array (
			array (
				'key' => 'acfsorp_title',
				'label' => 'Title above Related Posts list (on frontend)',
				'name' => 'acfsorp_title',
				'type' => 'text',
				'instructions' => 'Change the title above the Related Posts list into something of your liking',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Related Posts',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'acfsorp_showthumbs',
				'label' => 'Thumbnails',
				'name' => 'acfsorp_showthumbs',
				'type' => 'true_false',
				'instructions' => 'Check this box if you would like to show the thumbnails of the Related Posts. Please bear in mind that this only works if your Posts have a Featured Image.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'Ticked means that it will show the thumbs on the frontend',
				'default_value' => 0,
			),
			array (
				'key' => 'acfsorp_thumbsize',
				'label' => 'Select the size of the thumbnails',
				'name' => 'acfsorp_thumbsize',
				'type' => 'text',
				'instructions' => 'Here you can adjust the size of the thumbnail. The default thumbnail size is 50x50 pixels, which includes auto-generation of a thumb of 100x100 pixels for Retina. ',
				'required' => 0,
				'conditional_logic' => array (
					array (
						array (
							'field' => 'acfsorp_showthumbs',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array (
					'width' => '',
					'class' => 'acfsorp-thumbsize',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => 50,
				'prepend' => '',
				'append' => 'px',
				'maxlength' => 3,
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'acfsorp_styling',
				'label' => 'Style output',
				'name' => 'acfsorp_styling',
				'type' => 'textarea',
				'instructions' => 'The output of the SO Related Posts plugin comes with the following classes that you can adjust to your heart\'s content. 
	
	<ul><li>div container: <code>.acfso-related-posts {}</code></li><li>title: <code>.acfso-related-posts h4 {}</code></li><li>unordered list: <code>ul.related-posts {}</code></li><li>list-items: <code>ul.related-posts li {}</code></li><li>anchor: <code>ul.related-posts li a {}</code></li><li>image (if you show the thumbnails): <code>img.related-post-thumb {}</code></li><li>post title: <code>ul.related-posts li span.title {}</code></li></ul>
	
	As an example you could use the following styling if you choose to show the thumbs:
	<pre>.related-posts { list-style: none; margin-left: 0; }<br />.related-posts li { clear: both; margin-bottom: 10px; min-height: 50px; width: 100%; }<br />img.related-post-thumb { float: left; margin-right: 3%; }<br />.related-posts .title { line-height: 40px; }</pre>
	
	Another simple example to show the Related Posts as a numbered list without thumbs can be like this: 
	<pre>.related-posts { list-style: decimal; }<br />.related-posts a { text-decoration: none; }<br />.related-posts a:hover { text-decoration: underline; }</pre>',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => '',
				'new_lines' => 'wpautop',
				'readonly' => 0,
				'disabled' => 0,
			),
			array (
				'key' => 'acfsorp_restore_defaults',
				'label' => 'Restore default settings',
				'name' => 'acfsorp_restore_defaults',
				'type' => 'true_false',
				'instructions' => 'Only check this if you want to reset plugin settings upon Plugin reactivation.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'Restore defaults upon plugin deactivation/reactivation',
				'default_value' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'acfsorp',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	)	
);

} //endif ( function_exists( 'acf_add_local_field_group' ) )

/**
 * Place the output at the bottom of the_content()
 * The output comes in its own class, so you can customise it with CSS all you want.
 *
 * @since 1.0
 */
function acfsorp_output( $content ) {

	if ( is_main_query() && is_single() ) {

		$showposts = get_post_meta( get_the_ID(), 'acfsorp_show', true );
		$related_posts = get_post_meta( get_the_ID(), 'acfsorp_selector', true );
		$options = get_option( 'acfsorp_options' );
		$title = $options['acfsorp_title'];
		// check whether showing the thumbs option has been set: //wordpress.stackexchange.com/a/25409/2015
		$showthumbs = isset( $options['acfsorp_showthumbs'] ) ? esc_attr( $options['acfsorp_showthumbs'] ) : '';

		if ( $showposts == 1 && ! empty( $related_posts ) ) {
		
			if ( ! empty( $title ) ) {
				$content .= '<div class="acfso-related-posts"><h4>' . $title . '</h4><ul class="related-posts">';
			} else {
				$content .= '<div class="acfso-related-posts"><h4>' . __( 'Related Posts', 'acf-so-related-posts' ) . '</h4><ul class="related-posts">';
			}
			
			for( $i = 0; $i < $related_posts; $i++ ) {
				
				$related_post = esc_html( get_post_meta( get_the_ID(), 'acfsorp_selector_' . $i . '_acfsorp_related_post', true ) );
				
				if ( $showthumbs == 1 && has_post_thumbnail( $related_post ) ) {
					$feat_img = get_post_thumbnail_id( $related_post );
					$img_url = wp_get_attachment_url( $feat_img, 'thumbnail' ); //get thumbnail URL to image (use "full", "large" or "medium" if the image is too small)
					$thumb = aq_resize( $img_url, 50, 50, true ); //resize & crop the image
					$thumb2x = aq_resize( $img_url, 100, 100, true ); //resize & crop the image retina

					$content .= '<li><a href="' . esc_url( get_permalink( $related_post ) ) . '" title="' . esc_attr( get_the_title( $related_post ) ) . '"><img class="related-post-thumb" src="' . $thumb . '" srcset="' . $thumb . ' 1x, ' . $thumb2x . ' 2x" width="50" height="50" /><span class="title">' . esc_attr( get_the_title( $related_post ) ) . '</span></a></li>';
					
				} else {
					$content .= '<li><a href="' . esc_url( get_permalink( $related_post ) ) . '" title="' . esc_attr( get_the_title( $related_post ) ) . '"><span class="title">' . esc_attr( get_the_title( $related_post ) ) . '</span></a></li>';
				}
			
			};
			
			unset( $related_post );
			
			$content .= '</ul></div>';

		}

	}

	return $content;

}

function acfsorp_styling() {
	
	$showposts = get_post_meta( get_the_ID(), 'acfsorp_show', true );

	// make sure that Related Posts Styling only shows when it is Main Query, a Single Post and when showing has been set to true
	if ( is_main_query() && is_single() && $showposts == 1 ) {

		$options = get_option( 'acfsorp_options' );
		$styling = $options['acfsorp_styling'];

		if ( ! empty( $styling ) ) { ?>
			
			<style type="text/css" id="acfso-related-posts-css"><?php echo $styling; ?></style>
			
		<?php }
	}
}

if( function_exists( 'acf_add_options_page' ) ) {
 
	$option_page = acf_add_options_page(
		array(
			'parent_slug'	=> 'options-general.php',
			'page_title' 	=> 'ACF SO Related Posts Settings',
			'menu_title' 	=> 'Related Posts Settings',
			'menu_slug' 	=> 'acfsorp',
			//'position'		=> false,
			'capability' 	=> 'manage_options',
			'redirect'		=> false
		)
	);
 
}