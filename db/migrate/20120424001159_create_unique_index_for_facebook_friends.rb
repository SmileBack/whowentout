class CreateUniqueIndexForFacebookFriends < ActiveRecord::Migration
  def change
    remove_index :facebook_friendships, [:user_id, :friend_id]
    add_index :facebook_friendships, [:user_id, :friend_id], :unique => true
  end
end
