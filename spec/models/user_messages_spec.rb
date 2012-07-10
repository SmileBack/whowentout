require 'spec_helper'

describe User do


  describe "conversation" do


    it "should show only have between two users that talk to each other" do
      a = create(:user)
      b = create(:user)
      c = create(:user)

      a.send_message(b, "a to b")
      a.send_message(c, "a to c")
      b.send_message(a, "b to a")

      Conversation.between(a, b).messages.pluck(:body).should == ['b to a', 'a to b']
      Conversation.between(b, a).messages.pluck(:body).should == ['b to a', 'a to b']

      Conversation.between(a, c).messages.pluck(:body).should == ['a to c']
    end

    it "should be unique to the users that are involved" do
      a = create(:user)
      b = create(:user)

      convo = Conversation.between(a, b)
      convo.should_not be_nil

      same_convo = Conversation.between(b, a)
      same_convo.should == convo
    end

    it "should get populated with messages when they are sent" do
      a = create(:user)
      b = create(:user)

      a.send_message(b, 'hola')

      Conversation.between(a, b).messages.count.should == 1

      b.send_message(a, 'yo')
      Conversation.between(a, b).messages.count.should == 2
    end

    it "should get created when convos are sent" do
      a = create(:user)
      b = create(:user)
      c = create(:user)

      #debugger

      a.send_message(b, 'a-b')
      a.conversations.count.should == 1
      b.conversations.count.should == 1

      c.send_message(a, 'c-a')
      a.conversations.count.should == 2
      c.conversations.count.should == 1
    end

    it "woo should always have the latest message" do
      a = create(:user)
      b = create(:user)

      a.send_message(b, 'a-b')
      Conversation.between(a, b).latest_message.body.should == 'a-b'

      a.send_message(b, 'a-b-2')
      Conversation.between(a, b).latest_message.body.should == 'a-b-2'

      b.send_message(a, 'b-a')
      Conversation.between(a, b).latest_message.body.should == 'b-a'
    end

  end

end
