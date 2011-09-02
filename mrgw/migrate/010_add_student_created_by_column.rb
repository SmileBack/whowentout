class AddStudentCreatedByColumn < ActiveRecord::Migration
  def self.up
    add_column :students, :created_by_id, :integer
    
    add_index :students, :created_by_id
  end
  
  def self.down
    remove_column :students, :created_by_id
  end
end
