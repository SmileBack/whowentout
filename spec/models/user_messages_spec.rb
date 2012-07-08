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
      c = create(:user)

      a.send_message(b, 'hi b')
      c.send_message(b, 'whats up its c')
      b.send_message(a, 'hi a')

      a.reload
      b.reload

      a.messages.pluck(:body).should == ['hi a', 'hi b']
      b.messages.pluck(:body).should == ['hi a', 'whats up its c', 'hi b']
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

  describe "between" do

    it "should show only the messages between two users" do
      a = create(:user)
      b = create(:user)
      c = create(:user)

      a.send_message(b, "a to b")
      a.send_message(c, "a to c")
      b.send_message(a, "b to a")

      Message.between(a, b).pluck(:body).should == ['a to b', 'b to a']
    end

  end

end
