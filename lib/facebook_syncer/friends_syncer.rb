module FacebookSyncer

  class FriendsSyncer

    def sync(user, facebook_token)
      hash = get_hash(facebook_token)
      update_user_from_hash(user, hash)
      user.save
    end

    def update_user_from_hash(user, hash)
      user.transaction do
        user.facebook_friendships.destroy_all
        hash.each do |friend_data|

          if friend_data['sex'].blank?
            friend_gender = nil
          else
            friend_gender = friend_data['sex'][0].upcase
          end

          friend = User.find_or_create_by_facebook_id(
                                                        :is_active => false,
                                                        :facebook_id => friend_data['uid'],
                                                        :first_name => friend_data['first_name'],
                                                        :last_name => friend_data['last_name'],
                                                        :gender => friend_gender
                                                      )

          update_user_from_networks_hash(friend, friend_data['affiliations'])
          user.facebook_friendships.create(:friend_id => friend.id)
        end

      end
    end

    def get_hash(token)
      api = Koala::Facebook::API.new(token)
      api.fql_query("SELECT uid, first_name, last_name, sex, affiliations FROM user
                      WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me())")
    end

    def update_user_from_networks_hash(user, hash)
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

  end

end