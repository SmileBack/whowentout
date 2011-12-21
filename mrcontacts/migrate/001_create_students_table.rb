class CreateStudentsTable < ActiveRecord::Migration
  def self.up
    create_table :students do |table|
      table.column :created_at, :datetime
      table.column :updated_at, :datetime

      table.column :email, :string
      table.column :name, :string

      table.column :data, :text
    end

    add_index :students, :name
    add_index :students, :email
  end
  
  def self.down
    drop_table :students
  end
end
