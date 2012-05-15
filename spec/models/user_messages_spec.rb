require 'spec_helper'

describe User do

  describe "messages" do

    it "should start out as empty" do
      user = create(:user)

      user.messages.should be_empty
    end

    it "should be sorted in descending order" do
      a = create(:user)
      b = create(:user)

      a.send_message(b, 'hows it going')
      b.send_message(a, 'not too bad')

      a.reload
      b.reload

      a.messages.first.body.should == 'not too bad'
      a.messages.first.body.should == 'hows it going'
    end

  end

  describe "send_message" do

    it "should show up in the messages" do
      a = create(:user)
      b = create(:user)
      c = create(:user)

      a.send_message(b, "hey whats up")

      a.reload
      a.messages.count.should == 1

      b.reload
      b.messages.count.should == 1

      c.reload
      c.messages.count.should == 0

      b.messages.first.body.should == "hey whats up"
    end

  end

end
