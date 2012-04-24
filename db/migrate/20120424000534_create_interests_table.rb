class CreateInterestsTable < ActiveRecord::Migration
  def change
    create_table :interests do |t|
      t.integer :id
      t.integer :facebook_id, :limit => 8
      t.string :name, :null => false
    end

    add_index :interests, :facebook_id

    create_table :user_interests do |t|
      t.integer :user_id
      t.integer :interest_id
    end

    add_index :user_interests, [:user_id, :interest_id], :unique => true
  end
end