class Friendship < ActiveRecord::Base
  belongs_to :user
  belongs_to :friend, :class_name => 'User'

  state_machine :status, :initial => :inactive do

    event :send do
      transition [:inactive, :ignored_by_you] => :sent
      # call they_send on reverse friendship
    end

    event :they_send do
      transition [:inactive, :ignored_by_them] => :pending
    end

    event :accept do
      transition :pending => :active
      # call they_accept on reverse friendship
    end

    event :they_accept do
      transition :sent => :active
    end

    event :ignore do
      transition :pending => :ignored_by_you
      # call they_ignore on reverse friendship
    end

    event :they_ignore do
      transition :sent => :they_ignored
    end

    event :remove do
      transition :active => :inactive
    end

  end

  def reverse_friendship
    Friendship.find(user_id: self.friend_id, friend_id: self.user_id)
  end

end
