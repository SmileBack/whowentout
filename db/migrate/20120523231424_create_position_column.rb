class CreatePositionColumn < ActiveRecord::Migration
  def change
    add_column :smile_game_choices, :position, :integer
  end
end
