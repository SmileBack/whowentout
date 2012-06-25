require 'grape'

class WWOApi < Grape::API
  use Rack::Session::Cookie

  prefix 'api'
  version 'v1'
  format :json
  default_format :json

  helpers do

    def session
      env['rack.session']
    end

    def current_user
      User.find_by_token(params[:token])
    end

    def logged_in?
      User.find_by_token(params[:token]) != nil
    end

  end

  post 'location' do
    user = current_user

    longitude = params[:longitude].to_f
    latitude = params[:latitude].to_f

    user.update_location(longitude: longitude, latitude: latitude)

    {
        :success => true
    }
  end

  get 'location' do
    user = current_user

    {
        success: true,
        longitude: user.longitude,
        latitude: user.latitude
    }
  end

  get 'nearby' do
    response = {
      success: true,
      users: [],
      current_region: nil
    }

    user = current_user
    nearby_users = user.nearby_users
    unless nearby_users.nil?
      nearby_users.each do |u|
        response[:users] << {
            id: u.id,
            name: u.first_name,
            age: u.age,
            networks: u.college_networks.pluck(:name).join(', '),
            thumb: u.photo.thumb
        }
      end
    end

    unless user.current_region.nil?
      response[:current_region] = user.current_region.name
    end

    response
  end

  get 'me' do
    if logged_in?
      {
          :user => current_user
      }
    else
      {
          :user => nil
      }
    end
  end

  get 'login' do
    token = params[:token]

    error!({:error => 'Must provide a Facebook access token. None provided'}, 401) if token.nil?

    user = User.find_by_token(token)
    error!({:error => 'Invalid or expired Facebook access token.'}, 401) if user.nil?

    session[:user_id] = user.id
    session[:token] = token

    {
        :user => user
    }
  end

  get 'logout' do
    session[:user_id] = nil
    session[:token] = nil

    puts session.inspect

    {
        :success => true
    }
  end

end
