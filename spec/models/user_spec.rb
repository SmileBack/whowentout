require 'spec_helper'

describe User do

  let(:venkats_token) { "AAACEdEose0cBAM04sZBwqPbKm1SviCmFSA9vewlGxZBFadbXZCCNCwY4ZBnHtd4l62d3vv7V2PyXkP1LwHblNE8AwFC0ZCwnTFVhtphtXMwZDZD" }
  let(:dans_token) { "AAACEdEose0cBAD2RuX8t5pcS9km8AHpZBQ6Ah3DA2IGJUw6zXwVZCIbj8WKRsNqJQPsBvmqRCw7YSUYIdO0uyvvgDsK3bExUQZC807BHgZDZD" }

  describe "find_by_token" do

    it "should return the proper fields", :vcr do
      user = User.find_by_token(venkats_token)

      user.is_active?.should == true
      user.facebook_id.should == 776200121
      user.first_name.should == 'Venkat'
      user.last_name.should == 'Dinavahi'
      user.email.should == 'ven@stanford.edu'
      user.gender.should == 'M'
      user.birthday.should == Date.new(1988, 10, 6)

      user.relationship_status.should == 'Single'
      user.interested_in.should == 'female'

      user.hometown.should == 'Severna Park, Maryland'
      user.current_city.should == 'Washington, District of Columbia'
    end

    it "shouldnt return duplicate users", :vcr do
      user_a = User.find_by_token(venkats_token)
      user_b = User.find_by_token(venkats_token)

      user_a.should == user_b
      user_a.id.should == user_b.id
    end

    it "should return nil if the token is invalid", :vcr do
      user = User.find_by_token('wooyah')
      user.should == nil
    end

  end


  describe "sync_networks_from_facebook" do
    it "should work when called multiple times", :vcr do
      user = User.find_by_token(venkats_token)

      user.should respond_to :sync_networks_from_facebook

      user.sync_networks_from_facebook
      user.sync_networks_from_facebook

      user.networks.length.should == 2
    end
  end


  describe "networks" do
    it "should contain the right networks", :vcr do
      user = User.find_by_token(venkats_token)
      user.should respond_to :college_networks

      networks = user.college_networks.pluck :name

      networks.length.should == 2
      networks.should include('Stanford')
      networks.should include('Maryland')
    end
  end


  describe "facebook_friends" do

    it "shouldnt be empty", :vcr do
      user = User.find_by_token(venkats_token)

      user.facebook_friends.empty?.should == false

      friend_names = user.facebook_friends.map { |friend| "#{friend.first_name} #{friend.last_name}"}
      friend_names.should include('Dan Berenholtz')
    end

    it "should come with the college networks", :vcr do
      user = User.find_by_token(venkats_token)

      danb = user.facebook_friends.where(:facebook_id => 8100231).first
      danb.should_not be_nil

      network_names = danb.networks.pluck(:name).sort
      network_names.should == ['Cornell', 'GWU', 'Stanford']
    end

    it "should update after calling update_friends_from_facebook", :vcr do
      user = User.find_by_token(venkats_token)

      # todo delete a few friends and add a few friends
      # sync friends from facebook

      # check that dummy friends are gone
      # check that deleted friends are back
      # check that count is what it previously was
    end

  end

  describe "work" do
    it "should be nil if not listed on profile", :vcr do
      ven = User.find_by_token(venkats_token)
      ven.work.should == nil
    end

    it "should be set to the employer if present", :vcr do
      dan = User.find_by_token(dans_token)
      dan.work.should == 'WhoWentOut'
    end
  end

  describe "interests" do

    it "should return the correct interests", :vcr do
      user = User.find_by_token(venkats_token)

      interest_names = user.interests.pluck(:name)

      interest_names.should include('Web development')
      interest_names.should include('Graphic Design')
      interest_names.should include('Traveling')

    end

    it "should provide correct facebook ids for interests taken from facebook", :vcr do
      user = User.find_by_token(venkats_token)
      user.interests.where(:name => 'Traveling').first.facebook_id.should == 110534865635330
    end

  end

  describe "photo" do

    it "should be a valid picture", :vcr do
      venkat = User.find_by_token(venkats_token)
      venkat.photo.thumb.should match /\.jpg$/
      venkat.photo.large.should match /\.jpg$/

      venkat.photo.thumb.should_not == venkat.photo.large

      dan = User.find_by_token(dans_token)
      dan.photo.thumb.should match /\.jpg$/
      dan.photo.large.should match /\.jpg$/
    end

  end

end