class Conversation < ActiveRecord::Base

  has_many :user_conversations
  has_many :users, :through => :user_conversations,
           :after_add => :compute_users_hash, :after_remove => :compute_users_hash
  has_many :messages, :order => 'created_at ASC',
           :after_add => :update_latest_message, :after_remove => :update_latest_message

  belongs_to :latest_message, :class_name => 'Message'

  def self.find_by_users(*users)
    Conversation.unscoped { Conversation.find_by_users_hash(calculate_users_hash users) }
  end

  def self.find_or_create_by_users(*users)
    conversation = self.find_by_users(*users)

    if conversation.nil?
      conversation = Conversation.create!(users: users)
    end

    return conversation
  end

  private

  def update_latest_message(message)
    self.latest_message = self.messages.last
    self.save
  end

  def compute_users_hash(user)
    self.users_hash = self.users.map(&:id).sort.join('-')
  end

  def self.calculate_users_hash(users)
    users.map(&:id).sort.join('-')
  end

end
