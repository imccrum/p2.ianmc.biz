<?php
class posts_controller extends base_controller {

  public function __construct() {

    parent::__construct();

  } 

  public function add($error = NULL) {

    # must be logged in

    if (!$this->user) {

      Router::redirect("/");

    }

    $this->template->content = View::instance("v_posts_add");

    $this->template->title = "QUACK QUACK | POST";

    if (isset($error)) {

     $this->template->content->error = $error;

    }

    echo $this->template;
  
  }


  public function p_add() {

    # must be logged in

    if (!$this->user) {

    Router::redirect("/");

    }  

    # trim content to establish if really empty

    $check = ltrim($_POST["content"]);

    # 2nd condition allows single 0s

    if (empty($check) && $check !== "0") {

      # return error message in url

      Router::redirect("/posts/add/error");

    }

    # check for html tags

    if($_POST["content"] != strip_tags($_POST["content"])) {

      # return error message in url

      Router::redirect("/posts/add/html");
      
    }

    $_POST["user_id"] = $this->user->user_id;
    $_POST["created"] = Time::now();
    $_POST["modified"] = Time::now();

    DB::instance(DB_NAME)->insert("posts", $_POST);

    # return to profile with success message

    Router::redirect("/users/profile/post_successful");

  }


  public function index($message = NULL) {

  # stop if not logged in

    if (!$this->user) {

      Router::redirect("/");

    }

  # if logged in send to profile

    else {

      Router::redirect("/users/profile");

    }

  }

  public function users() {

  # must be logged in

    if (!$this->user) {

      Router::redirect("/");

    }

    $this->template->content = View::instance("v_posts_users");

    $this->template->title = "QUACK QUACK | FOLLOW";

  # query return all users in alphabetical order 

    $q = "SELECT
    users.user_id,
    users.first_name,
    users.last_name
    FROM users
    WHERE user_id <>'" .$this->user->user_id. "' 
    ORDER BY first_name";

  # data already sanitized

    $users = DB::instance(DB_NAME)->select_rows($q);

  # query for relationshiips

    $q = "SELECT * 
    FROM users_users
    WHERE user_id = ".$this->user->user_id;

  # data already sanitized

    $connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

    $this->template->content->users       = $users;
    $this->template->content->connections = $connections;

    echo $this->template;

  }

  public function follow($user_id_followed) {

  # must be logged in

    if (!$this->user) {

      Router::redirect("/");

    }

# prepare array

    $data = Array(
      "created" => Time::now(),
      "user_id" => $this->user->user_id,
      "user_id_followed" => $user_id_followed
      );

# insert

    DB::instance(DB_NAME)->insert('users_users', $data);

# back to user list

    Router::redirect("/posts/users");

  }

  public function unfollow($user_id_followed) {

  # must be logged in

    if (!$this->user) {

      Router::redirect("/");

    }

# Delete this connection

    $where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;

  # not user input

    DB::instance(DB_NAME)->delete('users_users', $where_condition);

  # Send them back

    Router::redirect("/posts/users");

  }

  public function myposts($option = NULL) {

  # must be logged in 

    if (!$this->user)  {

      Router::redirect("/");

    }

  # only allow access through delete or edit functions

    if ($option != "delete" && $option != "edit") {

      Router::redirect("/users/profile");

    }

    $this->template->content = View::instance("v_posts_myposts");

    $this->template->content->option = $option;

    $this->template->title = "QUACK QUACK | MY POSTS";

  # query returns all posts my the user (and time, name, etc) chronological order

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

  # already sanitized

    $posts = DB::instance(DB_NAME)->select_rows($q);

    foreach ($posts as &$post) {

    # convert timestamp

      $post["created"] = date("j M Y, g:i a", $post["created"]);

    }

    $this->template->content->posts = $posts;

    echo $this->template;

  }


  public function edit($param = NULL) {

   if (!$this->user) {

    Router::redirect("/");

  }

  $this->template->content = View::instance("v_posts_edit");

  $this->template->title = "QUACK QUACK | EDIT";

  # to separate params in the url => post_id | error message

  $params = explode("&", $param);

  if(isset($params[1])) {

    $this->template->content->error = $params[1];

  }

  $q = "SELECT
  posts.content,
  posts.post_id,
  posts.user_id
  FROM posts
  WHERE posts.post_id = '" .$params[0]. "' AND posts.user_id = '" .$this->user->user_id. "'";

  $_POST = DB::instance(DB_NAME)->sanitize($q);

  $posts = DB::instance(DB_NAME)->select_rows($q);

  # to stop rogue editing when there are no posts

  if (!isset($posts[0])) {

    Router::redirect("/posts/myposts/edit");

  }

  $this->template->content->posts = $posts;

  echo $this->template;

  }

  public function p_edit() {

    # trim input to make sure really empty

    $check = ltrim($_POST["content"]);

    if (empty($check) && $check !== "0") {

      # if empty return to edit with post_id and error condition in url

      Router::redirect("/posts/edit/".$_POST[post_id]."&error");

    }

    # check for html and return error message

    if($_POST["content"] != strip_tags($_POST["content"])) {

      Router::redirect("/posts/edit/".$_POST[post_id]."&html");
      
    }

    # update modified time

    $_POST["modified"] = Time::now();

    # update entry provided it matches the post_id AND the user_id 

    $q = "UPDATE 
    posts 
    SET content = '" .$_POST["content"]. "'
    WHERE post_id = '" .$_POST["post_id"]. "' AND user_id = '" .$this->user->user_id. "'";

    DB::instance(DB_NAME)->query($q);

    Router::redirect("/users/profile/edit_successful");

  }

  public function delete($param = NULL) {

    # must be logged in 

    if (!$this->user) {

      Router::redirect("/");

    }

    $this->template->content = View::instance("v_posts_delete");

    $this->template->title = "QUACK QUACK | DELETE";

    # run query to confirm message for delete. Ensure matches post_id and user_id

    $q = "SELECT
    posts.content, 
    posts.created,
    posts.post_id,
    posts.user_id,
    users.first_name, 
    users.last_name
    FROM posts
    INNER JOIN users ON posts.user_id = users.user_id
    WHERE posts.post_id = '" .$param. "' AND posts.user_id = '" .$this->user->user_id. "'";

    $_POST = DB::instance(DB_NAME)->sanitize($q);

    $posts = DB::instance(DB_NAME)->select_rows($q);

    # to stop rogue editing when there are no posts

    if (!isset($posts["0"])) {

      Router::redirect("/posts/myposts/delete");

    }

    # convert timestamp

    $posts["0"]["created"] = date("j M Y, g:i a", $posts["0"]["created"]);

    $this->template->content->posts = $posts;

    echo $this->template;

  }

  public function p_delete() {

    # delete provided post_id and user_id

    $q = "DELETE 
    FROM posts
    WHERE posts.post_id = '" .$_POST["post_id"]. "' AND posts.user_id = '" .$this->user->user_id. "'";

    $_POST = DB::instance(DB_NAME)->sanitize($q);

    DB::instance(DB_NAME)->query($q);

    Router::redirect("/users/profile/delete_successful");

  }

}
