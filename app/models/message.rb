class Message < ActiveRecord::Base
  belongs_to :sender, :class_name => 'User'
  belongs_to :receiver, :class_name => 'User'

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

  end

  def self.between(userA, userB)
    from_a_to_b = arel_table[:sender_id].eq(userA.id).and(arel_table[:receiver_id].eq(userB.id))
    from_b_to_a = arel_table[:sender_id].eq(userB.id).and(arel_table[:receiver_id].eq(userA.id))
    Message.where(from_a_to_b.or(from_b_to_a))
  end

  def self.involving(user)
    from_sender = arel_table[:sender_id].eq(user.id)
    to_sender = arel_table[:receiver_id].eq(user.id)
    Message.where(from_sender.or(to_sender))
  end

end
