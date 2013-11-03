<?php
class posts_controller extends base_controller {

	public function __construct() {
        parent::__construct();
        
    } 

	public function add($error = NULL) {

	if (!$this->user) {

            Router::redirect("/");
            
    }
    	$this->template->content = View::instance("v_posts_add");

    if (isset($error)) {

    	$this->template->content->error = $error;

    }

        $client_files_head = Array("/css/main.css");

        $this->template->client_files_head = Utils::load_client_files($client_files_head);
		
		$this->template->title = "New Post";

		echo $this->template;
	}

	public function p_add() {

	if (empty($_POST["content"])) {

            Router::redirect("/posts/add/error");
    
    }

		$_POST["user_id"] = $this->user->user_id;
		$_POST["created"] = Time::now();
		$_POST["modified"] = Time::now();

		DB::instance(DB_NAME)->insert("posts", $_POST);

		Router::redirect("/users/profile");

	}

	public function index() {

	if (!$this->user) {

            Router::redirect("/");
            
    }

  		$this->template->content = View::instance("v_posts_index");


		$q = "SELECT
			posts.content, 
			posts.created, 
			posts.user_id AS post_user_id, 
			users_users.user_id AS follower_id, 
			users.first_name, 
			users.last_name
			FROM posts
			INNER JOIN users_users ON posts.user_id = users_users.user_id_followed
			INNER JOIN users ON posts.user_id = users.user_id
			WHERE users_users.user_id = '" .$this->user->user_id. "'
			ORDER BY posts.created DESC 
			LIMIT 0 , 10";
		
		$posts = DB::instance(DB_NAME)->select_rows($q);

		

		foreach ($posts as &$post) {

			
			$post["created"] = date("j M Y, g:i a", $post["created"]);

		}


	//	$posts['first_name'] = "23";//time($post["created"], strtotime($date));

	

		$client_files_head = Array("/css/main.css");

    	$this->template->client_files_head = Utils::load_client_files($client_files_head);

		$this->template->content->posts = $posts;

		echo $this->template;


	}


public function users() {

	if (!$this->user) {

            Router::redirect("/");

    }

    	$client_files_head = Array("/css/main.css");

    	$this->template->client_files_head = Utils::load_client_files($client_files_head);

    
    $this->template->content = View::instance("v_posts_users");
    $this->template->title   = "Users";

    $q = "SELECT
    		users.user_id,
    		users.first_name,
    		users.last_name
        FROM users
        ORDER BY first_name";

    $users = DB::instance(DB_NAME)->select_rows($q);

    $q = "SELECT * 
        FROM users_users
        WHERE user_id = ".$this->user->user_id;

    # Execute this query with the select_array method
    # select_array will return our results in an array and use the "users_id_followed" field as the index.
    # This will come in handy when we get to the view
    # Store our results (an array) in the variable $connections
    $connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

    $this->template->content->users       = $users;
    $this->template->content->connections = $connections;

    echo $this->template;
}

public function follow($user_id_followed) {

	if (!$this->user) {

            Router::redirect("/");
           

    }

    # Prepare the data array to be inserted
    $data = Array(
        "created" => Time::now(),
        "user_id" => $this->user->user_id,
        "user_id_followed" => $user_id_followed
        );

    # Do the insert
    DB::instance(DB_NAME)->insert('users_users', $data);

    # Send them back
    Router::redirect("/posts/users");

}

public function unfollow($user_id_followed) {

	if (!$this->user) {

            Router::redirect("/");
       

    }

    # Delete this connection
    $where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;
    DB::instance(DB_NAME)->delete('users_users', $where_condition);

    # Send them back
    Router::redirect("/posts/users");

}

public function edit() {

	if (!$this->user) {

            Router::redirect("/");
       
    }

    $this->template->content = View::instance("v_posts_edit");

    $client_files_head = Array("/css/main.css");

    $this->template->client_files_head = Utils::load_client_files($client_files_head);

    		$q = "SELECT
			posts.content, 
			posts.created,
			posts.post_id, 
			posts.user_id,
			users.first_name, 
			users.last_name
			FROM posts
			INNER JOIN users ON posts.user_id = users.user_id
			WHERE posts.user_id = '" .$this->user->user_id. "'
			ORDER BY posts.created DESC";
					
		$posts = DB::instance(DB_NAME)->select_rows($q);

		foreach ($posts as &$post) {
			
			$post["created"] = date("j M Y, g:i a", $post["created"]);

		}

		$this->template->content->posts = $posts;

		echo $this->template;

	}

	public function editpost($message = NULL) {

		if (!$this->user) {

        Router::redirect("/");
       
    }

    $this->template->content = View::instance("v_posts_editpost");

    $client_files_head = Array("/css/main.css");

    $this->template->client_files_head = Utils::load_client_files($client_files_head);

    $messages = explode("&", $message);

    if(isset($messages[1])) {

    $this->template->content->error = $messages[1];

    }

    $q = "SELECT
			posts.content,
			posts.post_id
			FROM posts
			WHERE posts.post_id = '" .$messages[0]. "'";
					
		$posts = DB::instance(DB_NAME)->select_rows($q);

		$this->template->content->posts = $posts;

    echo $this->template;


	}

	public function p_editpost() {

	if (empty($_POST["content"])) {

            Router::redirect("/posts/editpost/".$_POST[post_id]."&error");
    
    }

		$_POST["modified"] = Time::now();

		$q = "UPDATE 
		     	posts 
		     SET content='" .$_POST["content"]. "'
             WHERE post_id= '" .$_POST["post_id"]. "'";

    DB::instance(DB_NAME)->query($q);

    Router::redirect("/users/profile");

	}

}
