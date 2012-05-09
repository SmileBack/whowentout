require 'spec_helper'

describe User do

  describe "current_checkin" do

    it "should start out as nil" do
      user = create(:user)
      user.current_checkin.should == nil
    end

  end

  describe "update_checkin" do

    it "should update the current checkin" do
      user = create(:user)
      place = create(:place)

      user.checkin_to(place)
      user.current_checkin.place.should == place
    end

    it "should replace the previous checkin" do
      user = create(:user)
      place_a = create(:place)
      place_b = create(:place)

      user.checkin_to(place_a)
      user.update_checkin(place_b)

      user.current_checkin.place.should == place_b
    end

  end

  describe "clear_checkin" do

    it "should do nothing if there is no checkin" do
      user = create(:user)

      user.clear_checkin
      user.current_checkin.should == nil
    end

    it "should remove the current checkin" do
      user = create(:user)
      place = create(:place)

      user.checkin_to(place)
      user.clear_checkin

      user.current_checkin.should == nil
    end

  end

  describe "past_checkins" do

    it "should start out as empty" do
      user = create(:user)
      user.past_checkins.should be_empty
    end

    it "should stay empty after the first checkin" do
      user = create(:user)
      place = create(:place)

      user.past_checkins.should be_empty
    end

    it "should become non-empty after clearing a checkin" do
      user = create(:user)
      place = create(:place)

      user.checkin_to(place)
      user.clear_checkin

      user.past_checkins.pluck(:place).should == [place]
    end

    it "should be non-empty after the second checkin and be sorted desc" do
      user = create(:user)
      a, b, c = create(:place), create(:place), create(:place)

      user.checkin_to(a)
      user.checkin_to(b)
      user.checkin_to(c)

      user.current_checkin.place.should == c
      user.past_checkins.pluck(:place).should == [b, a]
    end

  end

  describe "clear_all_checkins" do
    it "should set all current_checkin values to nil" do
      user_a = create(:user)
      user_b = create(:user)
      place = create(:place)

      user_a.checkin_to(place)
      user_b.checkin_to(place)

      User.clear_all_checkins

      #todo: make reload unnecessary
      user_a.reload
      user_b.reload

      user_a.current_checkin.should == nil
      user_b.current_checkin.should == nil
    end
  end

end