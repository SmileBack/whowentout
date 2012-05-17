class MakeAddressesUnique < ActiveRecord::Migration
  def change
    change_table :places do |t|
      t.timestamps
    end

    add_index :places, :address, :unique => true
  end
end
