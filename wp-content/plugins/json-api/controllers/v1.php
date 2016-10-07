<?php

class JSON_API_v1_Controller {

	  public function get_Scores() {
			$posts = get_posts(array(
				'fields' 			=> 'ids',
				'numberposts'	=> 4,
				'post_type'			=> 'bfscore',
				'post_status' => 'publish',
				'meta_key'			=> 'bfscore',
				'orderby'			=> 'meta_value_num',
				'order'				=> 'DESC'
			));

			$xx;
			foreach($posts as $postid)
			{
				$userid= get_field('bfuserID',$postid);
				$xx[]=['score' => get_field('bfscore',$postid),'name' => get_field('bfname',$userid),'email' => get_field('bfemail',$userid)];
			}
 	
		return $xx;
	  }

     public function insert_Score() {
		 $score=$_POST['bfscore'];
		 $uid=$_POST['uid'];
		 $post_id=0;

		 $uname= get_field('bfname',$uid);


	    if($uname) :
			$postid = get_posts(array(
			'fields' 			=> 'ids',
			'numberposts'	=> 1,
			'post_type'		=> 'bfscore',
			'post_status' => 'publish',
			'meta_key'		=> 'bfuserID',
			'meta_value'	=> $uid
			));
			$dbscore=get_field('bfscore',$postid[0]);
				if($postid):
					//update if greater
					if($dbscore < $score):
						update_post_meta($postid[0], 'bfscore', $score);
						$post_id=$postid[0];
					else:
						$post_id = $postid[0];
					endif;
				else:
					//insert
					  $my_post = array(
						 'post_title' => $uname,
						 'post_status' => 'publish',
						 'post_type' => 'bfscore',
					  );
					$post_id = wp_insert_post($my_post);
					add_post_meta($post_id, 'bfscore', $score, true);
					add_post_meta($post_id, 'bfuserID', $uid, true);
				endif;
		endif;
		$Ranks = get_posts(array(
				'fields' 			=> 'ids',
				'numberposts'	=> -1,
				'post_type'			=> 'bfscore',
				'post_status' => 'publish',
				'meta_key'			=> 'bfscore',
				'orderby'			=> 'meta_value_num',
				'order'				=> 'DESC'
			));
			$rank = array_search($post_id,$Ranks);
					
		return ['id'=>$post_id, 'rank'=>$rank+1];
	 }

	 public function insert_User() {
		 $name=$_POST['bfname'];
		 $email=$_POST['bfemail'];

		 if($name && $email):
				$postid = get_posts(array(
				'fields' 			=> 'ids',
				'numberposts'	=> 1,
				'post_type'		=> 'bfuser',
				'meta_key'		=> 'bfemail',
				'meta_value'	=> $email
				));

				if($postid):
					return $postid[0];
				else:
				  $my_post = array(
						 'post_title' => $name,
						 'post_status' => 'publish',
						 'post_type' => 'bfuser',
					  );
					$post_id = wp_insert_post($my_post);
					add_post_meta($post_id, 'bfname', $name, true);
					add_post_meta($post_id, 'bfemail', $email, true);
					return $post_id;
				endif;
			endif;
	 }

	 public function login_user(){
	 	session_start();
		$name=$_POST['bfname'];
		$email=$_POST['bfemail'];

			$posts = get_posts(array(
				'fields' 			=> 'ids',
				'numberposts'	=> 1,
				'post_type'		=> 'bfuser',
				'post_status' => 'publish',
				'meta_query'	=> array(
					'relation'		=> 'AND',
					array(
						'key'	 	=> 'bfname',
						'value'	  	=> $name,
						'compare' 	=> '=',
					),
					array(
						'key'	  	=> 'bfemail',
						'value'	  	=> $email,
						'compare' 	=> '=',
					),
				),
			));
			$id=$posts[0];
			if($id > 0)
			{
				$_SESSION['usr']=['name' => $name,'email' => $email, 'id' => $id];
				return ['status'=>'loggedin','id' => $id];
			}
			else{
				return ['status'=>'invaliduser'];
			}

	 }

	 public function init(){
	 	session_start();
	 	$userid=$_POST['uid'];
	 		$scoreid = get_posts(array(
				'fields' 			=> 'ids',
				'posts_per_page'	=> 1,
				'post_type'			=> 'bfscore',
				'post_status' => 'publish',
				'meta_key'		=> 'bfuserID',
				'meta_value'	=> $userid,
				'compare' 	=> '='
			));
		if($userid):
			return ['score' => get_field('bfscore',$scoreid[0]),'name' => get_field('bfname',$userid),'email' => get_field('bfemail',$userid)];
		else:
			return ['status'=>'No user is available'];
		endif;
	 }

