<?php

abstract class BaseImageRepository
{
  
  /**
   * @return WideImage
   */
  abstract function get($id, $preset);
  
  /**
   * @return string
   */
  abstract function path($id, $preset);
  function url($id, $preset) {
    return $this->path($id, $preset);
  }
  
  /**
   * @return bool
   */
  abstract function exists($id, $preset);
  
  abstract function delete($id, $preset);
  
  /**
   *
   * @param WideImage $img
   * @param int $id
   * @param string $preset 
   */
  abstract function saveImage($img, $id, $preset);
  
  function refresh($id, $preset) {
    $method = "refresh_$preset";
    $this->$method($id);
  }
  
  protected function refresh_facebook($id) {
    $user = user($id);
    
    if ( ! $user->facebook_id )
      return;
    
    $facebook_pic_url = "https://graph.facebook.com/$user->facebook_id/picture?type=large&access_token=" . fb()->getAccessToken();
    $img = WideImage::loadFromFile($facebook_pic_url);
    $this->saveImage($img, $id, 'facebook');
    
    $this->refresh($id, 'source');
    $this->refresh($id, 'normal');
    $this->refresh($id, 'thumb');
  }
  
  protected function refresh_upload($id) {
    $user = user($id);
    
    $file = $_FILES['upload_pic'];
    $filepath = $file['tmp_name'];
    $filename = $file['name'];
    
    if ( ! $this->is_valid_image($filepath) ) { //Invalid image
      return;
    }
    
    $img = WideImage::loadFromUpload('upload_pic');
    $this->saveImage($img, $id, 'upload');
    
    $this->refresh($id, 'source');
    $this->refresh($id, 'normal');
    $this->refresh($id, 'thumb');
  }
  
  function refresh_source($id) {
    if ( $this->exists($id, 'upload') ) {
      $img = $this->get($id, 'upload');
    }
    elseif ( $this->exists($id, 'facebook') ) {
      $img = $this->get($id, 'facebook');
    }
    else {
      $this->refresh($id, 'facebook');
      $img = $this->get($id, 'facebook');
    }
    $this->saveImage($img, $id, 'source');
    
    $this->set_default_crop_box($id);
  }
  
  protected function refresh_normal($id) {
    $user = user($id);
    $image_path = $this->path($id, 'source');
    $img = WideImage::load($image_path)
                    ->crop($user->pic_x, $user->pic_y, $user->pic_width, $user->pic_height)
                    ->resize(150, 200);
    $this->saveImage($img, $id, 'normal');
  }
  
  protected function refresh_thumb($id) {
    $user = user($id);
    $image_path = $this->path($id, 'source');
    $img = WideImage::load($image_path)
                    ->crop($user->pic_x, $user->pic_y, $user->pic_width, $user->pic_height)
                    ->resize(105, 140);
    $this->saveImage($img, $id, 'thumb');
  }
  
  function set_default_crop_box($id) {
    $user = user($id);
    
    $padding = 20;
    
    $img = $this->get($id, 'source');
    
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
  
  private function is_valid_image($filepath) {
    return getimagesize($filepath) !== FALSE;
  }
  
}

class S3ImageRepository extends BaseImageRepository
{
  
  private $bucket;
  private $amazon_public_key;
  private $amazon_secret_key;
  private $s3;
  
  function __construct($bucket) {
    $this->bucket = $bucket;
    $this->amazon_public_key = ci()->config->item('amazon_public_key');
    $this->amazon_secret_key = ci()->config->item('amazon_secret_key');
  }
  
  /**
   *
   * @param WideImage $img
   * @param int $id
   * @param string $preset 
   */
  function saveImage($img, $id, $preset) {
    $temp_image_path = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
    $img->saveToFile($temp_image_path);
    $filename = $this->filename($id, $preset);
    $response = $this->s3()->create_object($this->bucket, $filename, array(
      'fileUpload' => $temp_image_path, 
      'acl' => AmazonS3::ACL_PUBLIC,
    ));
    
    $user = user($id);
    $user->pic_version++;
    $user->save();
  }
  
  /**
   * @return WideImage
   */
  function get($id, $preset) {
    if ( ! $this->exists($id, $preset) )
      return NULL;
    
    $image_path = $this->path($id, $preset);
    return WideImage::loadFromFile($image_path);
  }
  
  function path($id, $preset) {
    if ( ! $this->exists($id, $preset) ) {
      $this->refresh($id, $preset);
    }
    $filename = $this->filename($id, $preset);
    return $this->s3()->get_object_url($this->bucket, $filename);
  }
  
  function url($id, $preset) {
    $user = user($id);
    return $this->path($id, $preset) . "?version=$user->pic_version";
  }
  
  function exists($id, $preset) {
    $filename = $this->filename($id, $preset);
    return $this->s3()->if_object_exists($this->bucket, $filename);
  }
  
  function delete($id, $preset) {
    $filename = $this->filename($id, $preset);
    $this->s3()->delete_object($this->bucket, $filename);
  }
  
  function filename($id, $preset) {
    return "$id.$preset.jpg";
  }
  
  /**
   * @return AmazonS3
   */
  function s3() {
    if ($this->s3 == NULL) {
      $this->s3 = new AmazonS3($this->amazon_public_key, $this->amazon_secret_key);
      $this->s3()->use_ssl = false;
    }
    return $this->s3;
  }
  
}

class FilesystemImageRepository extends BaseImageRepository
{
  
  private $path;
  
  function __construct($path) {
    $this->path = $path;
    $this->check_path();
  }
  
  function saveImage($img, $id, $preset) {
    $this->create_preset($preset);
    $img->saveToFile( $this->filename($id, $preset) );
    
    $user = user($id);
    $user->pic_version++;
    $user->save();
  }
  
  protected function create_preset($preset) {
    if ( ! file_exists("$this->path/$preset") ) {
      mkdir("$this->path/$preset");
    }
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
    return $this->filename($id, $preset);
  }
  
  function exists($id, $preset) {
    return file_exists( $this->filename($id, $preset) );
  }
  
  function delete($id, $preset) {
    $filename = $this->filename($id, $preset);
    unlink($filename);
  }
  
  protected function filename($id, $preset) {
    return "$this->path/$preset/$id.jpg";
  }
  
  protected function check_path() {
    if ( ! file_exists($this->path) )
      throw new Exception("The path $this->path doesn't exist");
  }
  
}
