class RemovePlaceTypeColumn < ActiveRecord::Migration
  def change
    remove_column :places, :place_type
  end
end
