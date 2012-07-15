Boxer.box(:smile_game_choice) do |box, smile_game_choice|

  box.view(:base) do
    {
      status: smile_game_choice.status,
      position: smile_game_choice.position,
      user: Boxer.ship(:user, smile_game_choice.user)
    }
  end

end
