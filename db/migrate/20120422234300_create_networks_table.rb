class CreateNetworksTable < ActiveRecord::Migration
  def change
    create_table :networks do |t|
      t.integer :id
      t.integer :facebook_id, :limit => 8
      t.string :type, :null => false
      t.string :name, :null => false
    end

    add_index :networks, :type
    add_index :networks, :facebook_id, :unique => true

    create_table :network_memberships do |t|
      t.integer :user_id
      t.integer :network_id
    end

    add_index :network_memberships, [:user_id, :network_id], :unique => true
  end
end
