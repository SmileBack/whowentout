class CreateStudentsTable < ActiveRecord::Migration
  def self.up
    create_table :students do |table|
      table.column :email, :string
      table.column :name, :string
      table.column :role, :string
      table.column :link, :text
    end
    add_index :students, :email, :unique => true
  end
  
  def self.down
    drop_table :students
  end
end