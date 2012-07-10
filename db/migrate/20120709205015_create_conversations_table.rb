class CreateConversationsTable < ActiveRecord::Migration

  def change
    create_table :conversations do |t|
      t.integer "latest_message_id"
      t.string "users_hash"

      t.timestamps
    end
    add_index :conversations, :users_hash

    create_table :user_conversations do |t|
      t.integer  "user_id"
      t.integer  "conversation_id"

      t.timestamps
    end
  end

end
