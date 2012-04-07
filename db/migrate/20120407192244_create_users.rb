class CreateUsers < ActiveRecord::Migration
  def change
    create_table :users do |t|
      t.int :facebook_id, :limit => 8
      t.string :first_name
      t.string :last_name
      t.string :email

      t.timestamps
    end
  end
end
