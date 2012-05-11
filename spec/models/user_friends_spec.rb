require 'spec_helper'

describe User do

  describe "friends" do
    it "should start out as empty" do
      user = create(:user)
      user.friends.should be_empty
    end
  end

  describe "send_friend_request" do

    it "should show up in pending_friends" do
      a = create(:user)
      b = create(:user)

      a.send_friend_request(b)

      a.reload
      a.friend_requests(:pending).count.should == 0
      a.friend_requests(:sent).count.should == 1

      b.reload
      b.friend_requests(:pending).count.should == 1
      b.friend_requests(:sent).count.should == 0
    end

    it "make two users friends if they send each other a friend request" do
      a = create(:user)
      b = create(:user)

      a.send_friend_request(b)
      b.send_friend_request(a)

      a.reload
      b.reload

      a.friend_requests(:pending).count.should == 0
      b.friend_requests(:pending).count.should == 0

      a.is_friends_with?(b).should == true
      b.is_friends_with(a).should == true
    end

    it "should do nothing when sent twice" do
      a = create(:user)
      b = create(:user)

      a.send_friend_request(b)
      a.send_friend_request(b)

      b.friend_requests(:pending).count.should == 1
    end

  end

  describe "accepting a friend request" do
    it "should make two users friends with each other" do
      a = create(:user)
      b = create(:user)

      a.send_friend_request(a)

      b.friend_requests(:pending).first.accept!

      a.friends.include?(b).should == true
      b.friends.include?(a).should == true
    end
  end

  describe "ignoring a friend request" do

    it "should leave the pending requests" do
      a = create(:user)
      b = create(:user)

      a.send_friend_request(b)

      b.friend_requests(:pending).first.ignore!

      b.reload
      b.friend_requests(:pending).count.should == 0
    end

    it "should allow a user to resend a friend request" do
      a = create(:user)
      b = create(:user)

      a.send_friend_request(b)
      b.friend_requests(:pending).first.ignore!

      a.is_friends_with?(b).should == true
      b.is_friends_with?(a).should == true

      a.send_friend_request(b)
      b.reload
      b.friend_requests(:pending).first.accept!

      a.is_friends_with?(b).should == true
      b.is_friends_with?(a).should == true
    end

  end

  describe "removing a friend" do

    it "should make two people not friends anymore" do
      a = create(:user)
      b = create(:user)

      a.send_friend_request(b)
      b.friend_requests(:pending).first.accept!

      a.is_friends_with?(b).should == true
      b.is_friends_with?(a).should == true

      a.remove_friend(b)

      a.reload
      b.reload

      a.is_friends_with?(b).should == false
      b.is_friends_with?(a).should == false
    end

  end

end