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
    $user = XUser::get($id);
    $facebook_pic_url = "https://graph.facebook.com/$user->facebook_id/picture?type=large&access_token=" . fb()->getAccessToken();
    $this->download('facebook', $user->id, $facebook_pic_url);
  }
  
  protected function refresh_normal($id) {
    $user = XUser::get($id);
    $facebook_image_path = $this->path($id, 'facebook');
    $img = WideImage::load($facebook_image_path)
                    ->crop($user->pic_x, $user->pic_y, $user->pic_width, $user->pic_height)
                    ->resize(150, 200);
    $this->saveImage($img, $id, 'normal');
  }
  
  protected function refresh_thumb($id) {
    $facebook_image_path = $this->path($id, 'facebook');
    $img = WideImage::loadFromFile($facebook_image_path)
                    ->resize(105, 140)
                    ->resizeCanvas(105, 140, 'center', 'center', '000000', 'up');
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
  
}
