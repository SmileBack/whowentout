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

    unless current_user.current_region.nil?
      response[:current_region] = current_user.current_region.name
    end

    response
  end

  get 'users/:id' do
    authenticate!

    u = User.find(params[:id])

    {
        user: {
          id: u.id,
          name: u.first_name,
          age: u.age,
          photos: u.photos.pluck(:large),
          networks: u.networks.pluck(:name).join(', '),
          hometown: u.hometown || "",
          current_city: u.current_city || "",
          college: u.college_networks.pluck(:name).join(', '),
          relationship_status: u.relationship_status || "",
          interested_in: u.interested_in || "",
          work: '-',
          mutual_friends: u.mutual_facebook_friends_with(current_user).map do |friend|
            {
                name: friend.first_name,
                thumb: "http://graph.facebook.com/#{friend.facebook_id}/picture?type=square"
            }
          end,
          music: [],
          interests: u.interests.map do |interest|
            {name: interest.name}
          end,
          recent_places: []
        },
        success: true,
        request: params
    }
  end

  get 'me' do
    if logged_in?
      {user: current_user}
    else
      {user: nil}
    end
  end

end
