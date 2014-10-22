<?php
/**
 * Plugin Name: Cuadric Page Navi
 * Plugin URI: http://www.cuadric.com
 * Description: Sistema de paginación mejorado
 * Author: Gonzalo Sanchez
 * Author URI: http://blog.cuadric.com
 * Version: 1.0
 */ 

function cuadric_page_navi_enqueue() {
	wp_enqueue_style( 'cpn_styles', plugins_url( 'style_geberit.css' , __FILE__ ), array(), '1', 'all' );
	// wp_enqueue_script( 'cpn_scripts', plugins_url( 'script.js', __FILE__ ), array('jquery'), '1' );
}

add_action('wp_enqueue_scripts', 'cuadric_page_navi_enqueue', 100);







// navegación de paginación
function cuadric_page_navi( $current_query = NULL ){

	global $wp_query;

	$main_query_replaced = false;

	if ( $current_query ) :
		$main_query_replaced = true;
		$original_wp_query = $wp_query;
		$wp_query = $current_query;
	endif;

	if ( is_single() ) : ?>
		
		<div class="navigation single cuadric_page_navi">
	
			<div class="prev-post"><?php previous_post_link('%link', '<span class="nav_label">' . __('&laquo; Previous') . '<br></span><span class="nav_text">' . '%title' . '</span>' ) ?></div>
			<div class="next-post"><?php next_post_link( '%link', '<span class="nav_label">' . __('Next &raquo;') . '<br></span><span class="nav_text">' . '%title' . '</span>' ) ?></div>

		</div>
		
		
	<?php else :

		if ( $wp_query->max_num_pages > 1 ) : // solo mostramos la navigation si hay más de una página de posts ?>
		
			<div class="navigation archive cuadric_page_navi">
	
				<?php
				
				$big = 999999999; // need an unlikely integer
				
				$num_items = 13; // debe ser siempre impar!!!! /  la cantidad de items que debe haber siempre
				$end_size = 1; // siempre debe ser menor que ($num_items/2)-1
				$mid_size = floor( ($num_items - ($end_size*2) - 1) / 2 );
				$side_size = $mid_size + $end_size;
				
				$current =  max( 1, get_query_var('paged') );
				
				if ( $current <= $side_size){
					$mid_size = ($side_size+1)-$current + $mid_size;
				}
				
				$args = array(
					'base' 			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' 		=> '/page/%#%',
					'current' 		=> $current,
					'total' 		=> $wp_query->max_num_pages,
					'show_all' 		=> false,
					'end_size' 		=> $end_size,
					'mid_size' 		=> $mid_size,
					'prev_next' 	=> true,
					
					'prev_text' 	=> apply_filters('cuadric_page_navi_prev_text',__('&laquo; Previous', 'cuadric_page_navi')),
					'next_text' 	=> apply_filters('cuadric_page_navi_next_text',__('Next &raquo;', 'cuadric_page_navi')),
					
					'type' 			=> 'list',
					
					//'add_args'     => array('id'=>5, 'post'=>'all'), 	// agrega un hash al final del link de paginación 'www.misitio.com/page/3/?id=5&post=all'
					//'add_fragment' => 'page', 						// agrega un string al final del link de paginación generado 'www.misitio.com/page/3/mistring'
				);

				echo paginate_links( $args );
				?>

			</div>

		<?php endif;

		endif;


		if ( $main_query_replaced ) :
			$wp_query = $original_wp_query;
		endif;
}