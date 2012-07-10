Boxer.box(:message) do |box, message|

  box.view(:base) do
    {
      id: message.id,
      sender_id: message.sender_id,
      conversation_id: message.conversation_id,
      body: message.body,
      time: message.created_at
    }
  end

end
