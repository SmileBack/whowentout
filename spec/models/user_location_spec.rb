require 'spec_helper'

describe User do

  def place_addresses
    {
        "MacDougal Street Ale House" => "122 MacDougal Street, New York, NY",
        "Vbar&Cafe" => "225 Sullivan Street, New York, NY 10012",
        "Le Figaro Cafe" => "174 Bleecker Street, New York, NY 10012",
        "Comedy Cellar" => "117 MacDougal Street, New York, NY 10012",
        "The Dove Parlor" => "228 Thompson Street, New York, NY 10012",
        "Kenny's Castaways" => "157 Bleecker Street, New York, NY 10012",
        "The Room" => "144 Sullivan St, Manhattan, New York 10012",
        "Sullivan Bistro" => "169 Sullivan Street, New York, NY 10012",
        "Children's Aid Society" => "219 Sullivan Street, New York, NY 10012",
        "1849" => "183 Bleecker Street, New York, NY 10012"
    }
  end

  def get_place(name)
    place = Place.find_by_name(name)
    if place.nil?
      place = create(:place, name: name, address: place_addresses[name])
    end
    return place
  end

  def create_region_from_places(region_name, point_names)
    point_places = point_names.map { |name| get_place(name) }
    points = point_places.map { |place| place.to_coordinates }
    create(:region, name: region_name, points: points)
  end

  def create_region_a
    create_region_from_places 'a', ['Le Figaro Cafe', 'Comedy Cellar', 'The Dove Parlor']
  end

  def create_region_b
    create_region_from_places 'b', ['Le Figaro Cafe', 'The Dove Parlor', "Kenny's Castaways", 'The Room']
  end

  def point_inside_a
    get_place("Vbar&Cafe")
  end

  def second_point_inside_a
    get_place("1849")
  end

  def point_inside_b
    get_place("Sullivan Bistro")
  end

  describe "update_location" do

    it "should set the current location" do
      user = create(:user)
      user.update_location(longitude: 66, latitude: 77)

      user.current_location.longitude.should == 66
      user.current_location.latitude.should == 77
      user.longitude.should == 66
      user.latitude.should == 77

      user.update_location(longitude: 88, latitude: 99)
      user.current_location.longitude.should == 88
      user.current_location.latitude.should == 99
      user.longitude.should == 88
      user.latitude.should == 99
    end

    it "should work with objects that have longitude and latitude" do
      user = create(:user)

      class SomePlace
        def latitude
          33
        end
        def longitude
          44
        end
      end

      user.update_location(SomePlace.new)

      user.current_location.latitude.should == 33
      user.current_location.longitude.should == 44
    end

    it "should work with an array" do
      user = create(:user)

      user.update_location([77, 88])

      user.current_location.latitude.should == 77
      user.current_location.longitude.should == 88
    end

  end

  describe "current_location" do
    it "should exist" do
      user = create(:user)
      user.should respond_to(:current_location)
    end

    it "should start out nil" do
      user = create(:user)
      user.current_location.should == nil
      user.latitude.should == nil
      user.longitude.should == nil
    end
  end

  describe "clear_location" do
    it "should get current_location to nil when called" do
      user = create(:user)

      user.update_location(longitude: 11, latitude: 22)
      user.current_location.should_not == nil

      user.clear_location
      user.current_location.should == nil
      user.latitude.should == nil
      user.longitude.should == nil
    end
  end

  describe "clear_all_locations" do
    it "should set all current_location values to nil" do
      user_a = create(:user)

      user_a.update_location(longitude: 11, latitude: 22)
      user_a.current_location.should_not == nil

      User.clear_all_locations
      user_a.current_location.should == nil
    end
  end

  describe "past_locations" do

    it "should transfer to past_location" do
      user = create(:user)
      user.past_locations.count.should == 0

      user.update_location(longitude: 11, latitude: 12)
      user.past_locations.count.should == 0

      user.update_location(longitude: 22, latitude: 23)
      user.past_locations.count.should == 1

      user.update_location(longitude: 33, latitude: 24)
      user.past_locations.count.should == 2

      longitude_locations = user.past_locations.pluck(:longitude)
      longitude_locations.should == [22, 11]

      latitude_locations = user.past_locations.pluck(:latitude)
      latitude_locations.should == [23, 12]

      user.clear_location
      user.past_locations.count.should == 3
    end

  end

  describe "nearby_places" do

    it "should be nil when the user hasnt updated his/her location" do
      user = create(:user)
      user.nearby_places.should == nil
    end

    it "should show places sorted by distance", :vcr, :cassette => 'google_maps_api' do
      user = create(:user)

      kennys_castaways = get_place("Kenny's Castaways")
      ale_house = get_place("MacDougal Street Ale House")
      vbar_cafe = get_place("Vbar&Cafe")

      user.update_location(ale_house)

      user.nearby_places.should_not == nil
      user.nearby_places.count.should == 3

      places = user.nearby_places.pluck(:name)
      places.should == ["MacDougal Street Ale House", "Vbar&Cafe", "Kenny's Castaways"]
    end

  end

  describe "nearby_users" do

    it "should be nil when the user doesn't have a location" do
      user = create(:user)
      user.current_location.should == nil
      user.nearby_users.should == nil
    end

    it "should only show users in your neighborhood", :vcr, :cassette => 'google_maps_api' do
      user = create(:user)

      joe = create(:user, first_name: "Joe")
      kate = create(:user, first_name: "Kate")
      jake = create(:user, first_name: "Jake")

      region_a = create_region_a
      region_b = create_region_b

      # these users are in region a
      user.update_location(point_inside_a)
      jake.update_location(point_inside_a)
      kate.update_location(second_point_inside_a)

      # this user is in region b
      joe.update_location(point_inside_b)

      users = user.nearby_users.pluck(:first_name).sort
      users.should == ['Jake', 'Kate']

      user.update_location(point_inside_b)
      users = user.nearby_users.pluck(:first_name).sort
      users.should == ['Joe']
    end

  end

  describe "current_region" do

    it "should be nil when the user hasnt updated his/her location" do
      user = create(:user)
      user.current_region.should == nil
    end

    it "should return the current region of the user", :vcr, :cassette => 'google_maps_api' do
      user = create(:user)

      region_a = create_region_a
      inside_a = point_inside_a

      user.update_location(inside_a)
      user.current_region.should == region_a
    end

    it "should update when the user changes locations from one region to another", :vcr, :cassette => 'google_maps_api' do
      user = create(:user)

      region_a = create_region_a
      region_b = create_region_b

      inside_a = point_inside_a
      inside_b = point_inside_b

      user.update_location(inside_a)
      user.current_region.should == region_a

      user.update_location(inside_b)
      user.current_region.should == region_b
    end

    it "should become nil when the user leaves all regions", :vcr, :cassette => 'google_maps_api' do
      user = create(:user)

      region_a = create_region_a
      inside_a = point_inside_a
      outside_a = point_inside_b

      user.update_location(inside_a)
      user.current_region.should == region_a

      user.update_location(outside_a)
      user.current_region.should == nil
    end

    it "should become nil when the user clears his location", :vcr, :cassette => 'google_maps_api' do
      user = create(:user)

      region_a = create_region_a
      inside_a = point_inside_a

      user.update_location(inside_a)
      user.current_region.should == region_a

      user.clear_location
      user.current_region.should == nil
    end

  end

end