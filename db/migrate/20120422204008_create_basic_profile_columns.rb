class CreateBasicProfileColumns < ActiveRecord::Migration
  def change
    add_column :users, :hometown, :string
    add_column :users, :current_city, :string
  end
end
