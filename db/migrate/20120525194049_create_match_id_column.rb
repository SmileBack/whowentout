class CreateMatchIdColumn < ActiveRecord::Migration
  def change
    add_column :smile_games, :match_id, :integer
  end
end
