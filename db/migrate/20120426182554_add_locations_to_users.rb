class AddLocationsToUsers < ActiveRecord::Migration
  def change
    create_table :user_locations do |t|
      t.integer :user_id, :null => false

      t.decimal :latitude, :precision => 15, :scale => 10, :null => false
      t.decimal :longitude, :precision => 15, :scale => 10, :null => false
      t.boolean :is_active, :null => false, :default => false

      t.timestamps
    end
  end
end
