class CreateFacebookTokenColumn < ActiveRecord::Migration
  def change
    add_column :users, :facebook_token, :text
  end
end