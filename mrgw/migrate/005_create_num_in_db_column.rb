class CreateNumInDbColumn < ActiveRecord::Migration
  def self.up
    add_column :queries, :num_in_db, :integer
  end
  def self.down
    remove_column :queries
  end
end