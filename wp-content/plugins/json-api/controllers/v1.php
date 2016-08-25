<?php

class JSON_API_v1_Controller {

	  public function get_Scores() {
			$posts = get_posts(array(
				'fields' 			=> 'ids',
				'posts_per_page'	=> -1,				
				'post_type'			=> 'bfscore',
				'meta_key'			=> 'score',
				'orderby'			=> 'meta_value_num',
				'order'				=> 'DESC'
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
		 
		 $uname= get_field('name',$uid);
		 
		 
	    if($uname) :
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
					return $post_id;
				endif;
			endif;
	 }
	 
	 public function login_user(){
	 	session_start();
		$name=$_GET['bfname'];
		$email=$_GET['bfemail'];

			$posts = get_posts(array(
				'fields' 			=> 'ids',
				'numberposts'	=> 1,
				'post_type'		=> 'bfuser',
				'meta_query'	=> array(
					'relation'		=> 'AND',
					array(
						'key'	 	=> 'name',
						'value'	  	=> $name,
						'compare' 	=> '=',
					),
					array(
						'key'	  	=> 'email',
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
	 	$userid=$_GET['uid'];
	 		$scoreid = get_posts(array(
				'fields' 			=> 'ids',
				'posts_per_page'	=> 1,
				'post_type'			=> 'bfscore',
				'meta_key'		=> 'bfuserID',
				'meta_value'	=> $userid,
				'compare' 	=> '='
			));
		if($userid):
			return ['score' => get_field('score',$scoreid[0]),'name' => get_field('name',$userid),'email' => get_field('email',$userid)];
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

	

	
	
	
	
}

?>