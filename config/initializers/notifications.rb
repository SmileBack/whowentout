ActiveSupport::Notifications.subscribe 'smile_game.sent' do |name, start, finish, id, payload|
  return if Rails.env.test?

  smile_game = payload[:smile_game]
  smile_game.receiver.notify("Someone has just smiled at you.")
end

ActiveSupport::Notifications.subscribe 'smile_game.match' do |name, start, finish, id, payload|
  return if Rails.env.test?

  smile_game = payload[:smile_game]
  smile_game.sender.notify("You and #{smile_game.receiver.first_name} #{smile_game.receiver.last_initial} have matched.")
end

ActiveSupport::Notifications.subscribe 'conversation.new_message' do |name, start, finish, id, payload|
  return if Rails.env.test?

  conversation = payload[:conversation]
  message = payload[:message]

  message_summary = "#{message.sender.first_name} #{message.sender.last_initial}: #{message.body}"

  conversation.users.each do |u|
    u.push_event(
      name: 'ConversationNewMessage',
      message: {
        conversation_id: message.conversation_id,
        sender_id: message.sender_id,
        body: message.body
      }
    )
    u.notify(message_summary) unless u == message.sender
  end
end

