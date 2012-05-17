require Rails.root.join('lib', 'facebook_syncer')

class User < ActiveRecord::Base
  geocoded_by nil

  has_many :network_memberships
  has_many :networks, :through => :network_memberships

  has_many :user_interests
  has_many :interests, :through => :user_interests

  has_many :facebook_friendships
  has_many :facebook_friends, :through => :facebook_friendships, :source => :friend

  has_many :friendships
  has_many :active_friendships, :class_name => 'Friendship', :conditions => {:status => 'active'}
  has_many :friends, :through => :active_friendships, :source => :friend

  has_many :photos
  has_one :photo

  has_many :locations, :class_name => 'UserLocation', :order => 'created_at DESC', :inverse_of => :user
  has_one :current_location, :class_name => 'UserLocation', :conditions => {:is_active => true}
  has_many :past_locations, :class_name =>  'UserLocation', :conditions => {:is_active => false}, :order => 'created_at DESC'

  belongs_to :current_region, :class_name => 'Region'

  has_many :checkins, :order => 'created_at DESC', :inverse_of => :user
  has_one :current_checkin, :class_name => 'Checkin', :conditions => {:is_active => true}
  has_many :past_checkins, :class_name => 'Checkin', :conditions => {:is_active => false}, :order => 'created_at DESC'

  validates_inclusion_of :gender, :in => ['M', 'F', nil]

  def update_checkin(place)
    clear_checkin

    checkins.create!(place: place, is_active: true)

    reload
    save
  end
  alias_method :checkin_to, :update_checkin

  def clear_checkin
    unless current_checkin.nil?
      current_checkin.is_active = false
      current_checkin.save
      reload

      save
    end
  end

  def update_location(coordinates)
    c = convert_to_coordinates(coordinates)

    clear_location
    locations.create!(longitude: c[:longitude], latitude: c[:latitude], is_active: true)
    reload

    self.latitude = current_location.latitude
    self.longitude = current_location.longitude
    self.current_region = calculate_current_region

    save
  end

  def clear_location
    unless current_location.nil?
      current_location.is_active = false
      current_location.save
      reload

      self.latitude = nil
      self.longitude = nil
      self.current_region = calculate_current_region

      save
    end
  end

  def calculate_current_region
    if current_location.nil?
      nil
    else
      Region.including(to_coordinates).first
    end
  end

  def nearby_places
    unless current_location.nil?
      Place.near(to_coordinates, 50, :order => 'distance')
    end
  end

  def to_coordinates
    unless current_location.nil?
      super
    end
  end

  def self.in_region(region)
    where(current_region_id: region.id)
  end

  def self.except_user(user)
    where arel_table[:id].not_eq(user.id)
  end

  def self.visible_to(user)
    blocked_users = Friendship.where(user_id: user.id, status: ['blocked', 'other_blocked'])
    blocked_user_ids = blocked_users.pluck(:friend_id)

    return all if blocked_user_ids.empty? #need this because NOT IN(NULL) always resolves to false

    where arel_table[:id].not_in(blocked_user_ids)
  end

  def nearby_users
    unless current_region.nil?
      User.near(to_coordinates).in_region(current_region)
                               .except_user(self)
                               .visible_to(self)
    end
  end

  def college_networks
    networks.where(:network_type => 'college')
  end

  def mutual_facebook_friends_with(user)
    User.find_by_sql ["SELECT users.* FROM facebook_friendships AS a
                        INNER JOIN facebook_friendships AS b
                          ON a.user_id = ? AND b.user_id = ? AND a.friend_id = b.friend_id
                        INNER JOIN users ON users.id = a.friend_id", self.id, user.id]
  end

  def send_friend_request(user)
    change_friendship_with(user, :send_request)
  end

  def remove_friend(user)
    change_friendship_with(user, :remove)
  end

  def block_user(user)
    change_friendship_with(user, :block)
  end

  def unblock_user(user)
    change_friendship_with(user, :unblock)
  end

  def change_friendship_with(user, action)
    find_or_create_friendship_with(user).send(action)
  end

  def find_or_create_friendship_with(user)
    friendship = Friendship.find_by_user_id_and_friend_id(self.id, user.id)

    if friendship.nil?
      transaction do
        friendship = Friendship.create!(user: self, friend: user, status: 'inactive')
        inverse_friendship = Friendship.create!(user: user, friend: self, status: 'inactive')
      end
    end

    return friendship
  end

  def friend_requests(status)
    friendships.where(status: status.to_s)
  end

  def is_friends_with?(user)
    friends.include?(user)
  end

  def self.clear_all_locations
    User.joins(:current_location).each do |row|
      user = User.find(row.id)
      user.clear_location
    end
  end

  def self.clear_all_checkins
    User.joins(:current_checkin).each do |row|
      user = User.find(row.id)
      user.clear_checkin
    end
  end

  def self.find_by_token(token, fields_to_sync = [:networks, :friends, :interests, :profile_pictures])
    facebook_id = self.get_facebook_id_from_token(token)
    return nil if facebook_id.nil?

    user = User.find_by_facebook_id(facebook_id)
    user = User.new if user.nil?

    if user.is_inactive?
      user.facebook_token = token

      user.sync_from_facebook :profile
      user.is_active = true
      user.save

      user.sync_from_facebook(fields_to_sync)
    end

    if user.facebook_token != token
      user.update_attributes(facebook_token: token)
    end

    return user
  end

  def self.get_facebook_id_from_token(token)
    api = Koala::Facebook::API.new(token)

    begin
      profile_hash = api.get_object('me')
      profile_hash['id'].to_i
    rescue Koala::Facebook::APIError => e
      nil
    end
  end

  def is_inactive?
    not is_active?
  end

  def sync_from_facebook(fields)
    FacebookSyncer.sync_from_facebook(self, fields)
  end

  def sync_from_facebook_in_background(fields)
    Resque.enqueue(SyncFromFacebookWorker, :user_id => self.id, :fields => fields)
  end

  def convert_to_coordinates(coordinates)
    if coordinates.respond_to?(:longitude) && coordinates.respond_to?(:latitude)
      {latitude: coordinates.latitude, longitude: coordinates.longitude}
    elsif coordinates.is_a?(Array)
      {latitude: coordinates[0], longitude: coordinates[1]}
    elsif coordinates.is_a?(Hash)
      {latitude: coordinates[:latitude], longitude: coordinates[:longitude]} if coordinates[:latitude] != nil
    else
      raise "Failed to convert coordinates."
    end
  end

  def send_message(user, message_body)
    message = Message.create!(sender: self, receiver: user, body: message_body)
    message.send_message!
  end

  def messages
    Message.involving(self).where(status: ['sent', 'received', 'read']).order('created_at DESC')
  end

end

