module FacebookSyncer

  class InterestsSyncer

    def sync(user, facebook_token)
      User.transaction do
        user.user_interests.destroy_all

        fetch_and_update_user_interests(facebook_token, user, 'interests')

        user.save
      end
    end

    def fetch_and_update_user_interests(token, user, category)
      fetch_user_interests(token, category).each do |interest|
        user.user_interests.create(:interest => interest)
      end
    end

    def fetch_user_interests(token, category)
      get_connections(token, category.pluralize).map do |interest_data|
        find_or_create_interest(category.singularize, interest_data)
      end
    end

    def get_connections(token, type)
      api = Koala::Facebook::API.new(token)
      api.get_connections('me', type)
    end

    def find_or_create_interest(category, interest_data)
      interest = Interest.find_by_facebook_id(interest_data['id'])

      if interest.nil?
        interest = Interest.create(
          :facebook_id => interest_data['id'],
          :name => interest_data['name'],
          :tag_list => category
        )
      end

      return interest
    end

  end

end
