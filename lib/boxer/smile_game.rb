Boxer.box(:smile_game) do |box, smile_game, current_user|

  box.view(:base) do
    {
      id: smile_game.id,
      status: smile_game.status
    }
  end

  box.view(:sent, :extends => :base) do
    {
      receiver: Boxer.ship(:user, smile_game.receiver)
    }
  end

  box.view(:received, :extends => :base) do
    view = smile_game.status == 'match' ? :base : :anonymous
    {
      sender: Boxer.ship(:user, smile_game.sender, :view => view),
      guesses_remaining: smile_game.guesses_remaining
    }
  end

  box.view(:matched, :extends => :base) do
    match = (smile_game.sender == current_user) ? smile_game.receiver : smile_game.sender
    {
        match: Boxer.ship(:user, match),
        guesses_remaining: smile_game.guesses_remaining
    }
  end

  box.view(:full, :extends => :base) do
    {
      choices: Boxer.ship_all(:smile_game_choice, smile_game.choices),
      guesses_remaining: smile_game.guesses_remaining
    }
  end

end

