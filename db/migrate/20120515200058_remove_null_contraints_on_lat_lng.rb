class RemoveNullContraintsOnLatLng < ActiveRecord::Migration
  def change
    change_column :places, :latitude, :float, :null => true
    change_column :places, :longitude, :float, :null => true
  end
end
