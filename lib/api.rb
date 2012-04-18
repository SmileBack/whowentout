require 'grape'

class WWOApi < Grape::API
  use Rack::Session::Cookie

  prefix 'api'
  version 'v1'

  helpers do
    def session
      env['rack.session']
    end

    def current_user
      if logged_in?
        User.find(env['rack.session'][:user_id])
      end
    end

    def logged_in?
      return !env['rack.session'][:user_id].nil?
    end
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
