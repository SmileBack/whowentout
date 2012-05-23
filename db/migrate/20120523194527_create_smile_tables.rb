class CreateSmileTables < ActiveRecord::Migration

  def change

    create_table :smile_games do |t|
      t.string :status

      t.integer :sender_id
      t.integer :receiver_id
      t.integer :origin_id

      t.timestamps
    end

    add_index :smile_games, :sender_id
    add_index :smile_games, :receiver_id
    add_index :smile_games, :status


    create_table :smile_game_choices do |t|
      t.string :status

      t.integer :smile_game_id
      t.integer :user_id

      t.timestamps
    end

    add_index :smile_game_choices, :smile_game_id
  end

end
