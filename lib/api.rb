require 'grape'

class WWOApi < Grape::API
  prefix 'api'
  version 'v1'

  format :json
  default_format :json

  helpers do

    def current_user
      @current_user ||= User.find_by_token(params[:token])
    end

    def logged_in?
      current_user != nil
    end

    def authenticate!
      token = params[:token]
      error!({:error => 'Must provide a Facebook access token. None provided'}, 401) if token.nil?
      error!({:error => 'Invalid or expired Facebook access token.'}, 401) if current_user.nil?
    end

  end

  post 'location' do
    authenticate!

    longitude = params[:longitude].to_f
    latitude = params[:latitude].to_f

    current_user.update_location(longitude: longitude, latitude: latitude)

    {success: true, request: params}
  end

  get 'location' do
    authenticate!

    {
        success: true,
        longitude: current_user.longitude,
        latitude: current_user.latitude,
        request: params
    }
  end

  get 'nearby' do
    authenticate!

    response = {
      success: true,
      users: [],
      current_region: nil,
      request: params
    }

    nearby_users = current_user.nearby_users
    unless nearby_users.nil?
      response[:users] = nearby_users.map do |user|
        Boxer.ship(:user, user)
      end
    end

    unless current_user.current_region.nil?
      response[:current_region] = current_user.current_region.name
    end
    response[:current_region] = '' if response[:current_region].nil?

    response
  end

  get 'users/:id' do
    authenticate!

    user = User.find(params[:id])

    {
        user: Boxer.ship(:user, user, current_user, :view => :full),
        success: true,
        request: params
    }
  end

  post 'push/register' do
    authenticate!

    current_user.iphone_push_token = params[:iphone_push_token]
    current_user.save

    {success: true}
  end

  get 'conversations' do
    authenticate!

    {
      success: true,
      conversations: current_user.conversations.map do |c|
        Boxer.ship(:conversation, c, current_user)
      end
    }
  end

  get 'conversations/:id' do
    authenticate!

    conversation = Conversation.find(params[:id])

    {
      success: true,
      conversation: Boxer.ship(:conversation, conversation, current_user, :view => :full)
    }
  end

  post 'users/:id/message' do
    authenticate!

    recipient = User.find(params[:id])
    body = params[:body]

    current_user.send_message(recipient, body) unless recipient.nil?

    {
        success: true
    }
  end

  get 'me' do
    if logged_in?
      {
          user: current_user
      }
    else
      {user: nil}
    end
  end

end
