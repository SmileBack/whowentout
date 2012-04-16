class Api::V1::SessionsController < Api::V1::BaseController

  def index
    if session[:user_id].nil?
      @user = nil
    else
      @user = User.find(session[:user_id])
    end
  end

  def create
    token = params[:token]
    user = User.find_by_token(token)

    unless user.nil?
      session[:user_id] = user.id
      session[:token] = token
    end
  end

  def destroy
    session[:user_id] = nil
    session[:token] = nil
  end

end
