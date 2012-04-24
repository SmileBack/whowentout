class CreatePhotosTable < ActiveRecord::Migration
  def change
    create_table :photos do |t|
      t.integer :user_id

      t.string :facebook_id
      t.time :created_at
      t.text :thumb
      t.text :large
    end

    add_index :photos, :facebook_id
    add_index :photos, :user_id

    add_column :users, :photo_id, :integer
  end
end

