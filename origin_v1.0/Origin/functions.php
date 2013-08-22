<?php 
add_action( 'after_setup_theme', 'et_setup_theme' );
if ( ! function_exists( 'et_setup_theme' ) ){
	function et_setup_theme(){
		global $themename, $shortname, $et_store_options_in_one_row;
		
		$themename = 'Origin';
		$shortname = 'origin';
		
		$template_directory = get_template_directory();
		$et_store_options_in_one_row = true;
	
		require_once( $template_directory . '/epanel/custom_functions.php' ); 

		require_once( $template_directory . '/includes/functions/comments.php' );

		require_once( $template_directory . '/includes/functions/sidebars.php' );

		load_theme_textdomain( 'Origin', $template_directory . '/lang' );

		require_once( $template_directory . '/epanel/options_origin.php' );

		require_once( $template_directory . '/epanel/core_functions.php' );

		require_once( $template_directory . '/epanel/post_thumbnails_origin.php' );
		
		include( $template_directory . '/includes/widgets.php' );
		
		require_once( $template_directory . '/includes/additional_functions.php' );
		
		add_action( 'init', 'et_register_main_menus' );
		
		add_filter( 'wp_page_menu_args', 'et_add_home_link' );
		
		add_action( 'wp_enqueue_scripts', 'et_origin_load_scripts_styles' );
		
		add_action( 'wp_head', 'et_add_viewport_meta' );
		
		add_action( 'pre_get_posts', 'et_home_posts_query' );
		
		add_filter( 'et_get_additional_color_scheme', 'et_remove_additional_stylesheet' );
		
		add_action( 'wp_enqueue_scripts', 'et_add_responsive_shortcodes_css', 11 );
	}
}

function et_register_main_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu', 'Origin' )
		)
	);
}

function et_add_home_link( $args ) {
	// add Home link to the custom menu WP-Admin page
	$args['show_home'] = true;
	return $args;
}

function et_origin_load_scripts_styles(){
	$template_dir = get_template_directory_uri();
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'Origin' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language, translate
		   this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'Origin' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:300italic,700italic,800italic,400,300,700,800',
			'subset' => $subsets
		);
		
		wp_enqueue_style( 'origin-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );
	}

	wp_enqueue_script( 'fitvids', $template_dir . '/js/jquery.fitvids.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'infinitescroll', $template_dir . '/js/jquery.infinitescroll.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'custom_script', $template_dir . '/js/custom.js', array( 'jquery' ), '1.0', true );
	
	wp_localize_script( 'custom_script', 'et_origin_strings', array( 'load_posts' => __( 'Loading new posts...', 'Origin' ), 'no_posts' => __( 'No more posts to load', 'Origin' ) ) );
	
	/*
	 * Loads the main stylesheet.
	 */
	wp_enqueue_style( 'origin-style', get_stylesheet_uri() );
}

function et_add_viewport_meta(){
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
}

function et_remove_additional_stylesheet( $stylesheet ){
	global $default_colorscheme;
	return $default_colorscheme;
}

/**
 * Filters the main query on homepage
 */
function et_home_posts_query( $query = false ) {
	/* Don't proceed if it's not homepage or the main query */
	if ( ! is_home() || ! is_a( $query, 'WP_Query' ) || ! $query->is_main_query() ) return;
		
	/* Set the amount of posts per page on homepage */
	$query->set( 'posts_per_page', et_get_option( 'origin_homepage_posts', '6' ) );
	
	/* Exclude categories set in ePanel */
	$exclude_categories = et_get_option( 'origin_exlcats_recent', false );
	if ( $exclude_categories ) $query->set( 'category__not_in', $exclude_categories );
}

if ( ! function_exists( 'et_list_pings' ) ){
	function et_list_pings($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
	<?php }
}

if ( ! function_exists( 'et_get_the_author_posts_link' ) ){
	function et_get_the_author_posts_link(){
		global $authordata, $themename;
		
		$link = sprintf(
			'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
			get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
			esc_attr( sprintf( __( 'Posts by %s', $themename ), get_the_author() ) ),
			get_the_author()
		);
		return apply_filters( 'the_author_posts_link', $link );
	}
}

if ( ! function_exists( 'et_get_comments_popup_link' ) ){
	function et_get_comments_popup_link( $zero = false, $one = false, $more = false ){
		global $themename;
		
		$id = get_the_ID();
		$number = get_comments_number( $id );

		if ( 0 == $number && !comments_open() && !pings_open() ) return;
		
		if ( $number > 1 )
			$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', $themename) : $more);
		elseif ( $number == 0 )
			$output = ( false === $zero ) ? __('No Comments',$themename) : $zero;
		else // must be one
			$output = ( false === $one ) ? __('1 Comment', $themename) : $one;
			
		return '<span class="comments-number">' . '<a href="' . esc_url( get_permalink() . '#respond' ) . '">' . apply_filters('comments_number', $output, $number) . '</a>' . '</span>';
	}
}

