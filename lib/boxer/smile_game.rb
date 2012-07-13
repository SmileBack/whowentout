Boxer.box(:smile_game) do |box, smile_game|

  box.view(:base) do
    {
      id: smile_game.id,
    }
  end

  box.view(:sent, :extends => :base) do
    {
      receiver: Boxer.ship(:user, smile_game.receiver)
    }
  end

  box.view(:received, :extends => :base) do
    {
      receiver: Boxer.ship(:user, smile_game.sender, :view => :anonymous),
      guesses_remaining: smile_game.guesses_remaining
    }
  end

  box.view(:match, :extends => :base) do
    {
      receiver: smile_game.match.nil? ? nil : Boxer.ship(:user, smile_game.match)
    }
  end

end
