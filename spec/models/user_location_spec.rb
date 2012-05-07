require 'spec_helper'

describe User do

  before(:all) do

  end

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

      longitude_locations = venkat.past_locations.pluck(:longitude)
      longitude_locations.should == [22, 11]

      latitude_locations = venkat.past_locations.pluck(:latitude)
      latitude_locations.should == [23, 12]

      venkat.clear_location
      venkat.past_locations.count.should == 3
    end

  end

  describe "nearby_places" do

    it "should be nil when the user hasnt updated his/her location" do
      user = create(:user)
      user.nearby_places.should == nil
    end

    it "should show places sorted by distance", :vcr, :cassette => 'google_maps_api' do
      venkat = create(:user, first_name: "Venkat", last_name: "D")

      kennys_castaways = create(:place, name: "Kenny's Castaways", address: "157 Bleecker Street, New York, NY 10012")
      ale_house = create(:place, name: "MacDougal Street Ale House", address: "122 MacDougal Street, New York, NY")
      vbar_cafe = create(:place, name: "Vbar&Cafe", address: "225 Sullivan Street, New York, NY 10012")

      venkat.update_location(latitude: ale_house.latitude, longitude: ale_house.longitude)

      venkat.nearby_places.should_not == nil
      venkat.nearby_places.count.should == 3

      places = venkat.nearby_places.pluck(:name)
      places.should == ["MacDougal Street Ale House", "Vbar&Cafe", "Kenny's Castaways"]
    end

  end

end