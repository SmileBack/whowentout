require 'spec_helper'

describe Place do

  describe "creating from address" do

    it "should get the correct longitude and latitude coordinates", :vcr, :cassette => 'google_maps' do
      statue_of_liberty = Place.create!(:address => 'Statue of Liberty, NY')

      statue_of_liberty.latitude.round(2).should == 40.69
      statue_of_liberty.longitude.round(2).should == -74.04
    end
  end

end