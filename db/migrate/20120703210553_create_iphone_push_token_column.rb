class CreateIphonePushTokenColumn < ActiveRecord::Migration
  def change
    add_column :users, :iphone_push_token, :text
  end
end

