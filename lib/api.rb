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

    response[:current_user] = Boxer.ship(:user, current_user)

    response
  end

  get 'me' do
    authenticate!

    user = current_user
    {
        user: Boxer.ship(:user, user, current_user, :view => :profile),
        success: true,
        request: params
    }
  end

  get 'users/:id' do
    authenticate!

    user = User.find(params[:id]) || resource_not_found!('user')

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

    conversation = Conversation.find(params[:id]) || resource_not_found!('conversation')

    {
      success: true,
      conversation: Boxer.ship(:conversation, conversation, current_user, :view => :full)
    }
  end

  post 'conversations/:id/send' do
    authenticate!

    conversation = Conversation.find(params[:id]) || resource_not_found!('conversation')

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

  post 'users/:id/start-smile-game' do
    authenticate!

    target_user = User.find(params[:id]) || resource_not_found!('user')

    if current_user.can_start_smile_game_with?(target_user)
      current_user.start_smile_game_with(target_user)
    else
      unauthorized!("Can't start smile game with user #{target_user.id}")
    end

    {
      success: true
    }
  end

  get 'smile-games-sent' do
    authenticate!

    {
      success: true,
      smile_games: Boxer.ship_all(:smile_game, current_user.smile_games_sent, :view => :sent)
    }
  end

  get 'smile-games-received' do
    authenticate!

    {
      success: true,
      smile_games: Boxer.ship_all(:smile_game, current_user.smile_games_received, :view => :received)
    }
  end

  get 'smile-games-matched' do
    authenticate!

    {
      success: true,
      smile_games: Boxer.ship_all(:smile_game, current_user.smile_games_matched, current_user, :view => :matched)
    }
  end

  get 'smile-games/:id' do
    authenticate!

    smile_game = SmileGame.find(params[:id]) || resource_not_found!('smile game')

    {
      success: true,
      smile_game: Boxer.ship(:smile_game, smile_game, :view => :full)
    }
  end

  post 'smile-games/:game_id/choices/:choice_id/guess' do
    authenticate!

    smile_game = SmileGame.find(params[:game_id]) || resource_not_found('smile game')
    smile_game_choice = smile_game.choices.find(params[:choice_id]) || resource_not_found('smile game choice')

    smile_game.guess(smile_game_choice)

    {
      success: true,
      smile_game: Boxer.ship(:smile_game, smile_game, :view => :full)
    }
  end

end
