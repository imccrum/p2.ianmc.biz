<?php

class index_controller extends base_controller {
	
	/*-------------------------------------------------------------------------------------------------

	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
		
		parent::__construct();
	
	} 

	/*-------------------------------------------------------------------------------------------------
	Accessed via http://localhost/index/index/
	-------------------------------------------------------------------------------------------------*/
	public function index() {

		# If logged in go straight to profile

		if ($this->user) {

			Router::redirect("/users/profile");

		}
		
		# Any method that loads a view will commonly start with this
		# First, set the content of the template with a view file
		$this->template->content = View::instance('v_index_index');

		# Now set the <title> tag
		$this->template->title = "QUACK QUACK";

		$this->template->content = View::instance("v_index_index");

		# CSS/JS includes

		# Render the view
	    	
	    echo $this->template;

	} # End of method
	
	
} # End of class
