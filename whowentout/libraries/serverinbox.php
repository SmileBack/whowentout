<?php

/**
 * @return S3ServerInbox 
 */
function serverinbox() {
  static $inbox = NULL;
  if (!$inbox)
    $inbox = new FilesystemServerInbox();
  
  return $inbox;
}

function serverinbox_element($object, $id = NULL, $id2 = NULL) {
  $args = func_get_args();
  $class = $object;
  $name = implode('_', $args);
  return sprintf('<div class="%s server" url="%s"></div>', $class, $name);
}

class FilesystemServerInbox
{
  
  function __construct() {
    $this->folder = 'events';
  }
  
  public function push($id, $data) {
    $encoded_data = 'json(' . json_encode($data) . ')';
    file_put_contents("$this->folder/$id", $encoded_data);
  }
  
  public function delete($id) {
    unlink("$this->folder/$id");
  }
  
  public function url($id) {
    return "/$this->folder/$id";
  }
  
}

class S3ServerInbox
{
  
  private $bucket;
  private $amazon_public_key;
  private $amazon_secret_key;
  private $s3;
  
  function __construct() {
    $this->bucket = 'whowentoutevents';
    $this->amazon_public_key = ci()->config->item('amazon_public_key');
    $this->amazon_secret_key = ci()->config->item('amazon_secret_key');
  }
  
  public function push($id, $data) {
    $encoded_data = 'json(' . json_encode($data) . ')';
    
    $this->s3()->create_object($this->bucket, $id, array(
      'body' => $encoded_data,
      'contentType' => 'application/javascript',
      'acl' => AmazonS3::ACL_PUBLIC,
    ));
  }
  
  public function delete($id) {
    $this->s3()->delete_object($bucket, $id);
  }
  
  public function url($id) {
    return $this->s3()->get_object_url($this->bucket, $id);
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
