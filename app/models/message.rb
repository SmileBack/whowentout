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

    after_transition :on => :send_message, :do => :on_conversation_new_message
  end

  private

  def on_conversation_new_message
    ActiveSupport::Notifications.instrument('conversation.new_message', conversation: self.conversation, message: self)
  end

end