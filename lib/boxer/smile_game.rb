Boxer.box(:smile_game) do |box, smile_game|

  box.view(:base) do
    {
      id: smile_game.id,
    }
  end

  box.view(:sent) do
    {
      receiver: Boxer.ship(:user, smile_game.receiver)
    }
  end

end
