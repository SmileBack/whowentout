class AddIndexToQueryStudents < ActiveRecord::Migration
  def self.up
    add_index :queries_students, [:query_id, :student_id], :unique => true
  end
  
  def self.down
    remove_index :queries_students, [:query_id, :student_id]
  end
end
