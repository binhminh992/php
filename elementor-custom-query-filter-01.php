<?php
add_action( 'elementor/query/custom_query_filter', 'minh_dz_query_with_2_post_type_same_terms', 1 );
function minh_dz_query_with_2_post_type_same_terms( $query ) {
	$condition = true;
	$taxonomy_1 = 'danh_muc_san_pham'; //Thay slug của custom taxonomy bạn tạo ra
	$taxonomy_2 = 'category';
	
	do {
		if (! taxonomy_exists( $taxonomy_1 )) {
			$condition = false;
			break;
		}
		if (! taxonomy_exists( $taxonomy_2 )) {
			$condition = false;
			break;
		}
		
		//Lấy tất cả các term (slug) của taxonomy 1 của current post đưa vào 1 array
		$terms_1 = wp_get_post_terms( get_the_ID(), $taxonomy_1 );
		if (empty( $terms_1 )  || is_wp_error( $terms_1 ) ) {
			$condition = false;
			break;
		}
		$term_1_array = array();
		foreach ( $terms_1 as $term_1 ) {
			$term_1_array[] = $term_1->slug;
		}
		
		//Lấy tất cả các term (slug) của taxonomy 2 đưa vào 1 array
		$terms_2 = get_terms( array( 
    		'taxonomy' => $taxonomy_2,
			'hide_empty' => true,
		) );
		if (empty( $terms_2 ) || is_wp_error( $terms_2 )) {
			$condition = false;
			break;
		}
		$term_2_array = array();
		foreach ( $terms_2 as $term_2 ) {
			$term_2_array[] = $term_2->slug;
		}
		
		//Lấy ra các term giống nhau của 2 taxonomy và set cho query
		$result = array_intersect($term_1_array, $term_2_array);
		if (empty($result)) {
			$condition = false;
			break;			
		}
		$result_2 = implode( ",", $result );
		$query->set('category_name', $result_2);		
		
	} while (0);
	
	//Nếu 1 trong các điều kiện không thoả mãn thì ẩn posts widget và hiện thông báo
	if ($condition) {
		echo "<style>
			.custom-query-posts-widget-noti {
				display: none;
			}
		</style>";		
	} else {
		echo "<style>
			.custom-query-posts-widget {
				display: none;
			}
		</style>";
	}
}
?>