	 public function status(){
		session_start();
	 	if(isset($_SESSION['usr']))
	 		return ['status'=>'loggedin'];
	 	else
	 		return ['status'=>'loggedout'];
	 }

	public function get_user(){
		 	session_start();
	 		return $_SESSION['usr'];
	 }

	public function get_GameContent(){
		 	$game_content = get_posts(array(
				'posts_per_page'	=> 1,
				'post_type'			=> 'bggame_content',
				//'orderby'          => 'modified',
				'order'				=> 'ASC',
				'post_status'     => 'publish'
			));
			//echo count($game_content);

			$game_content_array = array();
			if(count($game_content) > 0){
				foreach($game_content as $gcontent)
				{
					$game_title = get_field('bf_game_title', $gcontent->ID);
					$logo_image = get_field('bf_logo', $gcontent->ID);
					$logo_url = $logo_image['filename'];

					//Landing Page Screen
					$landing_page_content_array=array();
					$landing_page_content_array['title'] = get_field('bf_landing_page_title', $gcontent->ID);
					$landing_page_content_array['description'] = get_field('bf_landing_page_description', $gcontent->ID);
					//$landing_page_content_array['agree_text'] = get_field('bf_landing_page_agree_text', $gcontent->ID);
					//$landing_page_content_array['terms_conditions'] = get_field('bf_landing_page_terms_and_conditions_text', $gcontent->ID);
					$landing_page_content_array['terms_conditions_url'] = get_field('bf_landing_page_terms_and_conditions_url', $gcontent->ID);
					//$landing_page_content_array['challenge_button'] = get_field('bf_challenge_button_text', $gcontent->ID);

					//Challenge 1 Screen
					$bfchallenge_1_content = get_field('bfchallenge_1_content', $gcontent->ID);
					$bfchallenge_1_product_images = get_field('bgproduct_images_group', $bfchallenge_1_content->ID);

					$bfchallenge_1_content_array = array();
					$bfchallenge_1_content_array['title'] = $bfchallenge_1_content->post_title;
					$bfchallenge_1_product_images_array = array();
					foreach($bfchallenge_1_product_images as $bfchallenge_1_product_image){
						$upload_dir = wp_upload_dir();
						$image_name = $upload_dir['basedir'].'/'.$bfchallenge_1_product_image['filename'];
						if (file_exists($image_name)) {
						    $bfchallenge_1_product_images_array[] = $bfchallenge_1_product_image['filename'];
						}
					}
					$bfchallenge_1_content_array['images'] = $bfchallenge_1_product_images_array;

					//Challenge 2 Screen
					$bfchallenge_2_content = get_field('bfchallenge_2_content', $gcontent->ID);
					$bfchallenge_2_coupons = get_field('bf_coupon_group', $bfchallenge_2_content->ID);
					$bfchallenge_2_content_array = array();
					$bfchallenge_2_content_array['title'] = $bfchallenge_2_content->post_title;
					$bfchallenge_2_coupons_array = array();
					foreach($bfchallenge_2_coupons as $bfchallenge2){
						$bfchallenge_2_coupons_array[] = $bfchallenge2['bfcode'];
					}
					$bfchallenge_2_content_array['coupons'] = $bfchallenge_2_coupons_array;

					//Common fields for challenges Screen
					/*$bfchallenges_common_fields_array = array();
					//$bfchallenges_common_fields_array['how_to_play_text'] = get_field('bfc_how_to_play_text', $gcontent->ID);
					// How to play description text
					$bfchallenges_common_desc_fields = get_field('bfc_how_to_play_description_text', $gcontent->ID);
					$bfchallenges_common_desc_fields_array = array();
					foreach ($bfchallenges_common_desc_fields as $bfchallenges_common_desc_field) {
						$bfchallenges_common_desc_fields_array[] = $bfchallenges_common_desc_field['bfc_how_to_play_desc'];
					}
					$bfchallenges_common_fields_array['how_to_play_description'] = $bfchallenges_common_desc_fields_array;
					$bfchallenges_common_fields_array['start_button_text'] = get_field('bfc_start_button_text', $gcontent->ID);
					$bfchallenges_common_fields_array['seconds_left_text'] = get_field('bfc_seconds_left_text', $gcontent->ID);
					// Basket or coupon label text
					/*$bfchallenges_common_b_or_c_fields = get_field('bfc_basket_or_coupon_label_text', $gcontent->ID);
					$bfchallenges_common_b_or_c_fields_array = array();
					foreach ($bfchallenges_common_b_or_c_fields as $bfchallenges_common_b_or_c_field) {
						$bfchallenges_common_b_or_c_fields_array[] = $bfchallenges_common_b_or_c_field['bfc_basket_or_coupon'];
					}
					$bfchallenges_common_fields_array['basket_or_coupon_label_text'] = $bfchallenges_common_b_or_c_fields_array;*/
					//$bfchallenges_common_fields_array['total_label_text'] = get_field('bfc_total_label_text', $gcontent->ID);
					// No of items label text
					/*$no_of_items_labels_text = get_field('bfc_no_of_items_label_text', $gcontent->ID);
					$no_of_items_label_text_array = array();
					foreach ($no_of_items_labels_text as $no_of_items_label_text) {
						$no_of_items_label_text_array[] = $no_of_items_label_text['bfc_no_of_items_text'];
					}
					$bfchallenges_common_fields_array['no_of_items_label_text'] = $no_of_items_label_text_array;
					// Total items description text
					$bfchallenges_common_fields_array['total_items_description_text'] = get_field('bfc_total_items_description_text', $gcontent->ID);
					/*$total_items_description_text = get_field('bfc_total_items_description_text', $gcontent->ID);
					$total_items_description_text_array = array();
					foreach ($total_items_description_text as $total_items_desc_text) {
						$total_items_description_text_array[] = $total_items_desc_text['bfc_total_items_desc'];
					}
					$bfchallenges_common_fields_array['total_items_description_text'] =$total_items_description_text_array;*/
					// Checkout or viewscore button text
					/*$checkout_or_viewscore_button_text = get_field('bfc_checkout_or_viewscore_button_text', $gcontent->ID);
					$checkout_or_viewscore_button_text_array = array();
					foreach ($checkout_or_viewscore_button_text as $checkout_viewscore_button_text) {
						$checkout_or_viewscore_button_text_array[] = $checkout_viewscore_button_text['bfc_checkout_or_view_score_button_text'];
					}
					$bfchallenges_common_fields_array['checkout_or_viewscore_button_text'] = $checkout_or_viewscore_button_text_array;
					$bfchallenges_common_fields_array['enter_code_label_text'] = get_field('bfc_enter_code_label_text', $gcontent->ID);*/

					//Results Screen
					$bf_results_array = array();
					$bf_results_array['title'] = get_field('bfr_title', $gcontent->ID);
					$bf_results_array['strapline'] = get_field('bfr_strapline', $gcontent->ID);
					//$bf_results_array['redeem_now'] = get_field('bfr_redeem_now_text', $gcontent->ID);
					$bf_results_array['redeem_now_url'] = get_field('bfr_redeem_now_url', $gcontent->ID);
					//$bf_results_array['final_score'] = get_field('bfr_final_score', $gcontent->ID);
					//$bf_results_array['your_rank'] = get_field('bfr_your_rank', $gcontent->ID);
					//$bf_results_array['share_score'] = get_field('bfr_share_score', $gcontent->ID);
					//$bf_results_array['invitation'] = get_field('bfr_invitation', $gcontent->ID);
					//$bf_results_array['invitation_button'] = get_field('bfr_invitation_button', $gcontent->ID);
					//$bf_results_array['play_again_button'] = get_field('bfr_play_again_button', $gcontent->ID);
					$bf_results_array['banner_image'] = get_field('bfr_banner_image', $gcontent->ID)['filename'];
					$bf_results_array['banner_image_url'] = get_field('bfr_banner_image_url', $gcontent->ID);
					//$bf_results_array['top_scores_and_prizes'] = get_field('bfr_top_scores_and_prizes', $gcontent->ID);
					//$bf_results_array['top_scorers'] = get_field('bfr_top_scorers', $gcontent->ID);
					//$bf_results_array['win_these_prizes'] = get_field('bfr_win_these_prizes', $gcontent->ID);

					//Prizes
					$bfprizes = get_field('bfprizes', $gcontent->ID);
					$bfprizes_array = array();
					foreach($bfprizes as $key=>$bfprize){
						$bfprizes_array[$key]['prize'] = $bfprize['bfr_prizes'];
						$bfprizes_array[$key]['prize_image'] = $bfprize['bfr_prize_image']['filename'];
					}

					$game_content_array[]=['game_title' => $game_title,'logo' => $logo_url, 'bfoglanding_page_content'=>$landing_page_content_array, 'bfogchallenge_1_content' => $bfchallenge_1_content_array,
																	'bfogchallenge_2_content' => $bfchallenge_2_content_array, 'bfogresults_screen' => $bf_results_array, 'bfogprizes' => $bfprizes_array];
				}
		}else{
			$game_title = 'NULL';
			$logo_url = 'NULL';

			//Landing Page Screen
			$landing_page_content_array=array();
			$landing_page_content_array['title'] = 'NULL';
			$landing_page_content_array['description'] = 'NULL';
			//$landing_page_content_array['agree_text'] = 'NULL';
			//$landing_page_content_array['terms_conditions'] = 'NULL';
			$landing_page_content_array['terms_conditions_url'] = 'NULL';
			//$landing_page_content_array['challenge_button'] = 'NULL';

			//Challenge 1 Screen
			$bfchallenge_1_content_array['title'] = 'NULL';
			$bfchallenge_1_content_array['images'] = 'NULL';

			//Challenge 2 Screen
			$bfchallenge_2_content_array['title'] = 'NULL';
			$bfchallenge_2_content_array['coupons'] = 'NULL';

			//Common fields for challenges Screen
			/*$bfchallenges_common_fields_array = array();
			$bfchallenges_common_fields_array['how_to_play_description'] = 'NULL';
			$bfchallenges_common_fields_array['start_button_text'] = 'NULL';
			$bfchallenges_common_fields_array['seconds_left_text'] = 'NULL';
			$bfchallenges_common_fields_array['no_of_items_label_text'] = 'NULL';
			// Total items description text
			$bfchallenges_common_fields_array['total_items_description_text'] = 'NULL';
			$bfchallenges_common_fields_array['checkout_or_viewscore_button_text'] = 'NULL';
			$bfchallenges_common_fields_array['enter_code_label_text'] = 'NULL';*/

			//Results Screen
			$bf_results_array = array();
			$bf_results_array['title'] = 'NULL';
			$bf_results_array['strapline'] = 'NULL';
			//$bf_results_array['redeem_now'] = 'NULL';
			$bf_results_array['redeem_now_url'] = 'NULL';
			/*$bf_results_array['final_score'] = 'NULL';
			$bf_results_array['your_rank'] = 'NULL';
			$bf_results_array['share_score'] = 'NULL';
			$bf_results_array['invitation'] = 'NULL';
			$bf_results_array['invitation_button'] = 'NULL';
			$bf_results_array['play_again_button'] = 'NULL';*/
			$bf_results_array['banner_image'] = 'NULL';
			$bf_results_array['banner_image_url'] = 'NULL';
			//$bf_results_array['top_scores_and_prizes'] = 'NULL';
			//$bf_results_array['top_scorers'] = 'NULL';
			//$bf_results_array['win_these_prizes'] = 'NULL';

			//Prizes
			$bfprizes_array = array();
			$bfprizes_array['prize'] = 'NULL';
			$bfprizes_array['prize_image'] = 'NULL';
			$game_content_array[]=['game_title' => $game_title,'logo' => $logo_url, 'bfoglanding_page_content'=>$landing_page_content_array, 'bfogchallenge_1_content' => $bfchallenge_1_content_array,
															'bfogchallenge_2_content' => $bfchallenge_2_content_array, 'bfogresults_screen' => $bf_results_array, 'bfogprizes' => $bfprizes_array];

		}
		return $game_content_array;
	}
	public function send_Email(){
		//$email = 'vik@teamdevx.com';
		$email = $_POST['bfinvitation_email'];
		$bfemails = get_posts(array(
			'numberposts'	=> 1,
			'post_type'			=> 'bggame_content',
			'order'				=> 'ASC',
			'post_status'     => 'publish'
		));
		$mail_sent = array();
		foreach($bfemails as $bfemail){
			$from_email = get_field('bfemail_address', $bfemail->ID);
			$email_subject = get_field('bfemail_subject', $bfemail->ID);
			$email_body = get_field('bfemail_body', $bfemail->ID);
			$headers = array(
				"From: <".$from_email.">",
				"MIME-Version: 1.0",
				"Content-type: text/html; charset=UTF-8'"
			);
			$mail = wp_mail( $email, $email_subject, $email_body, $headers);
			if($mail){
				$mail_sent['status'] = 'Success';
			}else{
				$mail_sent['status'] = 'Fail';
			}
		}
		return $mail_sent;
	 }
}

?>
