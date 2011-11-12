<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Profiler extends CI_Profiler {

	protected function _compile_controller_info()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_controller_info" style="border:1px solid #995300;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
		$output .= "\n";
		$output .= '<legend style="color:#995300;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_controller_info').'&nbsp;&nbsp;</legend>';
		$output .= "\n";

		$output .= "<div style='color:#995300;font-weight:normal;padding:4px 0 4px 0'>".$this->CI->router->get_class()."/".$this->CI->router->get_method()."</div>";

		$output .= "</fieldset>";

		return $output;
	}


}
