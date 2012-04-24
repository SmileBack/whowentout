require 'spec_helper'

describe User do

  let(:venkats_token) { "AAACEdEose0cBALvOM1GpXfmMctNM50gJG2IV8zJkqXvzjT3ylu1WbCbdB1PTj74oDx6eKZChuhREATXrn7rtc6FNHJDQWbRhEOcpNeAZDZD" }

  describe "find_by_token" do

    it "should return the proper fields" do
      user = User.find_by_token(venkats_token)

      user.is_active?.should == true
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
      user_a = User.find_by_token(venkats_token)
      user_b = User.find_by_token(venkats_token)

      user_a.should == user_b
      user_a.id.should == user_b.id
    end

    it "should return nil if the token is invalid" do
      user = User.find_by_token('wooyah')
      user.should == nil
    end

  end


  describe "sync_networks_from_facebook" do
    it "should work when called multiple times" do
      user = User.find_by_token(venkats_token)

      user.should respond_to :sync_networks_from_facebook

      user.sync_networks_from_facebook
      user.sync_networks_from_facebook

      user.networks.length.should == 2
    end
  end


  describe "networks" do
    it "should contain the right networks" do
      user = User.find_by_token(venkats_token)
      user.should respond_to :college_networks

      networks = user.college_networks.pluck :name

      networks.length.should == 2
      networks.should include('Stanford')
      networks.should include('Maryland')
    end
  end


  describe "facebook_friends" do
    it "shouldnt be empty" do
      user = User.find_by_token(venkats_token)

      user.facebook_friends.empty?.should == false

      friend_names = user.facebook_friends.map { |friend| "#{friend.first_name} #{friend.last_name}"}

      friend_names.count.should == 269
      friend_names.should include('Dan Berenholtz')
    end

    it "should come with the college networks" do
      user = User.find_by_token(venkats_token)

      danb = user.facebook_friends.where(:facebook_id => 8100231).first
      danb.should_not be_nil

      network_names = danb.networks.pluck(:name).sort
      network_names.should == ['Cornell', 'GWU', 'Stanford']
    end

    it "should update after calling update_friends_from_facebook" do
      user = User.find_by_token(venkats_token)

      user.should respond_to :sync_friends_from_facebook
    end

  end


  describe "interests" do

    it "should return the correct interests" do
      user = User.find_by_token(venkats_token)

      interest_names = user.interests.pluck(:name)

      interest_names.should include('Web development')
      interest_names.should include('Graphic Design')
      interest_names.should include('Traveling')


    end

    it "should provide correct facebook ids for interests taken from facebook" do
      user = User.find_by_token(venkats_token)
      user.interests.where(:name => 'Traveling').first.facebook_id.should == 110534865635330
    end

  end

end