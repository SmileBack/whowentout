class CreateQueriesTable < ActiveRecord::Migration
  def self.up
    create_table :queries do |table|
      table.column :created_at, :datetime
      table.column :updated_at, :datetime

      table.column :num_results, :integer
      table.column :value, :string
    end
  end

  def self.down
    drop_table :students
  end
end
