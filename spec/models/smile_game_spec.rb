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

    #describe "direct smiling" do
    #
    #  it "be limited to 3 per day" do
    #    a = create(:user)
    #    b = create(:user)
    #
    #    a.start_smile_game_with(b).should == true
    #    a.start_smile_game_with(b).should == true
    #    a.start_smile_game_with(b).should == true
    #
    #    a.start_smile_game_with(b).should == false
    #
    #    Timecop.freeze(Date.today + 1) do
    #      a.start_smile_game_with(b).should == true
    #    end
    #  end
    #end

  end

end
