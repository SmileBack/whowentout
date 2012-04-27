class CreatePlacesTable < ActiveRecord::Migration
  def change
    create_table :places do |t|
      t.string :type

      t.string :name
      t.string :phone_number

      t.string :address
      t.decimal :latitude, :precision => 15, :scale => 10, :null => false
      t.decimal :longitude, :precision => 15, :scale => 10, :null => false

      t.text :details
    end
  end
end
