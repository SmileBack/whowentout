class ChangeDecimalGeoColumnsToFloats < ActiveRecord::Migration
  def change
    change_table :places do |t|
      t.change :latitude, :float
      t.change :longitude, :float
    end

    change_table :regions do |t|
      t.change :lat_min, :float
      t.change :lat_max, :float
      t.change :lng_min, :float
      t.change :lng_max, :float
    end

    change_table :user_locations do |t|
      t.change :latitude, :float
      t.change :longitude, :float
    end
  end
end

