class RenameProtectedTypeColumn < ActiveRecord::Migration
  def change
    rename_column :networks, :type, :network_type
  end
end
