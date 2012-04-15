class User < ActiveRecord::Base

  def self.find_by_token(token)
    api = Koala::Facebook::API.new(token)
    profile = api.get_object('me')
    facebook_id = profile['id'].to_i

    user = User.find_by_facebook_id(facebook_id)
    if user.nil?
      user = User.new

      user.facebook_id = profile['id'].to_i
      user.first_name = profile['first_name']
      user.last_name = profile['last_name']
      user.email = profile['email']
      user.save
    end

    return user
  end

end