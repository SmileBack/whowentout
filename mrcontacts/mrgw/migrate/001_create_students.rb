class CreateStudents < ActiveRecord::Migration
  def self.up
    create_table :students do |table|
      table.column :email, :string
      table.column :name, :string
    end
  end
  
  def self.down
    drop_table :students
  end
end