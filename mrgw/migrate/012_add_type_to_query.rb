class AddTypeToQuery < ActiveRecord::Migration
  def self.up
    add_column :queries, :qtype, :string, :default => 'normal'
    
    add_index :queries, :qtype
  end

  def self.down
    remove_column :queries, :qtype
  end
end
