<?php

class ImageRepository
{
  
  private $path;
  
  function __construct($path) {
    $this->path = $path;
    $this->check_path();
  }
  
  function refresh($id, $preset) {
    $method = "refresh_$preset";
    $this->$method($id);
  }
  
  /**
   * @return WideImage
   */
  function get($id, $preset) {
    $image_path = $this->path($id, $preset);
    
    if ($image_path == NULL)
      return NULL;
    
    return WideImage::load($image_path);
  }
  
  function path($id, $preset) {
    if ( ! $this->exists($id, $preset) ) {
      $this->refresh($id, $preset);
    }
    return "$this->path/$preset/$id.jpg";
  }
  
  function exists($id, $preset) {
    return file_exists("$this->path/$preset/$id.jpg");
  }
  
  protected function refresh_facebook($id) {
    $user = user($id);
    
    if ( ! $user->facebook_id )
      return;
    
    $facebook_pic_url = "https://graph.facebook.com/$user->facebook_id/picture?type=large&access_token=" . fb()->getAccessToken();
    $this->download('facebook', $user->id, $facebook_pic_url);
    $this->set_default_crop_box($id);
    
    $this->refresh_normal($id);
    $this->refresh_thumb($id);
  }
  
  protected function refresh_normal($id) {
    $user = user($id);
    $facebook_image_path = $this->path($id, 'facebook');
    $img = WideImage::load($facebook_image_path)
                    ->crop($user->pic_x, $user->pic_y, $user->pic_width, $user->pic_height)
                    ->resize(150, 200);
    $this->saveImage($img, $id, 'normal');
  }
  
  protected function refresh_thumb($id) {
    $user = user($id);
    $facebook_image_path = $this->path($id, 'facebook');
    $img = WideImage::load($facebook_image_path)
                    ->crop($user->pic_x, $user->pic_y, $user->pic_width, $user->pic_height)
                    ->resize(105, 140);
    $this->saveImage($img, $id, 'thumb');
  }
  
  protected function saveImage($img, $id, $preset) {
    $this->create_preset($preset);
    $img->saveToFile("$this->path/$preset/$id.jpg");
  }
  
  protected function download($preset, $id, $url) {
    $img = WideImage::loadFromFile($url);
    $this->saveImage($img, $id, $preset);
  }
  
  protected function create_preset($preset) {
    if ( ! file_exists("$this->path/$preset")) {
      mkdir("$this->path/$preset");
    }
  }
  
  protected function check_path() {
    if ( ! file_exists($this->path) )
      throw new Exception("The path $this->path doesn't exist");
  }
  
  
  function set_default_crop_box($id) {
    $user = user($id);
    
    $padding = 20;
    
    $img = $this->get($id, 'facebook');
    $img_width = $img->getWidth();
    $img_height = $img->getHeight();
    
    if ($img_width / $img_height > 3 / 4) {
      $user->pic_height = $img_height - $padding * 2;
      $user->pic_width = $user->pic_height * (3 / 4);
      
      $user->pic_x = $img_width / 2 - $user->pic_width / 2;
      $user->pic_y = $img_height / 2 - $user->pic_height / 2;
      $user->save();
    }
    else {
      $user->pic_x = $padding;
      $user->pic_y = $padding;
      $user->pic_width = $img_width - $padding * 2;
      $user->pic_height = $user->pic_width * (4 / 3);
      $user->save();
    }
  }
  
}
