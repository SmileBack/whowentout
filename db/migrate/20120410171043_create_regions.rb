class CreateRegions < ActiveRecord::Migration
  def change
    create_table :regions do |t|
      t.string :name
      t.text :points, :text

      t.decimal :lat_min, :precision => 15, :scale => 10
      t.decimal :lat_max, :precision => 15, :scale => 10
      t.decimal :lng_min, :precision => 15, :scale => 10
      t.decimal :lng_max, :precision => 15, :scale => 10
    end
  end
end
