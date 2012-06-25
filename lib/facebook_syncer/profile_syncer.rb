module FacebookSyncer

  class ProfileSyncer

    def sync(user, facebook_token)
      hash = get_hash(facebook_token)
      update_user_from_hash(user, hash)
      user.save
    end

    def update_user_from_hash(user, hash)
      user.gender = hash['gender'][0].upcase
      user.facebook_id = hash['id'].to_i
      user.first_name = hash['first_name']
      user.last_name = hash['last_name']
      user.email = hash['email']
      user.birthday = Date.strptime(hash['birthday'], "%m/%d/%Y")

      user.hometown = hash['hometown']['name'] unless hash['hometown'].nil?
      user.current_city = hash['location']['name'] unless hash['location'].nil?

      user.relationship_status = hash['relationship_status']
      user.interested_in = hash['interested_in'].sort.join(',')

      user.work = hash['work'].first['employer']['name'] unless hash['work'].nil?
    end

    def get_hash(token)
      api = Koala::Facebook::API.new(token)

      begin
        profile_hash = api.get_object('me')
      rescue Koala::Facebook::APIError => e
        profile_hash = nil
      end

      return profile_hash
    end

  end

end