class Friendship < ActiveRecord::Base
  belongs_to :user
  belongs_to :friend, :class_name => 'User'

  state_machine :status, :initial => :inactive do

    event :send_request do
      transition [:inactive, :ignored] => :sent
      # if you have a pending request from the other user, accept automatically
      transition :pending => :active
    end

    event :other_send_request do
      transition [:inactive, :other_ignored] => :pending
      # if you already sent the other user a request and they send you one, accept automatically
      transition :sent => :active
    end

    event :accept do
      transition :pending => :active
    end

    event :other_accept do
      transition :sent => :active
    end

    event :ignore do
      transition :pending => :ignored
    end

    event :other_ignore do
      transition :sent => :other_ignored
    end

    event :remove do
      transition :active => :inactive
    end

    event :other_remove do
      transition :active => :inactive
    end

    event :block do
      transition [:inactive, :sent, :pending, :ignored, :active, :other_ignored] => :blocked
    end

    event :other_block do
      transition [:inactive, :sent, :pending, :ignored, :active, :other_ignored] => :other_blocked
    end

    event :unblock do
      transition :blocked => :inactive
    end

    after_transition any => any do |friendship, transition|
      event_name = transition.event.to_s
      unless event_name.starts_with?("other_")
        inverse_transition = "other_#{event_name}"
        friendship.inverse_friendship.fire_status_event(inverse_transition)
      end
    end

  end

  def inverse_friendship
    Friendship.where(user_id: self.friend_id, friend_id: self.user_id).first
  end

end
