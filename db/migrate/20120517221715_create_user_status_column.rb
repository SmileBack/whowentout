class CreateUserStatusColumn < ActiveRecord::Migration
  def change
    add_column :users, :status, :string
    add_index :users, :status

    User.where(is_active: true).each do |user|
      if user.is_active?
        user.update_attributes(status: 'online')
      else
        user.update_attributes(status: 'registered')
      end
    end

    remove_column :users, :is_active
  end
end
