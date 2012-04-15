class CreateBirthdayColumn < ActiveRecord::Migration
  def change
    add_column :users, :birthday, :date
  end
end
