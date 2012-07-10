class Conversation < ActiveRecord::Base

  has_many :user_conversations
  has_many :users, :through => :user_conversations
  has_many :messages, :order => 'created_at DESC', :after_add => :update_latest_message

  belongs_to :latest_message, :class_name => 'Message'

  def self.between(*users)
    users_hash = users.map(&:id).sort.join('-')
    conversation = Conversation.find_by_users_hash(users_hash)

    if conversation.nil?
      conversation = Conversation.create!(users_hash: users_hash)
      users.each { |u| conversation.users << u }
      conversation.save
    end

    return conversation
  end

  def update_latest_message(message)
    self.latest_message = self.messages.first
    self.save
  end

end
