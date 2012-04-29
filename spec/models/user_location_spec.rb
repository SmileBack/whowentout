require 'spec_helper'

describe User do

  describe "current_location" do

    it "should exist" do
      venkat = create(:user, first_name: "Venkat", last_name: "D")
      venkat.should respond_to(:current_location)
    end

    it "should start out nil" do
      venkat = create(:user, first_name: "Venkat", last_name: "D")
      venkat.current_location.should == nil
    end

    it "should set the current location" do
      venkat = create(:user, first_name: "Venkat", last_name: "D")
      venkat.update_location(longitude: 66, latitude: 77)

      venkat.current_location.longitude.should == 66
      venkat.current_location.latitude.should == 77

      venkat.update_location(longitude: 88, latitude: 99)
      venkat.current_location.longitude.should == 88
      venkat.current_location.latitude.should == 99
    end

    it "should get set to nil when the location is cleared" do
      venkat = create(:user, first_name: "Venkat", last_name: "D")

      venkat.update_location(longitude: 11, latitude: 22)
      venkat.current_location.should_not == nil

      venkat.clear_location
      venkat.current_location.should == nil
    end

  end

  describe "past_locations" do

    it "should transfer to past_location" do
      venkat = create(:user, first_name: "Venkat", last_name: "D")
      venkat.past_locations.count.should == 0

      venkat.update_location(longitude: 11, latitude: 12)
      venkat.past_locations.count.should == 0

      venkat.update_location(longitude: 22, latitude: 23)
      venkat.past_locations.count.should == 1

      venkat.update_location(longitude: 33, latitude: 24)
      venkat.past_locations.count.should == 2

      locations = venkat.past_locations.pluck(:longitude)
      locations.should == [22, 11]

      venkat.clear_location
      venkat.past_locations.count.should == 3
    end



  end

end