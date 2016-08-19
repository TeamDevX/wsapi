<?php

class JSON_API_v1_Controller {

	  public function get_Scores() {
		  
			$posts = get_posts(array(
				'fields' 			=> 'ids',
				'posts_per_page'	=> -1,
				'post_type'			=> 'bfscore'
			));

			$xx;
			foreach($posts as $postid)
			{
				$userid= get_field('bfuserID',$postid);

				$xx[]=['score' => get_field('score',$postid),'name' => get_field('name',$userid),'email' => get_field('email',$userid)];
			}
		return $xx;
	  }
  
     public function insert_Score() {
		 $score=$_GET['score'];
		 $uid=$_GET['uid'];
		 $post_id=0;
		 
		 //$userid= get_field('bfuserID',$uid);
		 $uname= get_field('name',$uid);
		 
		 
	    if($uname) :
			//$post=get_post($uid);
			$postid = get_posts(array(
			'fields' 			=> 'ids',
			'numberposts'	=> 1,
			'post_type'		=> 'bfscore',
			'meta_key'		=> 'bfuserID',
			'meta_value'	=> $uid
			));
			$dbscore=get_field('score',$postid[0]);
				if($postid):			
					//update if greater
					if($dbscore < $score):
						update_post_meta($postid[0], 'score', $score);
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
					add_post_meta($post_id, 'score', $score, true);
					add_post_meta($post_id, 'bfuserID', $uid, true);
				endif;		
		endif;
		return $post_id;
	 }
	 
	 public function insert_User() {
		 $name=$_GET['bfname'];
		 $email=$_GET['bfemail'];
		 
		 if($name && $email):
				$postid = get_posts(array(
				'fields' 			=> 'ids',
				'numberposts'	=> 1,
				'post_type'		=> 'bfuser',
				'meta_key'		=> 'email',
				'meta_value'	=> $email
				));
				
				if($postid):
				echo 1;
					return $postid[0];
					
				else:
					  $my_post = array(
							 'post_title' => $name,
							 'post_status' => 'publish',
							 'post_type' => 'bfuser',
						  );
						$post_id = wp_insert_post($my_post);
						add_post_meta($post_id, 'name', $name, true);
						add_post_meta($post_id, 'email', $email, true);
						echo 2;
						return $post_id;
				endif;
				echo 3;
			endif;
			echo 4;
	 }
	 
	 public function checkifsameServer()
	 {
		 //echo $_SERVER['HTTP_REFERER'];
		 return  $_SERVER['SERVER_ADDR'];
		 
	 }
	

	
	
	
	
}

?>