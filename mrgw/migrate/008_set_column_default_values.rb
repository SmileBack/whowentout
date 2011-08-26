class SetColumnDefaultValues < ActiveRecord::Migration
  def self.up
    change_nulls_to_zeros(:queries, :num_in_db)
    change_nulls_to_zeros(:queries, :num_added_to_db)
    
    change_column :queries, :num_in_db, :integer, :default => 0, :null => false
    change_column :queries, :num_added_to_db, :integer, :default => 0, :null => false
  end
  
  def self.down
    change_column :queries, :num_in_db, :integer, :null => true
    change_column :queries, :num_added_to_db, :integer, :null => true
  end
  
  def self.change_nulls_to_zeros(table, column)
    ActiveRecord::Base.connection.execute("UPDATE #{table} SET #{column} = 0 WHERE #{column} IS NULL")
  end
  
end

