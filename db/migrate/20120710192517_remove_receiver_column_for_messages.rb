class RemoveReceiverColumnForMessages < ActiveRecord::Migration
  def change
    remove_column :messages, :receiver_id
  end
end
