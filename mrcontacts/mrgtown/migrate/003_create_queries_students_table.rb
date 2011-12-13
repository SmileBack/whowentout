class CreateQueriesStudentsTable < ActiveRecord::Migration
  def self.up
      create_table :queries_students, :id => false do |table|
      table.column :query_id, :integer
      table.column :student_id, :integer
    end
  end

  def self.down
    drop_table :queries_students
  end
end