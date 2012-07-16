Boxer.box(:smile_game_choice) do |box, smile_game_choice|

  box.view(:base) do
    {
      id: smile_game_choice.id,
      smile_game_id: smile_game_choice.smile_game_id,
      status: smile_game_choice.status,
      position: smile_game_choice.position,
      user: Boxer.ship(:user, smile_game_choice.user)
    }
  end

end
