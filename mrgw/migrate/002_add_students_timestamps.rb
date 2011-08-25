class AddStudentsTimestamps < ActiveRecord::Migration
  def self.up
    add_column :students, :created_at, :datetime
    add_column :students, :updated_at, :datetime
  end
  
  def self.down
    remove_column :students, :created_at
    remove_column :students, :updated_at
  end
end
