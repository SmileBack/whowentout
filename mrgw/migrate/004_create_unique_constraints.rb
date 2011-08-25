class CreateUniqueConstraints < ActiveRecord::Migration
  def self.up
    add_index :students, :email, :unique => true
    add_index :queries, :value, :unique => true
  end
  def self.down
    remove_index :students, :email
    remove_index :queries, :value
  end
end