Boxer.box(:conversation) do |box, conversation, current_user|

  box.view(:base) do
    other_user = (conversation.users - [current_user]).first
    {
      id: conversation.id,
      current_user_id: current_user.id,
      latest_message: Boxer.ship(:message, conversation.latest_message),
      other_user: Boxer.ship(:user, other_user, current_user)
    }
  end

  box.view(:full, :extends => :base) do
    {
      messages: conversation.messages.map { |m| Boxer.ship(:message, m) }
    }
  end

end

