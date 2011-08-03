<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Split_hometown_to_city_state extends Migration {
  
  public function up() {
    require APPPATH . 'objects/xobject.php';
    require APPPATH . 'objects/xcollege.php';
    require APPPATH . 'objects/xuser.php';
    
    $verbose = $this->migrations->is_verbose();
    
    $this->db->query('ALTER TABLE `users`
                      ADD COLUMN `hometown_city` VARCHAR(255) NOT NULL AFTER `hometown`');
    $this->db->query('ALTER TABLE `users`
                      ADD COLUMN `hometown_state` VARCHAR(255) NOT NULL AFTER `hometown_city`');
    
    $users = college()->load_objects('XUser', 'SELECT id FROM users');
    foreach ($users as $user) {
      $user->hometown_city = get_hometown_city($user->hometown);
      $user->hometown_state = get_hometown_state($user->hometown);
      $user->save();
    }
    
    $this->db->query('ALTER TABLE `users` DROP COLUMN `hometown`');
  }
  
  public function down() {
    require APPPATH . 'objects/xobject.php';
    require APPPATH . 'objects/xcollege.php';
    require APPPATH . 'objects/xuser.php';
    
    $this->db->query('ALTER TABLE `users`
                      ADD COLUMN `hometown` VARCHAR(255) NOT NULL AFTER `hometown_state`');
    
    $users = college()->load_objects('XUser', 'SELECT id FROM users');
    foreach ($users as $user) {
      $user->hometown = $user->hometown_city . ', ' . $user->hometown_state;
      $user->save();
    }
    
    $this->db->query('ALTER TABLE `users` DROP COLUMN `hometown_city`');
    $this->db->query('ALTER TABLE `users` DROP COLUMN `hometown_state`');
  }
  
}
