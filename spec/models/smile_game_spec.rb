require 'spec_helper'

describe SmileGame do

  def create_users(names)
    names.map { |name| create(:user, first_name: name) }
  end

  it "should have the provided number of users" do
    SmileGame.define_shuffler do |arr|
      arr.sort
    end

    Kernel.stub!(:rand).and_return( 0 )

    a, b, c, d = create_users('a'..'d')

    game = a.start_smile_game_with(b, 3)

    choices = game.choices.map { |c| c.user.first_name }

    choices.should == ['a', 'c', 'd']
  end

  it "should be shuffled in the right order" do
    Kernel.stub!(:rand).and_return( 0 )
    SmileGame.define_shuffler do |arr|
      arr.reverse
    end

    a, b = create_users('a'..'d')

    game = a.start_smile_game_with(b, 3)

    choices = game.choices.map { |c| c.user.first_name }
    choices.should == ['d', 'c', 'a']
  end

  describe "smiling" do

    it "should create a game for the receiving user" do
      a, b = create_users('a'..'d')

      b.open_smile_games.should be_empty

      a.start_smile_game_with(b, 3)
      b.open_smile_games.should_not be_empty
    end

    it "should only let you smile at a user once" do
      a, b = create_users('a'..'z')

      a.can_start_smile_game_with?(b).should be_true
      a.start_smile_game_with(b, 3).should_not be_nil

      a.can_start_smile_game_with?(b).should be_false
      a.start_smile_game_with(b, 3).should be_nil
    end

    describe "direct smiling" do

      it "be limited to 3 per day" do
        a, b, c, d, e = create_users('a'..'z')

        a.start_smile_game_with(b)
        a.start_smile_game_with(c)
        a.start_smile_game_with(d)

        a.can_start_smile_game_with?(e).should be_false

        Timecop.freeze(Date.today + 1) do
          a.can_start_smile_game_with?(e).should be_true
          a.start_smile_game_with(e)
        end

        SmileGame.where(status: 'open').count.should == 4
      end
    end

  end

  describe "guessing" do

    it "should create a smile game when there is no match" do
      Kernel.stub!(:rand).and_return( 0 )
      SmileGame.define_shuffler do |arr|
        arr.sort
      end

      a, b, c = create_users('a'..'z')
      a.start_smile_game_with(b, 3)
      game = b.open_smile_games.first

      c.open_smile_games.should be_empty

      c_choice = game.choices.find_by_user_id(c.id)
      game.guess(c_choice)

      c.open_smile_games.should_not be_empty
    end

    it "should not create a smile game when there IS a match" do
      Kernel.stub!(:rand).and_return( 0 )
      SmileGame.define_shuffler do |arr|
        arr.sort
      end

      a, b, c = create_users('a'..'z')
      a.start_smile_game_with(b, 3)
      game = b.open_smile_games.first

      c.open_smile_games.should be_empty

      a_choice = game.choices.find_by_user_id(a.id)
      game.guess(a_choice)

      c.open_smile_games.should be_empty
    end

    it "should end the game with the matched user when there IS a match" do
      a, b, c, d = create_users('a'..'d')

      a.start_smile_game_with(b, 3)

      game = b.open_smile_games.first
      game.open?.should be_true

      a_choice = game.choices.find_by_user_id(a.id)
      game.guess(a_choice)

      game.open?.should be_false
      game.match.first_name.should == 'a'
    end


    it "should end the game when the user makes 3 incorrect guesses" do
      Kernel.stub!(:rand).and_return( 0 )
      SmileGame.define_shuffler do |arr|
        arr.sort
      end

      a, b, c, d, e = create_users('a'..'g')

      a.start_smile_game_with(b, 4)

      game = b.open_smile_games.first
      game.open?.should be_true

      [c, d, e].each do |user|
        choice = game.choices.find_by_user_id(user.id)
        game.open?.should be_true
        game.guess(choice, 4)
      end

      game.open?.should be_false
      game.match.should be_nil
    end

  end

end
