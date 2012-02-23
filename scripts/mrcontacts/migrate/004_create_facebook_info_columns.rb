class CreateFacebookInfoColumns < ActiveRecord::Migration

  def self.up
    add_column :students, :facebook_id, :string
    add_column :students, :facebook_name, :string
    add_column :students, :gender, :string

    add_index :students, :facebook_id
  end

  def self.down
    remove_column :students, :facebook_id
    remove_column :students, :facebook_name
    remove_column :students, :gender
  end
end