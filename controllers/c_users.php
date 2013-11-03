<?php
class users_controller extends base_controller {

  public function __construct() {
    parent::__construct();

  } 

  public function index() {

    if (!$this->user) {

      Router::redirect("/");

    }

    else {

      Router::redirect("/users/profile");

    }

  }

  public function signup($error = NULL) {


    if ($this->user) {

      Router::redirect("/users/profile");
    }

    $this->template->content = View::instance("v_users_signup");       

    $client_files_head = Array("/css/main.css", "/js/jstz.min.js", "/js/jquery-1.10.1.min.js");

    $this->template->content->error = $error;

    $this->template->client_files_head = Utils::load_client_files($client_files_head);

    echo $this->template;

  }


  public function p_signup() {

    if (empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["email"]) || empty($_POST["password"])) {

      Router::redirect("/users/signup/");

    }

    $q = "SELECT email FROM users WHERE email = '" .$_POST["email"]. "'";

    $existing_email = DB::instance(DB_NAME)->select_rows($q);

    if ($existing_email) {

      Router::redirect("/users/signup/exists");

    }

    $client_files_head = Array("/css/main.css", "/js/jstz.min.js", "/js/jquery-1.10.1.min.js");

    $this->template->client_files_head = Utils::load_client_files($client_files_head);

    $_POST["created"]  = Time::now();

    $_POST["modified"] = Time::now();

    $_POST["password"] = sha1(PASSWORD_SALT.$_POST["password"]);            

    $_POST["token"] = sha1(TOKEN_SALT.$_POST["email"].Utils::generate_random_string()); 

    $user_id = DB::instance(DB_NAME)->insert("users", $_POST);

       # Build a multi-dimension array of recipients of this email
    $to[] = Array("name" => "Judy Grimes", "email" => "ianmccrum@gmail.com");

# Build a single-dimension array of who this email is coming from
# note it's using the constants we set in the configuration above)
    $from = Array("name" => APP_NAME, "email" => APP_EMAIL);

# Subject
    $subject = "Welcome to Quack Quack!";

# You can set the body as just a string of text
    $body = "Hi Judy, this is just a message to confirm your registration at JavaBeans.com";

# OR, if your email is complex and involves HTML/CSS, you can build the body via a View just like we do in our controllers
# $body = View::instance('e_users_welcome');

    $cc  = "";
    $bcc = "";

    $email = Email::send($to, $from, $subject, $body, true, $cc, $bcc);

    Router::redirect("/users/success");

  }

  public function success() {

      $this->template->content = View::instance('v_index_index');
      
  $this->template->title = "QUACK QUACK | PROFILE";

    $client_files_head = Array("/css/main.css");

    $this->template->client_files_head = Utils::load_client_files($client_files_head);

    $this->template->content = View::instance("v_users_success");
  
      echo $this->template;



  }


  public function login($error = NULL) {

   $this->template->content = View::instance("v_users_login");

   $client_files_head = Array("/css/main.css");

   $this->template->content->error = $error;

   $this->template->client_files_head = Utils::load_client_files($client_files_head);

   echo $this->template;


 }

 public function p_login() {

   $_POST = DB::instance(DB_NAME)->sanitize($_POST);

   $_POST["password"] = sha1(PASSWORD_SALT.$_POST["password"]);  

   $q = "SELECT token
   FROM users
   WHERE email = '".$_POST['email']."'
   AND password = '".$_POST['password']."'";

   $token = DB::instance(DB_NAME)->select_field($q);

   if ($token) {

    setcookie("token", $token, strtotime("+1 month"), "/");
    Router::redirect("/users/profile/");

  } else {

    $q = "SELECT email FROM users WHERE email = '" .$_POST["email"]. "'";

    $existing_email = DB::instance(DB_NAME)->select_rows($q);

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

  $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());

  $data = Array("token" => $new_token);

  DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");

  setcookie("token", "", strtotime("-1 year"), "/");

  Router::redirect("/");
}


public function profile() {

  if (!$this->user) {

    Router::redirect("/");

  }

  $this->template->content = View::instance('v_users_profile');

  $this->template->title = "QUACK QUACK | PROFILE";

  $this->template->content->user_name = $this->user->first_name;

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

  $posts = DB::instance(DB_NAME)->select_rows($q);


  foreach ($posts as &$post) {


    $post["created"] = Time::display($post["created"], "j M Y, g:i a", $post["timezone"]);


  }

  $this->template->content->posts = $posts;



   // $this->template->content->user_name = $user_name;


  $client_files_head = Array("/css/main.css");

  $this->template->client_files_head = Utils::load_client_files($client_files_head);

  echo $this->template;

}




} # end of the class
