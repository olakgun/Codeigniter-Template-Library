<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
        parent::__construct();

        // assets/admin folder
        $this->template->platform('admin');
        // assets/admin/default folder
        $this->template->theme('default');
    }

    public function index()
	{
	    // browser title
	    $this->template->title('Merhaba');
	    // style file1 for this method
	    $this->template->css('style1.css');
	    // style file2 for this method
	    $this->template->css('style2.css');
	    // view file name. application/views/.../.../welcome_message.php file
	    $this->template->layout('welcome_message');
	    // variables
        $data = [ 'person' => 'olcay akgun' ];
	    // output
        $this->template->render($data);
	}
}
