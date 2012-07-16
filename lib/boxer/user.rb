Boxer.box(:user) do |box, user, current_user|

  def image_path(name)
    "http://wwoapp.herokuapp.com/assets/#{name}"
  end

  box.view(:base) do
    thumb = user.photo ? user.photo.thumb : user.facebook_profile_picture('square')
    {
        id: user.id,
        name: user.first_name + ' ' + user.last_initial,
        age: user.age,
        gender: user.gender,
        networks: user.college_networks.pluck(:name).join(', '),
        college: user.college_networks.pluck(:name).join(', '),
        thumb: thumb
    }
  end

  box.view(:anonymous) do
    {
      gender: user.gender,
      thumb: image_path('user_anonymous_m.png')
    }
  end

  box.view(:profile, :extends => :base) do
    mutual_friends = []
    if user != current_user
      mutual_friends = current_user.mutual_facebook_friends_with(user).map do |friend|
        {
            name: friend.first_name,
            thumb: "http://graph.facebook.com/#{friend.facebook_id}/picture?type=square"
        }
      end
    end
    {
      photos: user.photos.pluck(:large),
      conversation_id: Conversation.find_or_create_by_users(user, current_user).id,
      hometown: user.hometown || "",
      current_city: user.current_city || "",
      relationship_status: user.relationship_status || "",
      interested_in: user.interested_in || "",
      work: user.work || "",
      mutual_friends: mutual_friends,
      interests: user.interests.map do |interest|
        {name: interest.name, thumb: interest.thumb}
      end
    }
  end

end

