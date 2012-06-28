require 'spec_helper'

describe User do

  let(:venkats_token) { "AAACEdEose0cBAHe29aI1no1SzdwvsYzc9hrdiec4ORyOQe1zD9VgBtN8k1keVGnjVtE9eZBvqMo2ZCcoEXspVW5aAinAtZBBnjZCZBUgupgZDZD" }
  let(:dans_token) { "AAACEdEose0cBAJS6LCNhveh1m4PQlGXkENqPw8KQbZAkthfC60efxRfKat0HZB9Pc0c7rL53FLYxXQ2XoZA5jZBYcCYldF11KOM5aZB7wQwZDZD" }

  describe "find_by_token" do

    before(:all) do
      User.destroy_all
    end

    it "should return nil if passed an empty or nil value" do
      User.find_by_token(nil).should == nil
      User.find_by_token("").should == nil
    end


    it "should update the old token", :vcr, :cassette => 'facebook_api' do
      user = create(:user, facebook_id: 776200121, facebook_token: "old token")

      user.facebook_token.should == "old token"

      venkat = User.find_by_token(venkats_token)

      venkat.id.should == user.id
      venkat.facebook_token.should == venkats_token
    end

    it "should return the proper fields", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)

      user.registered?.should == true
      user.facebook_id.should == 776200121
      user.first_name.should == 'Venkat'
      user.last_name.should == 'Dinavahi'
      user.email.should == 'ven@stanford.edu'
      user.gender.should == 'M'
      user.birthday.should == Date.new(1988, 10, 6)

      Timecop.freeze(Time.local(2012, 6, 25, 11, 30, 0)) do
        user.age.should == 23
      end

      user.relationship_status.should == 'Single'
      user.interested_in.should == 'female'

      user.hometown.should == 'Severna Park, Maryland'
      user.current_city.should == 'Washington, District of Columbia'
    end

    it "shouldnt return duplicate users", :vcr, :cassette => 'facebook_api' do
      user_a = User.find_by_token(venkats_token)
      user_b = User.find_by_token(venkats_token)

      user_a.should == user_b
      user_a.id.should == user_b.id
    end

    it "should return nil if the token is invalid", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token('wooyah')
      user.should == nil
    end

  end

  describe "registered?" do
    it "should be true when a user has provided a valid token", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.registered?.should == true
    end

    it "should be false for new users", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.sync_from_facebook :friends

      sean = User.find_by_first_name_and_last_name('Sean', 'Holbert')
      sean.registered?.should == false
    end

  end

  describe "sync_from_facebook :networks" do
    it "should work when called multiple times", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)

      user.sync_from_facebook :networks
      user.sync_from_facebook :networks

      user.networks.length.should == 2
    end
  end


  describe "networks" do
    it "should contain the right networks", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.sync_from_facebook :networks

      user.should respond_to :college_networks

      networks = user.college_networks.pluck :name

      networks.length.should == 2
      networks.should include('Stanford')
      networks.should include('Maryland')
    end
  end


  describe "facebook_friends" do

    it "shouldnt be empty", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.sync_from_facebook :friends

      user.facebook_friends.empty?.should == false
      user.facebook_friends.find_by_first_name_and_last_name('Dan', 'Berenholtz').should_not == nil
    end

    it "should come with the college networks", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.sync_from_facebook :friends

      danb = user.facebook_friends.where(:facebook_id => 8100231).first
      danb.should_not be_nil

      network_names = danb.networks.pluck(:name).sort
      network_names.should == ['Cornell', 'GWU', 'Stanford']
    end

    it "should update after calling sync_from_facebook :friends", :vcr, :cassette => 'facebook_api' do
      venkat = User.find_by_token(venkats_token)
      venkat.sync_from_facebook :friends

      sean = venkat.facebook_friends.find_by_first_name_and_last_name('Sean', 'Holbert')
      sean.should_not == nil

      bruce = venkat.facebook_friends.find_by_first_name_and_last_name('Bruce', 'Lee')
      bruce.should == nil

      # delete and add a friend
      venkat.facebook_friends.delete(sean)
      venkat.facebook_friends.find_by_first_name_and_last_name('Sean', 'Holbert').should == nil

      venkat.facebook_friends.create(:first_name => 'Bruce', :last_name => 'Lee', :gender => 'M')
      venkat.facebook_friends.find_by_first_name_and_last_name('Bruce', 'Lee').should_not == nil

      # sync friends from facebook
      venkat.sync_from_facebook :friends

      # check that the deleted friend is back and the added friend is gone
      venkat.facebook_friends.find_by_first_name_and_last_name('Sean', 'Holbert').should_not == nil
      venkat.facebook_friends.find_by_first_name_and_last_name('Bruce', 'Lee').should == nil
    end

  end

  describe "work" do
    it "should be nil if not listed on profile", :vcr, :cassette => 'facebook_api' do
      ven = User.find_by_token(venkats_token)
      ven.work.should == nil
    end

    it "should be set to the employer if present", :vcr, :cassette => 'facebook_api' do
      dan = User.find_by_token(dans_token)
      dan.work.should == 'WhoWentOut'
    end
  end

  describe "interests" do

    it "should return the correct interests", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.sync_from_facebook :interests

      interest_names = user.interests.pluck(:name)

      interest_names.should include('Web development')
      interest_names.should include('Graphic Design')
      interest_names.should include('Traveling')

      web_dev = user.interests.where(name: 'Web development').first
      web_dev.tag_list.should include('interest')
    end

    it "should come with pictures", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.sync_from_facebook :interests

      user.interests.first.thumb.should_not be_nil
    end

    it "should provide correct facebook ids for interests taken from facebook", :vcr, :cassette => 'facebook_api' do
      user = User.find_by_token(venkats_token)
      user.sync_from_facebook :interests

      user.interests.where(:name => 'Traveling').first.facebook_id.should == 110534865635330
    end

  end

  describe "photo" do

    it "should be a valid picture", :vcr, :cassette => 'facebook_api' do
      venkat = User.find_by_token(venkats_token)
      venkat.sync_from_facebook :photos

      venkat.photo.thumb.should match /\.jpg$/
      venkat.photo.large.should match /\.jpg$/

      venkat.photo.thumb.should_not == venkat.photo.large

      dan = User.find_by_token(dans_token)
      dan.sync_from_facebook :photos

      dan.photo.thumb.should match /\.jpg$/
      dan.photo.large.should match /\.jpg$/
    end

  end

  describe "mutual_facebook_friends_with" do

    it "should have the right number of users", :vcr, :cassette => 'facebook_api' do
      venkat = User.find_by_token(venkats_token)
      venkat.sync_from_facebook :friends

      dan = User.find_by_token(dans_token)
      dan.sync_from_facebook :friends

      venkat.mutual_facebook_friends_with(dan).count.should == 72
      dan.mutual_facebook_friends_with(venkat).count.should == 72
    end

  end

end
