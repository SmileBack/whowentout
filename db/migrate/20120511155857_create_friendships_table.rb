class CreateFriendshipsTable < ActiveRecord::Migration
  def change
    create_table :friendships do |t|
      t.string :status
      t.integer :user_id
      t.integer :friend_id

      t.timestamps
    end

    add_index :friendships, :status
    add_index :friendships, [:user_id, :friend_id]
  end
end
