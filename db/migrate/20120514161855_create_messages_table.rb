class CreateMessagesTable < ActiveRecord::Migration
  def change
    create_table :messages do |t|
      t.integer :sender_id, :null => false
      t.integer :receiver_id, :null => false
      t.string :status

      t.text :body

      t.timestamps
    end

    add_index :messages, [:sender_id, :receiver_id]
    add_index :messages, :status
  end
end
