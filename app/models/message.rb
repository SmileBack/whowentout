class Message < ActiveRecord::Base

  belongs_to :sender, :class_name => 'User'
  belongs_to :conversation, :counter_cache => true

  state_machine :status, :initial => :composed do

    event :send_message do
      transition :composed => :sent
    end

    event :receive_message do
      transition :sent => :received
    end

    event :read_message do
      transition :received => :read
    end

    event :ignore_message do
      transition :received => :ignored
    end

    after_transition :on => :send_message, :do => :notify_users unless Rails.env.test?
  end

  def notify_users
    self.conversation.users.each do |u|
      u.push_event(
        name: 'ConversationNewMessage',
        message: {
          conversation_id: self.conversation_id,
          sender_id: self.sender_id,
          body: self.body
        }
      )
      unless u == self.sender
        u.notify(message_summary)
      end
    end
  end

  def message_summary
    "#{self.sender.first_name}: #{self.body}"
  end

end