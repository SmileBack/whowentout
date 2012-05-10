module FacebookSyncer

  class InterestsSyncer

    def sync(user, facebook_token)
      hash = get_hash(facebook_token)
      update_user_from_hash(user, hash)
      user.save
    end

    def update_user_from_hash(user, hash)
      user.transaction do
        user.user_interests.destroy_all
        hash.each do |interest_data|
          interest = Interest.find_or_create_by_facebook_id(:facebook_id => interest_data['id'],
                                                            :name => interest_data['name'])
          user.user_interests.create(:interest => interest)
        end
      end
    end

    def get_hash(token)
      api = Koala::Facebook::API.new(token)
      api.get_connections('me', 'interests')
    end

  end

end