if ( ! function_exists( 'et_postinfo_meta' ) ){
	function et_postinfo_meta( $postinfo, $date_format, $comment_zero, $comment_one, $comment_more ){
		global $themename;
		
		$postinfo_meta = '';
			
		if ( in_array( 'date', $postinfo ) )
			$postinfo_meta .= ' ' . ( is_single() ? esc_html__('on',$themename) . ' ' : '' ) . get_the_time( $date_format );
			
		if ( in_array( 'author', $postinfo ) )
			$postinfo_meta .= ' ' . esc_html__('by',$themename) . ' ' . et_get_the_author_posts_link();
			
		if ( in_array( 'categories', $postinfo ) )
			$postinfo_meta .= ' ' . esc_html__('in',$themename) . ' ' . get_the_category_list(', ');
			
		if ( in_array( 'comments', $postinfo ) )
			$postinfo_meta .= ' | ' . et_get_comments_popup_link( $comment_zero, $comment_one, $comment_more );
			
		if ( '' != $postinfo_meta && is_single() ) $postinfo_meta = __('Posted',$themename) . ' ' . $postinfo_meta;	
			
		echo $postinfo_meta;
	}
}

if ( function_exists( 'get_custom_header' ) ) {
	// compatibility with versions of WordPress prior to 3.4
	
	add_action( 'customize_register', 'et_origin_customize_register' );
	function et_origin_customize_register( $wp_customize ) {
		$wp_customize->remove_section( 'title_tagline' );

		$wp_customize->add_setting( 'et_origin[sidebar_bg_color]', array(
			'default'		=> '#6ab3b2',
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options',
			'transport'		=> 'postMessage'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'et_origin[sidebar_bg_color]', array(
			'label'		=> __( 'Sidebar Background Color', 'Origin' ),
			'section'	=> 'colors',
			'settings'	=> 'et_origin[sidebar_bg_color]',
		) ) );
		
		$wp_customize->add_setting( 'et_origin[sidebar_borders_color]', array(
			'default'		=> '#5EA5A4',
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options',
			'transport'		=> 'postMessage'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'et_origin[sidebar_borders_color]', array(
			'label'		=> __( 'Sidebar Borders Color', 'Origin' ),
			'section'	=> 'colors',
			'settings'	=> 'et_origin[sidebar_borders_color]',
		) ) );
		
		$wp_customize->add_setting( 'et_origin[sidebar_active_link_bg]', array(
			'default'		=> '#ffffff',
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options',
			'transport'		=> 'postMessage'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'et_origin[sidebar_active_link_bg]', array(
			'label'		=> __( 'Sidebar Menu Active Link Background Color', 'Origin' ),
			'section'	=> 'colors',
			'settings'	=> 'et_origin[sidebar_active_link_bg]',
		) ) );
		
		$wp_customize->add_setting( 'et_origin[sidebar_dropdown_link_bg]', array(
			'default'		=> '#f8f8f8',
			'type'			=> 'option',
			'capability'	=> 'edit_theme_options',
			'transport'		=> 'postMessage'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'et_origin[sidebar_dropdown_link_bg]', array(
			'label'		=> __( 'Sidebar Dropdown Menu Link Background Color', 'Origin' ),
			'section'	=> 'colors',
			'settings'	=> 'et_origin[sidebar_dropdown_link_bg]',
		) ) );
	}

	add_action( 'customize_preview_init', 'et_origin_customize_preview_js' );
	function et_origin_customize_preview_js() {
		wp_enqueue_script( 'origin-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), false, true );
	}

	add_action( 'wp_head', 'et_origin_add_customizer_css' );
	add_action( 'customize_controls_print_styles', 'et_origin_add_customizer_css' );
	function et_origin_add_customizer_css(){ ?>
		<style>
			#info-bg { background: <?php echo esc_html( et_get_option( 'sidebar_bg_color', '#6ab3b2' ) ); ?>; }
			#top-menu a:hover .link_text, .current-menu-item > a, #top-menu .current-menu-item > a:hover, #top-menu .current-menu-item > a:hover .link_bg, .et_active_dropdown > li a, #top-menu .et_clicked, #mobile-nav { color: <?php echo esc_html( et_get_option( 'sidebar_bg_color', '#6ab3b2' ) ); ?>; }
			
			@media only screen and (max-width: 1023px){ 
				#info-area { background: <?php echo esc_html( et_get_option( 'sidebar_bg_color', '#6ab3b2' ) ); ?>; }
			}

			.widget, #top-menu a, #mobile-nav, #info-area, #info-bg, #top-menu { border-color: <?php echo esc_html( et_get_option( 'sidebar_borders_color', '#5EA5A4' ) ); ?>; }
			
			.current-menu-item > a, .et_active_dropdown > li a, #top-menu .et_clicked, #mobile-nav, #top-menu a:hover .link_bg, #top-menu .current-menu-item > a:hover, #top-menu .current-menu-item > a:hover .link_bg { background: <?php echo esc_html( et_get_option( 'sidebar_active_link_bg', '#fff' ) ); ?>; }
			
			#top-menu ul ul a:hover .link_bg { background: <?php echo esc_html( et_get_option( 'sidebar_dropdown_link_bg', '#f8f8f8' ) ); ?>; }
		</style>
	<?php }
}