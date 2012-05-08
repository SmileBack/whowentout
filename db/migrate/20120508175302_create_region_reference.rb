class CreateRegionReference < ActiveRecord::Migration
  def change
    add_column :users, :current_region_id, :integer
  end
end
