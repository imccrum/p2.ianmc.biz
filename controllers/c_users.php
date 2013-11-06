<?php

class users_controller extends base_controller {


  public function __construct() {

    parent::__construct();

  } 

  
  public function index() {

    # stopped if not logged in 

    if (!$this->user) {

      Router::redirect("/");

    }

    else {

      # if logged in go straight to profile

      Router::redirect("/users/profile");

    }

  }

  
  public function signup($error = NULL) {

    # if logged in go straight to profile

    if ($this->user) {

      Router::redirect("/users/profile");

    }

    $this->template->content = View::instance("v_users_signup");  

    $this->template->title = "QUACK QUACK | SIGNUP";     

    $this->template->content->error = $error;

    # load javascript files to calculate the users timezone on signup

    $client_files_head = Array("/js/jstz.min.js", "/js/jquery-1.10.1.min.js");

    # if errors are returned by p_signup include them in the view 
    
    $this->template->client_files_head = Utils::load_client_files($client_files_head);

    echo $this->template;

  }


  public function p_signup() {

    # if logged in go straight to profile

    if ($this->user) {

      Router::redirect("/users/profile");

    }

    # parse input for errors

    foreach ($_POST as $key => $value) {

      if ($key == "password") {

        # don't change real password

        $test = ltrim($_POST[$key]);

        if (empty($test)) {

          # append error condition to address to be displayed in signup

          Router::redirect("/users/signup/".$key);

        }

      }

      else {

        $_POST[$key] = ltrim($_POST[$key]);

      # check if any fields were empty

        if (empty($_POST[$key])) {

        # to remove "_" for display

          $message = str_replace("_", " ", $key);

        # append error condition to address to be displayed in signup

          Router::redirect("/users/signup/".$message);

              # check for html input

          if($_POST[$key] != strip_tags($_POST[$key])) {

        # append error condition to address to be displayed in signup

            Router::redirect("/users/signup/html");

          }

        }

      }

    }

    # verify that email address does not already exist in users table

    $q = "SELECT email FROM users WHERE email = '" .$_POST["email"]. "'";

    $existing_email = DB::instance(DB_NAME)->select_rows($q);

    if ($existing_email) {

      Router::redirect("/users/signup/exists");

    }

    # javascript necessary for timezone check

    $client_files_head = Array("/js/jstz.min.js", "/js/jquery-1.10.1.min.js");

    $this->template->client_files_head = Utils::load_client_files($client_files_head);

    # add fields for users table

    $_POST["created"]  = Time::now();
    $_POST["modified"] = Time::now();
    $_POST["password"] = sha1(PASSWORD_SALT.$_POST["password"]);            
    $_POST["token"] = sha1(TOKEN_SALT.$_POST["email"].Utils::generate_random_string()); 

    # insert (+ sanitize)

    $new_user_id = DB::instance(DB_NAME)->insert_row("users", $_POST);

    if($new_user_id) {

      # log in on signup

      setcookie('token',$_POST['token'], strtotime('+1 year'), '/');

      # the user must follow himself!

      $data = Array(
        "created" => Time::now(),
        "user_id" => $new_user_id,
        "user_id_followed" => $new_user_id
        );

      # add relationship to users_users

      DB::instance(DB_NAME)->insert('users_users', $data);

    }

    # move to success splash page

    Router::redirect("/users/success");

  }

  
  public function success() {

    # must be logged in

    if (!$this->user) {

      Router::redirect("/");

    }

    # success! and give info

    $this->template->content = View::instance('v_index_index');

    $this->template->title = "QUACK QUACK | SUCCESS";

    $this->template->content = View::instance("v_users_success");

    echo $this->template;

  }

  
  public function login($error = NULL) {

    # if already logged in send to profile

    if ($this->user) {

      Router::redirect("/users/profile");

    }

    $this->template->content = View::instance("v_users_login");

    $this->template->title = "QUACK QUACK | LOGIN";

    # if any error messages are passed in the url send them to the display

    $this->template->content->error = $error;

    echo $this->template;

  }

  
  public function p_login() {

    if ($this->user) {

      Router::redirect("/users/profile");

    }

    # sanitize pre query

    $_POST = DB::instance(DB_NAME)->sanitize($_POST);

    # use same salt and sha1 to encrypt password

    $_POST["password"] = sha1(PASSWORD_SALT.$_POST["password"]);  

    $q = "SELECT token
    FROM users
    WHERE email = '".$_POST['email']."'
    AND password = '".$_POST['password']."'";

    # check to see if user entered fields match db

    $token = DB::instance(DB_NAME)->select_field($q);

    if ($token) {

      # login and go to profile

      setcookie("token", $token, strtotime("+1 month"), "/");
      
      Router::redirect("/users/profile/");

    } 

    else {

      # recheck sanitized data to get error condition

      $q = "SELECT email FROM users WHERE email = '" .$_POST["email"]. "'";

      $existing_email = DB::instance(DB_NAME)->select_rows($q);

      # back to login with error condition

      if (!$existing_email) {

        Router::redirect("/users/login/email");

      } else { 

        Router::redirect("/users/login/password");

      }

    }

  }


  public function logout() {

    if (!$this->user) {

      Router::redirect("/");

    }

    # generate new token for next login

    $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());

    $data = Array("token" => $new_token);

    DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");

    setcookie("token", "", strtotime("-1 year"), "/");

    Router::redirect("/");

  }


  public function profile($message = NULL) {

    # must be logged in

    if (!$this->user) {

      Router::redirect("/");

    }

    $this->template->content = View::instance('v_users_profile');

    $this->template->title = "QUACK QUACK | PROFILE";

    if (isset($message)) {

      # send any error messages to the view

      $this->template->content->message = $message;

    }

    # to identify user in the profile

    $this->template->content->user_name = $this->user->first_name;

    # query to get the posts of followed users and their name and display in chronological order

    $q = "SELECT
    posts.content, 
    posts.created, 
    posts.user_id AS post_user_id, 
    users_users.user_id AS follower_id, 
    users.first_name, 
    users.last_name,
    users.timezone
    FROM posts
    INNER JOIN users_users ON posts.user_id = users_users.user_id_followed
    INNER JOIN users ON posts.user_id = users.user_id
    WHERE users_users.user_id = '" .$this->user->user_id. "'
    ORDER BY posts.created DESC";

    $_POST = DB::instance(DB_NAME)->sanitize($q);

    $posts = DB::instance(DB_NAME)->select_rows($q);

    foreach ($posts as &$post) {

      # convert unix timestamp into readible string

      $post["created"] = Time::display($post["created"], "j M Y, g:i a", $post["timezone"]);

    }

    $this->template->content->posts = $posts;

    echo $this->template;

  }

} 
