class User < ActiveRecord::Base

  has_many :network_memberships

  has_many :networks, :through => :network_memberships

  validates_inclusion_of :gender, :in => ['M', 'F']

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

      user.save

      user.refresh_networks_from_facebook
    end

    return user
  end

  def refresh_networks_from_facebook
    networks.clear

    affiliations_hash = self.class.get_affiliations_hash(facebook_token)
    affiliations_hash.each do |affiliation_data|
      network = Network.find_or_create_by_facebook_id(:facebook_id => affiliation_data['nid'],
                                            :name => affiliation_data['name'],
                                            :network_type => affiliation_data['type'])

      networks << network
      save
    end
  end

  private

    def self.get_affiliations_hash(token)
      profile = get_profile_hash(token)
      uid = profile['id']

      api = Koala::Facebook::API.new(token)
      response = api.fql_query("SELECT affiliations FROM user WHERE uid=#{uid}")

      return response[0]['affiliations']
    end

    def self.get_profile_hash(token)
      @profile_hash_store ||= {}

      unless @profile_hash_store.has_key?(token)
        api = Koala::Facebook::API.new(token)

        begin
          @profile_hash_store[token] = api.get_object('me')
        rescue Koala::Facebook::APIError => e
          @profile_hash_store[token] = nil
        end
      end

      return @profile_hash_store[token]
    end

end