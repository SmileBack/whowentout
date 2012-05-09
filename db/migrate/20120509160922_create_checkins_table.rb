class CreateCheckinsTable < ActiveRecord::Migration
  def change
    create_table :checkins do |t|
      t.integer :user_id, :null => false
      t.integer :place_id, :null => false
      t.boolean :is_active, :null => false, :default => false

      t.timestamps
    end
  end
end
