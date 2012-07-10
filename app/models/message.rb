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

    after_transition :on => :send_message, :do => :notify_receiver unless Rails.env.test?
    after_transition :on => :send_message, :do => :notify_sender unless Rails.env.test?
  end

  def notify_receiver
    self.receiver.push_event(
      name: 'UserDidReceiveMessage',
      message: {
        sender_id: self.sender_id,
        receiver_id: self.receiver_id,
        body: self.body
      }
    )

    self.receiver.notify("#{self.sender.first_name}: #{self.body}")
  end

  def notify_sender
    self.sender.push_event(
      name: 'UserDidSendMessage',
      message: {
        sender_id: self.sender_id,
        receiver_id: self.receiver_id,
        body: self.body
      }
    )
  end

end
