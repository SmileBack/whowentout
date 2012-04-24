class ChangeDefaultValueForRelationshipColumns < ActiveRecord::Migration
  def change
    change_column_default(:users, :relationship_status, nil)
    change_column_default(:users, :interested_in, nil)
  end
end
