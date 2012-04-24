class User < ActiveRecord::Base

  has_many :network_memberships
  has_many :networks, :through => :network_memberships

  has_many :user_interests
  has_many :interests, :through => :user_interests

  has_many :facebook_friendships
  has_many :facebook_friends, :through => :facebook_friendships, :source => :friend

  validates_inclusion_of :gender, :in => ['M', 'F', '?']

  def college_networks
    networks.where(:network_type => 'college')
  end

  def self.find_by_token(token)
    profile = get_profile_hash(token)

    return nil if profile.nil?

    facebook_id = profile['id'].to_i
    user = User.find_by_facebook_id(facebook_id)

    if user.nil?
      user = User.new

      user.facebook_token = token

      user.gender = profile['gender'][0].upcase
      user.facebook_id = profile['id'].to_i
      user.first_name = profile['first_name']
      user.last_name = profile['last_name']
      user.email = profile['email']
      user.birthday = Date.strptime(profile['birthday'], "%m/%d/%Y")

      user.hometown = profile['hometown']['name']
      user.current_city = profile['location']['name']

      user.is_active = true
      user.save

      user.sync_networks_from_facebook
      user.sync_friends_from_facebook
      user.sync_interests_from_facebook
    end

    return user
  end

  def sync_networks_from_facebook
    affiliations_hash = self.class.get_affiliations_hash(facebook_token)
    update_networks_from_data(affiliations_hash)
  end

  def sync_friends_from_facebook
    facebook_friends_hash = self.class.get_facebook_friends_hash(facebook_token)
    update_facebook_friends_from_data(facebook_friends_hash)
  end

  def sync_interests_from_facebook
    interests_hash = self.class.get_interests_hash(facebook_token)
    update_interests_from_data(interests_hash)
  end

    def update_networks_from_data(affiliations_hash)
      transaction do
        networks.clear
        affiliations_hash.each do |affiliation_data|
          network = Network.find_or_create_by_facebook_id(:facebook_id => affiliation_data['nid'],
                                                          :name => affiliation_data['name'],
                                                          :network_type => affiliation_data['type'])

          networks << network
        end
        save
      end
    end

    def update_facebook_friends_from_data(facebook_friends_hash)
      transaction do
        facebook_friendships.clear
        facebook_friends_hash.each do |friend_data|

          if friend_data['sex'].blank?
            friend_gender = '?'
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

          friend.update_networks_from_data(friend_data['affiliations'])
          facebook_friendships.create(:friend_id => friend.id)
        end

        save
      end
    end

    def update_interests_from_data(interests_hash)
      transaction do
        user_interests.clear
        interests_hash.each do |interest_data|
          interest = Interest.find_or_create_by_facebook_id(:facebook_id => interest_data['id'],
                                                            :name => interest_data['name'])
          user_interests.create(:interest => interest)
        end
        save
      end
    end

    class << self
      extend ActiveSupport::Memoizable

      def get_facebook_friends_hash(token)
        api = Koala::Facebook::API.new(token)
        response = api.fql_query("SELECT uid, first_name, last_name, sex, affiliations FROM user
                                  WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me())")
        return response
      end
      memoize :get_facebook_friends_hash

      def get_affiliations_hash(token)
        profile = get_profile_hash(token)
        uid = profile['id']

        api = Koala::Facebook::API.new(token)
        response = api.fql_query("SELECT affiliations FROM user WHERE uid=#{uid}")

        return response[0]['affiliations']
      end
      memoize :get_affiliations_hash

      def get_interests_hash(token)
        api = Koala::Facebook::API.new(token)
        return api.get_connections('me', 'interests')
      end

      def get_profile_hash(token)
        api = Koala::Facebook::API.new(token)

        begin
          profile_hash = api.get_object('me')
        rescue Koala::Facebook::APIError => e
          profile_hash = nil
        end

        return profile_hash
      end
      memoize :get_profile_hash

    end



end