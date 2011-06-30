<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   * 		http://example.com/index.php/welcome
   *	- or -  
   * 		http://example.com/index.php/welcome/index
   *	- or -
   * Since this controller is set as the default controller in 
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index()
  {
    $parties = $this->college_model->get_open_parties(1, today(TRUE));
    var_dump($parties);
    //var_dump($parties);
    //print parties_dropdown(1, new DateTime('2011-05-27', get_college_timezone()));
    //$time = get_closing_time();
    //print make_local($time)->format(DATE_RFC2822);
//    $now_local = current_time(TRUE);
//    $today_local = today(TRUE);
//    $yesterday_local = yesterday(TRUE);
//    
//    $now = current_time();
//    $today = today();
//    $yesterday = yesterday();
//    
//    print $now_local->format(DATE_RFC2822); print '<br>';
//    print $today_local->format(DATE_RFC2822); print '<br>';
//    print $yesterday_local->format(DATE_RFC2822); print '<br>';
//    
//    print $now->format(DATE_RFC2822); print '<br>';
//    print $today->format(DATE_RFC2822); print '<br>';
//    print $yesterday->format(DATE_RFC2822); print '<br>';
    
  }
  
}
