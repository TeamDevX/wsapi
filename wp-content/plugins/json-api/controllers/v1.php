<?php

class JSON_API_v1_Controller {

	  public function get_Scores() {
			$posts = get_posts(array(
				'fields' 			=> 'ids',
				'posts_per_page'	=> -1,				
				'post_type'			=> 'bfscore',
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
			'meta_key'		=> 'bfuserID',
			'meta_value'	=> $uid
			));
			$dbscore=get_field('bfscore',$postid[0]);
				if($postid):			
					//update if greater
					if($dbscore < $score):
						update_post_meta($postid[0], 'bfscore', $score);
						$post_id=$postid[0];
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
		return $post_id;
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
			else
				return ['status'=>'invaliduser'];

	 }

	 public function init(){
	 	session_start();
	 	$userid=$_POST['uid'];
	 		$scoreid = get_posts(array(
				'fields' 			=> 'ids',
				'posts_per_page'	=> 1,
				'post_type'			=> 'bfscore',
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
			
			$game_content_array = array();
			foreach($game_content as $gcontent)
			{
				$strapline = get_field('bfstrap_line', $gcontent->ID);
				
				$bfchallenge_1_content_id = get_field('bfchallenge_1_content', $gcontent->ID);				
				$bfchallenge_1_content = get_field('bgproduct_images_group', $bfchallenge_1_content_id);
				$bfchallenge_1_content_array = array();
				foreach($bfchallenge_1_content as $bfchallenge_1){
					$bfchallenge_1_content_array[] = $bfchallenge_1['filename'];
				}
				
				$bfchallenge_2_content_id = get_field('bfchallenge_2_content', $gcontent->ID);				
				$bfchallenge_2_content = get_field('bfcoupongroup', $bfchallenge_2_content_id);	
				$bfchallenge_2_content_array = array();
				foreach($bfchallenge_2_content as $bfchallenge2){
					$bfchallenge_2_content_array[] = $bfchallenge2['bfcode'];
				}	
				
				$result_coupon_code = get_field('bfresult_coupon_code', $gcontent->ID);
				
				$bfprizes = get_field('bfprizes', $gcontent->ID);
				$bfprizes_array = array();
				foreach($bfprizes as $bfprize){
					$bfprizes_array[] = $bfprize['filename'];
				}
				
				$game_content_array[]=['game_title' => $gcontent->post_title, 'game_desc' => $gcontent->post_content,'game_strapline' => $strapline,'bfchallenge_1_content' => $bfchallenge_1_content_array, 'bfchallenge_2_content' => $bfchallenge_2_content_array, 'result_coupon_code' => $result_coupon_code, 'bfprizes' => $bfprizes_array];
			}			
		return $game_content_array;		
	}
	public function send_Email(){
		$email = 'vik@teamdevx.com';
		$bfemails = get_posts(array(				
			'posts_per_page'	=> 1,				
			'post_type'			=> 'bfemail',			
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