require 'spec_helper'

describe "ActiveRecord" do
  describe "association" do
    it "should point to the same object" do
      user = create(:user)
      user.current_location.should == nil

      user.update_location(latitude: 11, longitude: 22)
      user.current_location.should_not == nil

      location = UserLocation.first

      location.id.should == user.current_location.id
      location.object_id.should == user.current_location.object_id
    end
  end
end
