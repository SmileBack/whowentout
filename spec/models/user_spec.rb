require 'spec_helper'

describe User do

  let(:fb_access_token) do
    "AAACm1V7H288BAEsdcHrcsMGDmjqTKLchLIFHu2Jh0HeZA0XZBvyuyZCvOGEFr9mMp4oiMFr2Yw6Nq1leTf9FasRZBKKtXUMZD"
  end

  describe "find_by_token" do

    it "should return the proper fields" do
      user = User.find_by_token(fb_access_token)

      user.facebook_id.should == 776200121
      user.first_name.should == 'Venkat'
      user.last_name.should == 'Dinavahi'
      user.email.should == 'ven@stanford.edu'
      user.gender.should == 'M'
      user.birthday.should == Date.new(1988, 10, 6)

      user.hometown.should == 'Severna Park, Maryland'
      user.current_city.should == 'Washington, District of Columbia'
    end

    it "shouldnt return duplicate users" do
      user_a = User.find_by_token(fb_access_token)
      user_b = User.find_by_token(fb_access_token)

      user_a.should == user_b
      user_a.id.should == user_b.id
    end

    it "should return nil if the token is invalid" do
      user = User.find_by_token('wooyah')
      user.should == nil
    end

  end

  describe "refresh_networks_from_facebook" do
    it "should work when called multiple times" do
      user = User.find_by_token(fb_access_token)

      user.refresh_networks_from_facebook
      user.refresh_networks_from_facebook

      user.networks.length.should == 2
    end
  end

  describe "networks" do
    it "should contain the right networks" do
      user = User.find_by_token(fb_access_token)

      networks = user.college_networks.pluck :name

      networks.length.should == 2
      networks.should include('Stanford')
      networks.should include('Maryland')
    end
  end

end