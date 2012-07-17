require Rails.root.join('lib', 'facebook_syncer')

class User < ActiveRecord::Base
  geocoded_by nil

  has_many :network_memberships
  has_many :networks, :through => :network_memberships

  has_many :user_interests
  has_many :interests, :through => :user_interests

  has_many :user_conversations
  has_many :conversations, :through => :user_conversations, :conditions => ['messages_count > ?', 0]

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

  has_many :smile_games_sent, :class_name => 'SmileGame', :foreign_key => 'sender_id', :order => 'created_at DESC'
  has_many :smile_games_received, :class_name => 'SmileGame', :foreign_key => 'receiver_id', :order => 'created_at DESC'

  validates_inclusion_of :gender, :in => ['M', 'F', nil]

  state_machine :status, :initial => :unregistered do

    event :register do
      transition :unregistered => :online
    end

    event :go_online do
      transition :offline => :online
    end

    event :go_offline do
      transition :online => :offline
    end

    state :online, :offline do
      def registered?
        true
      end
    end

    state :unregistered do
      def registered?
        false
      end
    end

  end

  def smile_games_sent_or_received
    t = SmileGame.arel_table
    sent_smile_game = t[:sender_id].eq(self.id)
    received_smile_game = t[:receiver_id].eq(self.id)

    SmileGame.where(sent_smile_game.or(received_smile_game))
  end

  def smile_games_matched
    smile_games_sent_or_received.where(status: 'match')
  end

  def smile_games_open
    self.smile_games_received.where(status: 'open')
  end

  def last_initial
    last_name[0].to_s.upcase + '.'
  end

  def age
    return nil if self.birthday.nil?

    now = Time.now.utc.to_date
    now.year - self.birthday.year - ((now.month > self.birthday.month || (now.month == self.birthday.month && now.day >= dob.day)) ? 0 : 1)
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

  def facebook_profile_picture(type = 'large')
    "https://graph.facebook.com/#{self.facebook_id}/picture?type=#{type}"
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

    return where('1=0') if user.status != 'online'

    results = where('1=1')

    unless blocked_user_ids.empty? #NOT IN(NULL) doesn't work as expected
      results = results.where( arel_table[:id].not_in(blocked_user_ids) )
    end

    results = results.where( arel_table[:status].eq('online') )

    return results
  end

  def nearby_users
    unless current_region.nil?
      User.in_region(current_region).except_user(self).visible_to(self)
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
    change_friendship_with(user, :remove_friendship)
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

  def self.find_by_token(token)
    facebook_id = self.get_facebook_id_from_token(token)
    return nil if facebook_id.nil?

    user = User.find_by_facebook_id(facebook_id)
    user = User.new if user.nil?

    if not user.registered?
      user.facebook_token = token

      user.sync_from_facebook :profile
      user.register!
      user.save
    end

    if user.facebook_token != token
      user.update_attributes(facebook_token: token)
    end

    return user
  end

  def self.token_cache
    @@token_cache ||= ActiveSupport::Cache::MemoryStore.new(:expires_in => 1.hour)
  end

  def self.get_facebook_id_from_token(token)
    return nil if token.nil?
    return nil if token == ""
    self.token_cache.fetch(token) do
      api = Koala::Facebook::API.new(token)
      begin
        profile_hash = api.get_object('me')
        profile_hash['id'].to_i
      rescue Koala::Facebook::APIError => e
        nil
      end
    end
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
    conversation = Conversation.find_or_create_by_users(self, user)

    message = conversation.messages.create!(
      sender: self,
      body: message_body
    )
    message.send_message!
  end

  # todo: move out of user object
  def start_smile_game_with(user, number_of_choices = 9)
    if can_start_smile_game_with?(user)
      SmileGame.create_for_user(user, self, number_of_choices)
    end
  end

  def notify(message)
    self.push(alert: message, badge: '+1', sound: 'default')
  end

  def push(message_data)
    unless self.iphone_push_token.nil?
      Urbanairship.push(
        device_tokens: [self.iphone_push_token],
        aps: message_data
      )
    end
  end

  def push_event(event)
    unless self.iphone_push_token.nil?
      Urbanairship.push(
        device_tokens: [self.iphone_push_token],
        aps: {},
        event: event
      )
    end
  end

  # todo: move out of user object
  def can_start_smile_game_with?(user)
    return false if started_smile_game_with?(user)
    return false if smile_count_today >= 3

    return true
  end

  def smile_count_today
    SmileGame.where(sender_id: self.id).direct.created_today.count
  end

  # todo: move out of user object?
  def started_smile_game_with?(user)
    not SmileGame.find_by_sender_id_and_receiver_id(self.id, user.id).nil?
  end

end

