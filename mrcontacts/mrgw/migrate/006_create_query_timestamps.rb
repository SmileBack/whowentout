class CreateQueryTimestamps < ActiveRecord::Migration
  def self.up
    add_column :queries, :created_at, :datetime
    add_column :queries, :updated_at, :datetime
  end
  def self.dow
    remove_column :queries, :created_at
    remove_column :queries, :updated_at
  end
end