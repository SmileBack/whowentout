Boxer.box(:user) do |box, user, current_user|
  box.view(:base) do
    {
        id: user.id,
        name: user.first_name,
        age: user.age,
        networks: user.college_networks.pluck(:name).join(', '),
        college: user.college_networks.pluck(:name).join(', '),
        thumb: user.photo.thumb
    }
  end

  box.view(:full, :extends => :base) do
    {
      photos: user.photos.pluck(:large),
      hometown: user.hometown || "",
      current_city: user.current_city || "",
      relationship_status: user.relationship_status || "",
      interested_in: user.interested_in || "",
      work: user.work || "",
      mutual_friends: user.mutual_facebook_friends_with(current_user).map do |friend|
        {
            name: friend.first_name,
            thumb: "http://graph.facebook.com/#{friend.facebook_id}/picture?type=square"
        }
      end,
      interests: user.interests.map do |interest|
        {name: interest.name, thumb: interest.thumb}
      end
    }
  end

end
