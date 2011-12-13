class CreateQueriesTable < ActiveRecord::Migration
  def self.up
    create_table :queries do |table|
      table.column :num_results, :integer
      table.column :first_name, :string
      table.column :first_name_match, :string
      table.column :last_name, :string
      table.column :last_name_match, :string
    end
  end

  def self.down
    drop_table :students
  end
end
