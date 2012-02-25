class CreateNetworkColumn < ActiveRecord::Migration
  def self.up
    add_column :students, :network, :string

    add_index :students, :network
  end

  def self.down
    remove_column :students, :network
  end
end