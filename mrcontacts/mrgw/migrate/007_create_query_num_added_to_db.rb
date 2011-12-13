class CreateQueryNumAddedToDb < ActiveRecord::Migration
  def self.up
    add_column :queries, :num_added_to_db, :integer
  end
  def self.down
    remove_column :queries, :num_added_to_db
  end
end
