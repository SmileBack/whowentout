class User < ActiveRecord::Base

  has_many :network_memberships
  has_many :networks, :through => :network_memberships

  has_many :user_interests
  has_many :interests, :through => :user_interests

  has_many :facebook_friendships
  has_many :facebook_friends, :through => :facebook_friendships, :source => :friend

  has_many :photos
  has_one :photo

  has_many :locations, :class_name => 'UserLocation', :order => 'created_at DESC'
  has_one :current_location, :class_name => 'UserLocation', :conditions => {:is_active => true}
  has_many :past_locations, :class_name =>  'UserLocation', :conditions => {:is_active => false}, :order => 'created_at DESC'

  validates_inclusion_of :gender, :in => ['M', 'F', nil]

  def update_location(l)
    clear_location
    locations.create!(:longitude => l[:longitude], :latitude => l[:latitude], :is_active => true)
    reload
  end

  def nearby_places
    unless current_location.nil?
      Place.near(to_coordinates, 50, :order => 'distance')
    end
  end

  def to_coordinates
    unless current_location.nil?
      [current_location.latitude, current_location.longitude]
    end
  end

  def nearby_users
    unless current_location.nil?
      []
    end
  end

  def clear_location
    unless current_location.nil?
      current_location.is_active = false
      current_location.save
      reload
    end
  end

  def college_networks
    networks.where(:network_type => 'college')
  end

  def self.clear_all_locations
    UserLocation.where(:is_active =>  true).update_all(:is_active => false)
  end

  def self.find_by_token(token, sync = [:networks, :friends, :interests, :profile_pictures])
    profile = get_profile_hash(token)

    return nil if profile.nil?

    facebook_id = profile['id'].to_i

    user = User.find_by_facebook_id(facebook_id)
    user = User.new if user.nil?

    if user.is_inactive?
      user.facebook_token = token

      user.sync_profile_from_facebook
      user.is_active = true
      user.save

      user.sync_networks_from_facebook if sync.include?(:networks)
      user.sync_friends_from_facebook if sync.include?(:friends)
      user.sync_interests_from_facebook if sync.include?(:interests)
      user.sync_profile_pictures_from_facebook if sync.include?(:profile_pictures)
    end

    return user
  end

  def is_inactive?
    not is_active?
  end

  def sync_profile_from_facebook
    profile_hash = self.class.get_profile_hash(facebook_token)
    update_profile_from_data(profile_hash)
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

  def sync_profile_pictures_from_facebook
    profile_pictures_hash = self.class.get_profile_pictures_hash(facebook_token)
    update_profile_pictures_from_data(profile_pictures_hash)
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

  def update_profile_from_data(profile)
    self.gender = profile['gender'][0].upcase
    self.facebook_id = profile['id'].to_i
    self.first_name = profile['first_name']
    self.last_name = profile['last_name']
    self.email = profile['email']
    self.birthday = Date.strptime(profile['birthday'], "%m/%d/%Y")

    self.hometown = profile['hometown']['name']
    self.current_city = profile['location']['name']

    self.relationship_status = profile['relationship_status']
    self.interested_in = profile['interested_in'].sort.join(',')

    self.work = profile['work'].first['employer']['name'] unless profile['work'].nil?

    save
  end

  def update_facebook_friends_from_data(facebook_friends_hash)
    transaction do
      facebook_friendships.destroy_all
      facebook_friends_hash.each do |friend_data|

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

        friend.update_networks_from_data(friend_data['affiliations'])
        facebook_friendships.create(:friend_id => friend.id)
      end

      save
    end
  end

  def update_profile_pictures_from_data(profile_pictures_hash)
    transaction do
      photos.destroy_all

      profile_pictures_hash.each do |picture_data|
        photos.create(
          :facebook_id => picture_data['pid'],
          :created_at => Time.at(picture_data['created']),
          :thumb => picture_data['src_small'],
          :large => picture_data['src_big']
        )
      end

      photo = photos.first

      save
    end
  end

  def update_interests_from_data(interests_hash)
    transaction do
      user_interests.destroy_all
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

    def get_profile_pictures_hash(token)
      api = Koala::Facebook::API.new(token)
      response = api.fql_query("SELECT pid, created, src, src_small, src_big
                                FROM photo WHERE
                                  aid IN (SELECT aid FROM album WHERE owner = me() AND type = 'profile')")
      return response
    end

    def get_affiliations_hash(token)
      profile = get_profile_hash(token)
      uid = profile['id']

      api = Koala::Facebook::API.new(token)
      response = api.fql_query("SELECT affiliations FROM user WHERE uid=me()")

      return response[0]['affiliations']
    end

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

  end

end
