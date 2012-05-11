class Friendship < ActiveRecord::Base
  belongs_to :user
  belongs_to :friend, :class_name => 'User'

  state_machine :status, :initial => :inactive do

    event :send do
      transition [:inactive, :ignored_by_you] => :sent
    end

    event :they_send do
      transition [:inactive, :ignored_by_them] => :pending
    end

    event :accept do
      transition :pending => :active
    end

    event :they_accept do
      transition :sent => :active
    end

    event :ignore do
      transition :pending => :ignored_by_you
    end

    event :they_ignore do
      transition :sent => :they_ignored
    end

    event :remove do
      transition :active => :inactive
    end

  end

end
