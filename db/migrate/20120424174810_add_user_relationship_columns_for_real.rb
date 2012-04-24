class AddUserRelationshipColumnsForReal < ActiveRecord::Migration
  def change
    add_column :users, :relationship_status, :string, :default => '?'
    add_column :users, :interested_in, :string, :default => '?'
  end
end
