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

    def unauthorized!(message)
      error!({:error => message}, 401)
    end

    def resource_not_found!(type)
      error!({:error => "This #{type} does not exist."}, 404)
    end

    def authenticate!
      token = params[:token]
      unauthorized!('Must provide a Facebook access token. None provided') if token.nil?
      unauthorized!('Invalid or expired Facebook access token.') if current_user.nil?
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
      response[:users] = Boxer.ship_all(:user, nearby_users)
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
    resource_not_found!('user') if user.nil?

    {
        user: Boxer.ship(:user, user, current_user, :view => :profile),
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
      conversations: Boxer.ship_all(:conversation, current_user.conversations, current_user)
    }
  end

  get 'conversations/:id' do
    authenticate!

    conversation = Conversation.find(params[:id])
    resource_not_found!('conversation') if conversation.nil?

    {
      success: true,
      conversation: Boxer.ship(:conversation, conversation, current_user, :view => :full)
    }
  end

  post 'conversations/:id/send' do
    authenticate!

    conversation = Conversation.find(params[:id])
    resource_not_found!('conversation') if conversation.nil?


    body = params[:body]

    message = conversation.messages.create!(
      sender: current_user,
      body: body
    )
    message.send_message!

    {
        success: true
    }
  end

  get 'smile-games/sent' do
    authenticate!

    {
      success: true,
      smile_games: Boxer.ship_all(:smile_game, current_user.smile_games_sent, :view => :sent)
    }
  end

  get 'me' do
    authenticate!

    {
      success: true,
      user: Boxer.ship(:user, current_user, current_user, :view => :profile)
    }
  end

end
