module FacebookSyncer

  class NetworksSyncer

    def sync(user, facebook_token)
      hash = get_hash(facebook_token)
      update_user_from_hash(user, hash)
      user.save
    end

    def update_user_from_hash(user, hash)
      user.transaction do
        user.networks.clear
        hash.each do |affiliation_data|
          network = Network.find_or_create_by_facebook_id(:facebook_id => affiliation_data['nid'],
                                                          :name => affiliation_data['name'],
                                                          :network_type => affiliation_data['type'])

          user.networks << network
        end
      end
    end

    def get_hash(token)
      api = Koala::Facebook::API.new(token)
      response = api.fql_query("SELECT affiliations FROM user WHERE uid=me()")

      response[0]['affiliations']
    end

  end

end