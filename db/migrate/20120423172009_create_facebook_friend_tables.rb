class CreateFacebookFriendTables < ActiveRecord::Migration
  def change
    create_table :facebook_friendships do |t|
      t.integer :user_id, :null => false
      t.integer :friend_id, :null => false
      t.string :status
      t.time :sent_at
      t.time :accepted_at
    end

    add_index :facebook_friendships, [:user_id, :friend_id]
    add_index :facebook_friendships, :status
  end
end
