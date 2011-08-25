class CreateQueryStudentsTable < ActiveRecord::Migration
  
  def self.up
    
    create_table :queries do |table|
      table.column :value, :string
      table.column :num_total_results, :integer
      table.column :num_returned_results, :integer
    end
    
    create_table :queries_students, :id => false do |table|
      table.column :query_id, :integer
      table.column :student_id, :integer
    end
    
  end
  
  def self.down
    drop_table :queries_students
    drop_table :queries
  end
  
end