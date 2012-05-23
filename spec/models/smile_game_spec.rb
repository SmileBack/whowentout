require 'spec_helper'

describe SmileGame do

  it "should have the provided number of users" do
    a = create(:user, first_name: 'a')
    b = create(:user, first_name: 'b')

    c = create(:user, first_name: 'c')
    d = create(:user, first_name: 'd')

    game = SmileGame.create_for_user(b, a, 3)

    choices = game.choices.to_a.sort_by { |c| c.user.first_name }
    choices.count.should == 3

    choices[0].id.should == a.id
    choices[1].id.should == b.id
    choices[2].id.should == c.id
  end

  describe "smiling" do

    it "should create a game for the receiving user" do
      a = create(:user)
      b = create(:user)

      b.open_smile_games.should be_empty

      a.start_smile_game_with(b)
      b.open_smile_games.count.should_not be_empty
    end

    describe "direct smiling" do

      it "be limited to 3 per day" do
        a = create(:user)
        b = create(:user)

        a.start_smile_game_with(b).should == true
        a.start_smile_game_with(b).should == true
        a.start_smile_game_with(b).should == true

        a.start_smile_game_with(b).should == false

        Timecop.freeze(Date.today + 1) do
          a.start_smile_game_with(b).should == true
        end
      end

    end

  end

  describe "guessing" do

    it "should start a game" do
      a = create(:user)
      b = create(:user)
      c = create(:user)
      d = create(:user)

    end

    it "should be limited to 3 per game" do
    end

  end

  it "should have status started after 1 guess" do
  end

  it "should have status completed after 3 incorrect guesses" do
  end

  it "should have status found_match after after a correct guess" do
  end

  it "should have the guessed user in matched_user after a correct guess" do
  end



end
