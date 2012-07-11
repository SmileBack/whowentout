Boxer.box(:conversation) do |box, conversation, current_user|

  box.view(:base) do
    latest_message = conversation.latest_message
    binding.pry

    other_user = (conversation.users - [current_user]).first
    {
      id: conversation.id,
      current_user_id: current_user.id,
      latest_message: latest_message.nil? ? nil : Boxer.ship(:message, latest_message),
      other_user: Boxer.ship(:user, other_user, current_user)
    }
  end

  box.view(:full, :extends => :base) do
    {
      messages: conversation.messages.map { |m| Boxer.ship(:message, m) }
    }
  end

end

