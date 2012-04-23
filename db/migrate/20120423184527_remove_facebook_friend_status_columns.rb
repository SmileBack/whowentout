class RemoveFacebookFriendStatusColumns < ActiveRecord::Migration
  def change
    remove_column :facebook_friendships, :status
    remove_column :facebook_friendships, :sent_at
    remove_column :facebook_friendships, :accepted_at
  end
end
